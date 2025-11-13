<?php

namespace App\Services;

class PredictionInsightService
{
    /**
     * Generate AI insights from prediction data
     */
    public function generateInsights(array $predictionData, array $historicalData): array
    {
        $insights = [
            'trend_analysis' => $this->analyzeTrend($predictionData),
            'volatility' => $this->analyzeVolatility($predictionData),
            'comparison_with_historical' => $this->compareWithHistorical($predictionData, $historicalData),
            'anomaly_detection' => $this->detectAnomalies($predictionData, $historicalData),
            'recommendations' => $this->generateRecommendations($predictionData, $historicalData),
            'risk_level' => $this->assessRiskLevel($predictionData),
            'summary' => ''
        ];

        // Generate summary text
        $insights['summary'] = $this->generateSummary($insights);

        return $insights;
    }

    /**
     * Analyze prediction trend (increasing, decreasing, stable)
     */
    private function analyzeTrend(array $predictions): array
    {
        if (count($predictions) < 2) {
            return [
                'direction' => 'stable',
                'percentage_change' => 0,
                'description' => 'Data tidak cukup untuk analisis trend'
            ];
        }

        $firstValue = $predictions[0];
        $lastValue = end($predictions);
        $percentageChange = (($lastValue - $firstValue) / $firstValue) * 100;

        $direction = 'stable';
        $description = 'Konsumsi cenderung stabil';

        if (abs($percentageChange) > 10) {
            if ($percentageChange > 0) {
                $direction = 'increasing';
                $description = sprintf('Konsumsi diprediksi meningkat %.1f%% dalam periode ini', abs($percentageChange));
            } else {
                $direction = 'decreasing';
                $description = sprintf('Konsumsi diprediksi menurun %.1f%% dalam periode ini', abs($percentageChange));
            }
        } elseif (abs($percentageChange) > 5) {
            $direction = $percentageChange > 0 ? 'slightly_increasing' : 'slightly_decreasing';
            $description = sprintf('Konsumsi menunjukkan perubahan moderat (%.1f%%)', $percentageChange);
        }

        return [
            'direction' => $direction,
            'percentage_change' => round($percentageChange, 2),
            'description' => $description,
            'icon' => $this->getTrendIcon($direction)
        ];
    }

    /**
     * Analyze volatility (standard deviation of predictions)
     */
    private function analyzeVolatility(array $predictions): array
    {
        $mean = array_sum($predictions) / count($predictions);
        $variance = 0;

        foreach ($predictions as $value) {
            $variance += pow($value - $mean, 2);
        }

        $stdDev = sqrt($variance / count($predictions));
        $coefficientOfVariation = ($stdDev / $mean) * 100;

        $level = 'low';
        $description = 'Prediksi menunjukkan stabilitas tinggi';

        if ($coefficientOfVariation > 20) {
            $level = 'high';
            $description = 'Prediksi menunjukkan volatilitas tinggi - perlu monitoring ketat';
        } elseif ($coefficientOfVariation > 10) {
            $level = 'moderate';
            $description = 'Prediksi menunjukkan volatilitas moderat';
        }

        return [
            'level' => $level,
            'coefficient' => round($coefficientOfVariation, 2),
            'std_deviation' => round($stdDev, 2),
            'description' => $description
        ];
    }

    /**
     * Compare prediction with historical average
     */
    private function compareWithHistorical(array $predictions, array $historicalData): array
    {
        if (empty($historicalData)) {
            return [
                'comparison' => 'no_data',
                'description' => 'Tidak ada data historis untuk perbandingan'
            ];
        }

        $historicalValues = array_column($historicalData, 'kalori_hari');
        $historicalAvg = array_sum($historicalValues) / count($historicalValues);
        $predictionAvg = array_sum($predictions) / count($predictions);

        $difference = (($predictionAvg - $historicalAvg) / $historicalAvg) * 100;

        $comparison = 'similar';
        $description = sprintf(
            'Prediksi rata-rata (%.2f) sejalan dengan historis (%.2f)',
            $predictionAvg,
            $historicalAvg
        );

        if (abs($difference) > 15) {
            if ($difference > 0) {
                $comparison = 'higher';
                $description = sprintf(
                    'Prediksi %.1f%% lebih tinggi dari rata-rata historis - indikasi peningkatan permintaan',
                    abs($difference)
                );
            } else {
                $comparison = 'lower';
                $description = sprintf(
                    'Prediksi %.1f%% lebih rendah dari rata-rata historis - perlu investigasi penyebab penurunan',
                    abs($difference)
                );
            }
        }

        return [
            'comparison' => $comparison,
            'difference_percentage' => round($difference, 2),
            'prediction_avg' => round($predictionAvg, 2),
            'historical_avg' => round($historicalAvg, 2),
            'description' => $description
        ];
    }

