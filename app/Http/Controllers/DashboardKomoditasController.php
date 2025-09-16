<?php

namespace App\Http\Controllers;

use App\Models\TransaksiNbm;
use App\Models\Kelompok;
use App\Models\Komoditi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardKomoditasController extends Controller
{
    /**
     * Get commodities data for dashboard
     */
    public function getCommoditiesData(Request $request)
    {
        try {
            $metric = $request->get('metric', 'harga');
            $period = $request->get('period', '1Y'); // Changed default to 1Y for better performance
            $group = $request->get('group', '');
            $search = $request->get('search', '');
            $limit = $request->get('limit', 20);

            // First, get all commodities with their groups
            $allCommoditiesQuery = Komoditi::with(['kelompok'])
                ->select(['kode_kelompok', 'kode_komoditi', 'nama', 'harga_rata_per_kg']);

            // Filter by group if specified
            if ($group) {
                $allCommoditiesQuery->where('kode_kelompok', $group);
            }

            $allCommodities = $allCommoditiesQuery->get();

            // Get latest transaction data for commodities that have it
            $transactionQuery = TransaksiNbm::with(['kelompok', 'komoditi'])
                ->select([
                    'kode_kelompok',
                    'kode_komoditi',
                    'tahun',
                    'bulan',
                    'kg_tahun',
                    'harga_produsen',
                    'harga_konsumen',
                    'masukan',
                    'keluaran',
                    'impor',
                    'ekspor',
                    'bahan_makanan',
                    'kalori_hari',
                    'protein_hari',
                    'inflasi_komoditi',
                    'updated_at'
                ])
                ->where('tahun', '>=', 2020);

            if ($group) {
                $transactionQuery->where('kode_kelompok', $group);
            }

            $transactionData = $transactionQuery->get()
                ->groupBy(function ($item) {
                    return $item->kode_kelompok . '-' . $item->kode_komoditi;
                })
                ->map(function ($commodityData) {
                    return $commodityData->sortByDesc(function ($item) {
                        return ($item->tahun ?? 2020) * 100 + ($item->bulan ?? 12);
                    })->first();
                });

            // Apply search filter
            if ($search) {
                $searchLower = strtolower($search);
                $allCommodities = $allCommodities->filter(function ($item) use ($searchLower) {
                    return str_contains(strtolower($item->nama ?? ''), $searchLower) ||
                           str_contains(strtolower($item->kelompok->nama ?? ''), $searchLower);
                });
            }

            // Transform data for frontend - remove take($limit) to show all commodities
            $commodities = $allCommodities->map(function ($commodity) use ($metric, $period, $transactionData) {
                $commodityKey = $commodity->kode_kelompok . '-' . $commodity->kode_komoditi;
                $transactionItem = $transactionData->get($commodityKey);
                
                // Check if commodity has transaction data
                $hasData = $transactionItem !== null;
                
                if ($hasData) {
                    // Commodity with transaction data
                    $currentValue = $this->getCurrentValue($transactionItem, $metric);
                    $historicalData = $this->getHistoricalData($commodity->kode_kelompok, $commodity->kode_komoditi, $period, $metric);
                    $change = $this->calculateChange($historicalData, $currentValue);
                    $changePercent = $this->calculateChangePercent($historicalData, $currentValue);
                    $lastUpdate = $this->getLastUpdateText($transactionItem->updated_at);
                } else {
                    // Commodity without transaction data - use defaults
                    $currentValue = $commodity->harga_rata_per_kg ?? 0;
                    $historicalData = [];
                    $change = 0;
                    $changePercent = 0;
                    $lastUpdate = 'No Data';
                }

                return [
                    'id' => $commodity->kode_kelompok . '-' . $commodity->kode_komoditi,
                    'name' => $commodity->nama ?? 'Unknown',
                    'group' => $commodity->kode_kelompok,
                    'groupName' => $commodity->kelompok->nama ?? 'Unknown Group',
                    'currentValue' => $currentValue,
                    'change' => $change,
                    'changePercent' => $changePercent,
                    'unit' => $this->getUnit($metric),
                    'lastUpdate' => $lastUpdate,
                    'hasData' => $hasData,
                    'yearHigh' => $hasData ? $this->getYearHigh($commodity->kode_kelompok, $commodity->kode_komoditi, $metric) : $currentValue,
                    'yearLow' => $hasData ? $this->getYearLow($commodity->kode_kelompok, $commodity->kode_komoditi, $metric) : $currentValue,
                    'average' => $hasData ? $this->getAverage($commodity->kode_kelompok, $commodity->kode_komoditi, $metric) : $currentValue,
                    'volatility' => $hasData ? $this->getVolatility($commodity->kode_kelompok, $commodity->kode_komoditi, $metric) : 0,
                    'chartData' => $historicalData
                ];
            })->filter()->values(); // Remove null entries

            return response()->json([
                'success' => true,
                'data' => $commodities,
                'total' => $allCommodities->count(),
                'hasMore' => $allCommodities->count() > $limit
            ])->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching commodities data: ' . $e->getMessage(),
                'data' => []
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Get dashboard summary statistics
     */
    public function getSummaryStats()
    {
        try {
            $totalCommodities = TransaksiNbm::distinct('kode_komoditi')->count();
            
            // Get recent data for trend calculation
            $recentData = TransaksiNbm::where('tahun', '>=', 2023)
                ->whereNotNull('harga_konsumen')
                ->where('harga_konsumen', '>', 0)
                ->get();

            // Adjust thresholds - inflasi_komoditi seems to be in format where 1.0 = 100%
            $trendUp = $recentData->filter(function ($item) {
                return ($item->inflasi_komoditi ?? 0) > 0.02; // 2% threshold
            })->count();

            $trendDown = $recentData->filter(function ($item) {
                return ($item->inflasi_komoditi ?? 0) < -0.02; // -2% threshold
            })->count();

            $highVolatility = $recentData->filter(function ($item) {
                return abs($item->inflasi_komoditi ?? 0) > 0.1; // 10% threshold
            })->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'totalCommodities' => $totalCommodities,
                    'trendUp' => $trendUp,
                    'trendDown' => $trendDown,
                    'highVolatility' => $highVolatility,
                    'trendUpPercent' => $recentData->count() > 0 ? round(($trendUp / $recentData->count()) * 100, 1) : 0,
                    'trendDownPercent' => $recentData->count() > 0 ? round(($trendDown / $recentData->count()) * 100, 1) : 0
                ]
            ])->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching summary stats: ' . $e->getMessage(),
                'data' => []
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Get current value based on metric
     */
    private function getCurrentValue($item, $metric)
    {
        switch ($metric) {
            case 'harga':
                return $item->harga_konsumen ?? $item->harga_produsen ?? 0;
            case 'produksi':
                return $item->masukan ?? 0;
            case 'perdagangan':
                return ($item->impor ?? 0) + ($item->ekspor ?? 0);
            case 'ketersediaan':
                return $item->kg_tahun ?? 0;
            default:
                return $item->harga_konsumen ?? 0;
        }
    }

    /**
     * Get historical data for chart
     */
    private function getHistoricalData($kodeKelompok, $kodeKomoditi, $period, $metric)
    {
        $monthsBack = match($period) {
            '2M' => 2,
            '4M' => 4,
            '6M' => 6,
            '1Y' => 12,
            'All Time' => null, // No limit for All Time
            default => 12
        };

        // For All Time period, show all available data
        if ($period === 'All Time' || $monthsBack === null) {
            // Get the earliest record to show full historical range
            $earliestRecord = TransaksiNbm::where('kode_kelompok', $kodeKelompok)
                ->where('kode_komoditi', $kodeKomoditi)
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->first();

            if (!$earliestRecord) {
                return $this->generateSampleData(12);
            }

            $startDate = \Carbon\Carbon::createFromDate($earliestRecord->tahun, $earliestRecord->bulan, 1);
        } else {
            // For specific periods, use latest record of THIS commodity as reference
            $latestRecord = TransaksiNbm::where('kode_kelompok', $kodeKelompok)
                ->where('kode_komoditi', $kodeKomoditi)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->first();

            if (!$latestRecord) {
                return $this->generateSampleData($monthsBack);
            }

            // Calculate start date from THIS commodity's latest available data
            $latestDate = \Carbon\Carbon::createFromDate($latestRecord->tahun, $latestRecord->bulan, 1);
            $startDate = $latestDate->copy()->subMonths($monthsBack - 1); // -1 because we want to include current month
        }
        
        $data = TransaksiNbm::where('kode_kelompok', $kodeKelompok)
            ->where('kode_komoditi', $kodeKomoditi)
            ->where(function ($query) use ($startDate) {
                $query->where('tahun', '>', $startDate->year)
                    ->orWhere(function ($q) use ($startDate) {
                        $q->where('tahun', $startDate->year)
                          ->where('bulan', '>=', $startDate->month);
                    });
            })
            ->select([
                'tahun', 'bulan', 
                DB::raw('AVG(harga_konsumen) as harga_konsumen'),
                DB::raw('AVG(harga_produsen) as harga_produsen'),
                DB::raw('AVG(masukan) as masukan'),
                DB::raw('AVG(impor) as impor'),
                DB::raw('AVG(ekspor) as ekspor'),
                DB::raw('AVG(kg_tahun) as kg_tahun')
            ])
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->map(function ($item) use ($metric) {
                // Safety check to ensure item has required properties
                if (!$item || !isset($item->tahun)) {
                    return null;
                }
                
                return [
                    'x' => $item->tahun . '-' . str_pad($item->bulan ?? 12, 2, '0', STR_PAD_LEFT),
                    'y' => $this->getCurrentValue($item, $metric)
                ];
            })
            ->filter() // Remove null entries
            ->values()
            ->toArray();

        // If still no data, generate sample data
        if (empty($data)) {
            return $this->generateSampleData($monthsBack);
        }

        return $data;
    }

    /**
     * Generate sample data when no real data is available
     */
    private function generateSampleData($monthsBack)
    {
        $data = [];
        $baseValue = rand(1000, 50000);
        for ($i = $monthsBack; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $variation = rand(-10, 10) / 100;
            $data[] = [
                'x' => $date->format('Y-m'),
                'y' => $baseValue * (1 + $variation)
            ];
        }
        return $data;
    }

    /**
     * Calculate change from historical data
     */
    private function calculateChange($historicalData, $currentValue)
    {
        if (count($historicalData) < 2) return 0;
        
        $previousValue = $historicalData[count($historicalData) - 2]['y'] ?? $currentValue;
        return $currentValue - $previousValue;
    }

    /**
     * Calculate percentage change
     */
    private function calculateChangePercent($historicalData, $currentValue)
    {
        if (count($historicalData) < 2) return 0;
        
        $previousValue = $historicalData[count($historicalData) - 2]['y'] ?? $currentValue;
        if ($previousValue == 0) return 0;
        
        return (($currentValue - $previousValue) / $previousValue) * 100;
    }

    /**
     * Get unit based on metric
     */
    private function getUnit($metric)
    {
        return match($metric) {
            'harga' => 'Rp/kg',
            'produksi' => 'ribu ton',
            'perdagangan' => 'ribu ton', 
            'ketersediaan' => 'kg/kapita/tahun',
            default => 'Rp/kg'
        };
    }

    /**
     * Get last update text in Indonesian date format
     */
    private function getLastUpdateText($updatedAt)
    {
        if (!$updatedAt) return 'Unknown';
        
        $carbon = \Carbon\Carbon::parse($updatedAt);
        
        // Return in "15 September 2025, 15:20" format
        return $carbon->format('j F Y, H:i');
    }

    /**
     * Get year high value
     */
    private function getYearHigh($kodeKelompok, $kodeKomoditi, $metric)
    {
        $yearData = TransaksiNbm::where('kode_kelompok', $kodeKelompok)
            ->where('kode_komoditi', $kodeKomoditi)
            ->where('tahun', '>=', now()->year - 1)
            ->get();

        if ($yearData->isEmpty()) return 0;

        return $yearData->map(function ($item) use ($metric) {
            return $this->getCurrentValue($item, $metric);
        })->max();
    }

    /**
     * Get year low value
     */
    private function getYearLow($kodeKelompok, $kodeKomoditi, $metric)
    {
        $yearData = TransaksiNbm::where('kode_kelompok', $kodeKelompok)
            ->where('kode_komoditi', $kodeKomoditi)
            ->where('tahun', '>=', now()->year - 1)
            ->get();

        if ($yearData->isEmpty()) return 0;

        return $yearData->map(function ($item) use ($metric) {
            return $this->getCurrentValue($item, $metric);
        })->filter(function ($value) {
            return $value > 0;
        })->min();
    }

    /**
     * Get average value
     */
    private function getAverage($kodeKelompok, $kodeKomoditi, $metric)
    {
        $yearData = TransaksiNbm::where('kode_kelompok', $kodeKelompok)
            ->where('kode_komoditi', $kodeKomoditi)
            ->where('tahun', '>=', now()->year - 1)
            ->get();

        if ($yearData->isEmpty()) return 0;

        $values = $yearData->map(function ($item) use ($metric) {
            return $this->getCurrentValue($item, $metric);
        })->filter(function ($value) {
            return $value > 0;
        });

        return $values->isEmpty() ? 0 : $values->avg();
    }

    /**
     * Get volatility (coefficient of variation)
     */
    private function getVolatility($kodeKelompok, $kodeKomoditi, $metric)
    {
        $yearData = TransaksiNbm::where('kode_kelompok', $kodeKelompok)
            ->where('kode_komoditi', $kodeKomoditi)
            ->where('tahun', '>=', now()->year - 1)
            ->get();

        if ($yearData->count() < 2) return 5; // Default low volatility

        $values = $yearData->map(function ($item) use ($metric) {
            return $this->getCurrentValue($item, $metric);
        })->filter(function ($value) {
            return $value > 0;
        });

        if ($values->count() < 2) return 5;

        $mean = $values->avg();
        $variance = $values->map(function ($value) use ($mean) {
            return pow($value - $mean, 2);
        })->avg();

        $stdDev = sqrt($variance);
        
        // Coefficient of variation as percentage
        return $mean > 0 ? ($stdDev / $mean) * 100 : 5;
    }
}