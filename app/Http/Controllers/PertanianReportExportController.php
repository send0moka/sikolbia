<?php

namespace App\Http\Controllers;

use App\Services\ReportExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PertanianReportExportController extends Controller
{
	public function __construct(private ReportExportService $exportService) {}

	public function export(Request $request, string $moduleType)
	{
		try {
			// Reuse validation rules of PertanianReportController
			$validated = $this->validatePayload($request, $moduleType);
			$filename = $request->input('filename');
			return $this->exportService->exportToExcel($moduleType, $validated['selections'], $validated['config'], $filename);
		} catch (ValidationException $e) {
			return response()->json(['message' => 'Data input tidak valid.', 'errors' => $e->errors()], 422);
		} catch (\Throwable $e) {
			Log::error('PertanianReport export error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
			return response()->json(['message' => 'Terjadi kesalahan saat export.'], 500);
		}
	}

	private function validatePayload(Request $request, string $moduleType): array
	{
		$rulesBase = [
			'selections' => 'required|array|min:1',
			'config' => 'required|array',
			'config.tata_letak' => 'required|string|in:tipe_1,tipe_2,tipe_3',
			'config.provinsi_ids' => 'nullable|array',
			'config.kabupaten_ids' => 'nullable|array',
		];
		$monthly = in_array($moduleType, ['benih-pupuk', 'iklim-opt-dpi']);
		if ($monthly) {
			$rulesSelections = [
				'selections.*.variabel_id' => 'required|integer',
				'selections.*.tahun_ids' => 'required|array|min:1',
				'selections.*.klasifikasi_ids' => 'required|array|min:1',
				'selections.*.bulan_ids' => 'required|array|min:1',
			];
		} else {
			$rulesSelections = [
				'selections.*.variabel_id' => 'required|integer',
				'selections.*.tahuns' => 'required|array|min:1',
				'selections.*.klasifikasi_ids' => 'required|array|min:1',
			];
		}
		return $request->validate($rulesBase + $rulesSelections);
	}
}