    /**
     * Detect anomalies in predictions
     */
    private function detectAnomalies(array $predictions, array $historicalData): array
    {
        $anomalies = [];

        // Calculate historical bounds
        if (!empty($historicalData)) {
            $historicalValues = array_column($historicalData, 'kalori_hari');
            $mean = array_sum($historicalValues) / count($historicalValues);
            $stdDev = $this->calculateStdDev($historicalValues, $mean);

            $upperBound = $mean + (2 * $stdDev);
            $lowerBound = $mean - (2 * $stdDev);

            foreach ($predictions as $idx => $value) {
                if ($value > $upperBound) {
                    $anomalies[] = [
                        'period' => $idx + 1,
                        'value' => $value,
                        'type' => 'high',
                        'description' => sprintf('Nilai anomali tinggi (%.2f) pada bulan ke-%d', $value, $idx + 1)
                    ];
                } elseif ($value < $lowerBound && $lowerBound > 0) {
                    $anomalies[] = [
                        'period' => $idx + 1,
                        'value' => $value,
                        'type' => 'low',
                        'description' => sprintf('Nilai anomali rendah (%.2f) pada bulan ke-%d', $value, $idx + 1)
                    ];
                }
            }
        }

        $hasAnomalies = !empty($anomalies);
        $description = $hasAnomalies
            ? sprintf('Terdeteksi %d anomali dalam prediksi - perlu perhatian khusus', count($anomalies))
            : 'Tidak ada anomali terdeteksi - prediksi dalam batas normal';

        return [
            'has_anomalies' => $hasAnomalies,
            'count' => count($anomalies),
            'items' => $anomalies,
            'description' => $description
        ];
    }

    /**
     * Generate recommendations based on analysis
     */
    private function generateRecommendations(array $predictions, array $historicalData): array
    {
        $recommendations = [];

        $trend = $this->analyzeTrend($predictions);
        $volatility = $this->analyzeVolatility($predictions);
        $comparison = $this->compareWithHistorical($predictions, $historicalData);

        // Recommendation based on trend
        if ($trend['direction'] === 'increasing') {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'supply',
                'text' => 'Siapkan stok tambahan untuk mengantisipasi peningkatan konsumsi',
                'icon' => '📈'
            ];
        } elseif ($trend['direction'] === 'decreasing') {
            $recommendations[] = [
                'priority' => 'medium',
                'category' => 'analysis',
                'text' => 'Investigasi penyebab penurunan konsumsi dan evaluasi strategi distribusi',
                'icon' => '🔍'
            ];
        }

        // Recommendation based on volatility
        if ($volatility['level'] === 'high') {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'monitoring',
                'text' => 'Tingkatkan frekuensi monitoring karena volatilitas tinggi',
                'icon' => '⚠️'
            ];
        }

        // Recommendation based on comparison
        if ($comparison['comparison'] === 'higher') {
            $recommendations[] = [
                'priority' => 'medium',
                'category' => 'planning',
                'text' => 'Koordinasi dengan supplier untuk memastikan ketersediaan stok',
                'icon' => '📦'
            ];
        }

        // Default recommendations
        if (empty($recommendations)) {
            $recommendations[] = [
                'priority' => 'low',
                'category' => 'routine',
                'text' => 'Lanjutkan monitoring rutin dan maintain stok sesuai prediksi',
                'icon' => '✅'
            ];
        }

        return $recommendations;
    }

    /**
     * Assess overall risk level
     */
    private function assessRiskLevel(array $predictions): array
    {
        $volatility = $this->analyzeVolatility($predictions);
        $trend = $this->analyzeTrend($predictions);

        $riskScore = 0;

        // Volatility contribution
        if ($volatility['level'] === 'high') $riskScore += 3;
        elseif ($volatility['level'] === 'moderate') $riskScore += 2;
        else $riskScore += 1;

        // Trend contribution
        if ($trend['direction'] === 'increasing' || $trend['direction'] === 'decreasing') {
            $riskScore += 2;
        }

        $level = 'low';
        $description = 'Risiko rendah - situasi terkendali';
        $color = 'green';

        if ($riskScore >= 4) {
            $level = 'high';
            $description = 'Risiko tinggi - perlu tindakan proaktif';
            $color = 'red';
        } elseif ($riskScore >= 3) {
            $level = 'medium';
            $description = 'Risiko moderat - perlu monitoring ketat';
            $color = 'yellow';
        }

        return [
            'level' => $level,
            'score' => $riskScore,
            'description' => $description,
            'color' => $color
        ];
    }

    /**
     * Generate overall summary
     */
    private function generateSummary(array $insights): string
    {
        $trend = $insights['trend_analysis'];
        $risk = $insights['risk_level'];

        $summary = sprintf(
            'Prediksi menunjukkan trend %s dengan tingkat risiko %s. %s',
            $this->getTrendText($trend['direction']),
            $risk['level'],
            $trend['description']
        );

        return $summary;
    }

    /**
     * Helper: Calculate standard deviation
     */
    private function calculateStdDev(array $values, float $mean): float
    {
        $variance = 0;
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        return sqrt($variance / count($values));
    }

    /**
     * Helper: Get trend icon
     */
    private function getTrendIcon(string $direction): string
    {
        return match($direction) {
            'increasing' => '📈',
            'decreasing' => '📉',
            'slightly_increasing' => '↗️',
            'slightly_decreasing' => '↘️',
            default => '➡️'
        };
    }

    /**
     * Helper: Get trend text in Indonesian
     */
    private function getTrendText(string $direction): string
    {
        return match($direction) {
            'increasing' => 'meningkat',
            'decreasing' => 'menurun',
            'slightly_increasing' => 'sedikit meningkat',
            'slightly_decreasing' => 'sedikit menurun',
            default => 'stabil'
        };
    }
}
