<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\StructuredSearchController;

Route::middleware(['chatbot.anon', 'throttle:chatbot-user'])
	->withoutMiddleware('throttle:api')
	->group(function () {
		Route::post('/chatbot', [ChatbotController::class, 'handle']);
		Route::post('/chatbot/reset', [ChatbotController::class, 'reset']);
		Route::post('/chatbot/summary', [ChatbotController::class, 'summary']);
	});
Route::get('/structured/search', [StructuredSearchController::class, 'search']);

use App\Models\TransaksiNbm;
use App\Models\Komoditi;

// Debug preview endpoint (only available when APP_DEBUG=true)
Route::post('/debug/prediksi-preview', function (\Illuminate\Http\Request $request) {
	if (!env('APP_DEBUG', false)) {
		return response()->json(['success' => false, 'message' => 'Not available'], 403);
	}

	// Optional extra guard: require DEBUG_PREVIEW_TOKEN header or local request
	$debugToken = env('DEBUG_PREVIEW_TOKEN', null);
	$provided = $request->header('x-debug-token');
	$isLocal = in_array($request->ip(), ['127.0.0.1', '::1']);
	if ($debugToken) {
		if (empty($provided) || $provided !== $debugToken) {
			return response()->json(['success' => false, 'message' => 'Invalid debug token'], 403);
		}
	} else {
		if (!$isLocal) {
			return response()->json(['success' => false, 'message' => 'Not available from remote hosts'], 403);
		}
	}

	$request->validate([
		'kelompok' => 'required|string',
		'komoditi' => 'required|string',
		'bulan' => 'required|integer|min:1|max:12'
	]);

	$kelompok = $request->kelompok;
	$komoditi = $request->komoditi;
	$bulanPrediksi = $request->bulan;

	$historicalData = TransaksiNbm::where('kode_kelompok', $kelompok)
		->where('kode_komoditi', $komoditi)
		->orderBy('tahun', 'desc')
		->orderBy('bulan', 'desc')
		->limit(6)
		->get();

	if ($historicalData->count() < 6) {
		return response()->json(['success' => false, 'message' => 'Not enough historical data (min 6 months)'], 400);
	}

	$komoditiInfo = Komoditi::where('kode_komoditi', $komoditi)->first();
	if (!$komoditiInfo) return response()->json(['success' => false, 'message' => 'Komoditi not found'], 404);

	$groupAvgKalori = Komoditi::where('kode_kelompok', $kelompok)->where('kalori_per_100g', '>', 0)->avg('kalori_per_100g') ?: 0;
	$globalAvgKalori = Komoditi::where('kalori_per_100g', '>', 0)->avg('kalori_per_100g') ?: 0;
	$defaultKaloriPer100g = $groupAvgKalori ?: $globalAvgKalori ?: 200;

	$avgGramPerCapitaKomoditi = TransaksiNbm::where('kode_komoditi', $komoditi)
		->where('makanan', '>', 0)
		->where('populasi_indonesia', '>', 0)
		->get()
		->map(function($it) {
			$makananTons = floatval($it->makanan) * 1000;
			$makananKg = $makananTons * 1000;
			$kgPerCapitaPerYear = $makananKg / max(1.0, floatval($it->populasi_indonesia));
			return ($kgPerCapitaPerYear * 1000) / 365;
		})->avg() ?: 0;

	$avgGramPerCapitaGroup = TransaksiNbm::where('kode_kelompok', $kelompok)
		->where('makanan', '>', 0)
		->where('populasi_indonesia', '>', 0)
		->get()
		->map(function($it) {
			$makananTons = floatval($it->makanan) * 1000;
			$makananKg = $makananTons * 1000;
			$kgPerCapitaPerYear = $makananKg / max(1.0, floatval($it->populasi_indonesia));
			return ($kgPerCapitaPerYear * 1000) / 365;
		})->avg() ?: 0;

	$defaultGramsPerDay = $avgGramPerCapitaKomoditi ?: $avgGramPerCapitaGroup ?: 100;
	// enforce sensible floor to avoid tiny gram/day artifacts (in grams)
	$minGramsPerDay = 30.0;
	if ($defaultGramsPerDay < $minGramsPerDay) $defaultGramsPerDay = $minGramsPerDay;

	$data_points = $historicalData->map(function($item) use ($komoditiInfo, $kelompok, $komoditi, $defaultKaloriPer100g, $defaultGramsPerDay, $minGramsPerDay) {
		$kaloriHari = 0;
		$usedFallback = false;
		$resultGramFallback = false;

		// Prefer per-row komoditi caloric density if available
		$rowKaloriPer100g = floatval($item->komoditi->kalori_per_100g ?? $komoditiInfo->kalori_per_100g ?? 0);
		if ($item->makanan > 0 && $item->populasi_indonesia > 0) {
			if ($rowKaloriPer100g <= 0) { $rowKaloriPer100g = $defaultKaloriPer100g; $usedFallback = true; }

			// Try two unit interpretations for `makanan`: thousand-tons (legacy) and tons
			$m_val = floatval($item->makanan);
			$pop = max(1.0, floatval($item->populasi_indonesia));

			// Interpretation A: makanan is in thousand tons -> tons = m*1000
			$kgA = ($m_val * 1000.0) * 1000.0; // thousand tons -> tons -> kg
			$gramPerCapitaA = ($kgA / $pop) * 1000.0 / 365.0;

			// Interpretation B: makanan is in tons -> kg = m*1000
			$kgB = ($m_val) * 1000.0; // tons -> kg
			$gramPerCapitaB = ($kgB / $pop) * 1000.0 / 365.0;

			// Prefer the interpretation that yields more realistic grams/day (> minGramsPerDay)
			if ($gramPerCapitaA >= $minGramsPerDay || $gramPerCapitaA >= $gramPerCapitaB) {
				$gramPerCapitaPerDay = $gramPerCapitaA;
			} else {
				$gramPerCapitaPerDay = $gramPerCapitaB;
			}

			// If still below floor, apply minimum and mark fallback
			if ($gramPerCapitaPerDay < $minGramsPerDay) {
				$gramPerCapitaPerDay = $minGramsPerDay;
				$usedFallback = true;
				$resultGramFallback = true;
			}

			$kaloriHari = ($gramPerCapitaPerDay / 100.0) * $rowKaloriPer100g;
		} else {
			$estGrams = $defaultGramsPerDay ?? 100;
			$usedFallback = true; $resultGramFallback = true;
			$kaloriHari = ($estGrams / 100.0) * ($rowKaloriPer100g > 0 ? $rowKaloriPer100g : $defaultKaloriPer100g);
		}

		$result = [
			'tahun' => (int)$item->tahun,
			'bulan' => (int)$item->bulan,
			'kelompok' => str_pad($kelompok, 2, '0', STR_PAD_LEFT),
			'komoditi' => str_pad($komoditi, 4, '0', STR_PAD_LEFT),
			'kalori_hari' => (float)round($kaloriHari, 2)
		];

		if ($usedFallback) {
			$result['used_fallback_kalori_per_100g'] = true;
			$result['fallback_kalori_per_100g'] = (float)$defaultKaloriPer100g;
			if (!empty($resultGramFallback)) {
				$result['used_fallback_grams_per_day'] = true;
				$result['fallback_grams_per_day'] = (float)$defaultGramsPerDay;
			}
		}

		return $result;
	})->values()->toArray();

	$invalidMonths = [];
	$validCaloriesCount = 0;
	foreach ($data_points as $d) {
		if (!isset($d['kalori_hari']) || floatval($d['kalori_hari']) <= 0) {
			$invalidMonths[] = ($d['tahun'] ?? 'n/a') . '-' . str_pad(($d['bulan'] ?? '0'), 2, '0', STR_PAD_LEFT);
		} else {
			$validCaloriesCount++;
		}
	}

	return response()->json([
		'success' => true,
		'historical' => $data_points,
		'invalid_months' => $invalidMonths,
		'has_valid_calories' => $validCaloriesCount > 0
	]);
});