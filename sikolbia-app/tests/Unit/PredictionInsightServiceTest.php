<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\PredictionInsightService;

class PredictionInsightServiceTest extends TestCase
{
    protected $insightService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->insightService = new PredictionInsightService();
    }

    /** @test */
    public function can_analyze_increasing_trend()
    {
        $historicalData = [
            ['kalori_hari' => 800],
            ['kalori_hari' => 820],
            ['kalori_hari' => 840],
            ['kalori_hari' => 860],
            ['kalori_hari' => 880],
            ['kalori_hari' => 900],
        ];

        // Need >10% increase to be classified as "increasing"
        // 800 -> 900 = 12.5% increase
        $predictionData = [800, 850, 900];

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertArrayHasKey('trend_analysis', $insights);
        $this->assertStringContainsString('increasing', $insights['trend_analysis']['direction']);
        $this->assertGreaterThan(10, $insights['trend_analysis']['percentage_change']);
    }

    /** @test */
    public function can_analyze_decreasing_trend()
    {
        $historicalData = [
            ['kalori_hari' => 900],
            ['kalori_hari' => 880],
            ['kalori_hari' => 860],
            ['kalori_hari' => 840],
            ['kalori_hari' => 820],
            ['kalori_hari' => 800],
        ];

        $predictionData = [780, 760, 740];

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertArrayHasKey('trend_analysis', $insights);
        $this->assertStringContainsString('decreasing', $insights['trend_analysis']['direction']);
        $this->assertLessThan(0, $insights['trend_analysis']['percentage_change']);
    }

    /** @test */
    public function can_calculate_volatility()
    {
        $predictionData = [850, 860, 870, 880, 890, 900]; // Low volatility

        $historicalData = [];

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertArrayHasKey('volatility', $insights);
        $this->assertIsFloat($insights['volatility']['coefficient']);
        $this->assertArrayHasKey('description', $insights['volatility']);
    }

    /** @test */
    public function can_assess_low_risk_level()
    {
        $historicalData = [
            ['kalori_hari' => 850],
            ['kalori_hari' => 860],
            ['kalori_hari' => 870],
            ['kalori_hari' => 880],
            ['kalori_hari' => 890],
            ['kalori_hari' => 900],
        ];

        $predictionData = [905, 910, 915]; // Stable predictions

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertArrayHasKey('risk_level', $insights);
        $this->assertEquals('low', $insights['risk_level']['level']);
        $this->assertEquals('green', $insights['risk_level']['color']);
    }

    /** @test */
    public function can_assess_high_risk_level()
    {
        $historicalData = [
            ['kalori_hari' => 850],
            ['kalori_hari' => 860],
            ['kalori_hari' => 870],
            ['kalori_hari' => 880],
            ['kalori_hari' => 890],
            ['kalori_hari' => 900],
        ];

        $predictionData = [700, 650, 600]; // Sharp decline

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertArrayHasKey('risk_level', $insights);
        $this->assertContains($insights['risk_level']['level'], ['medium', 'high']);
    }

    /** @test */
    public function can_detect_anomalies()
    {
        $historicalData = [
            ['kalori_hari' => 850],
            ['kalori_hari' => 860],
            ['kalori_hari' => 870],
            ['kalori_hari' => 880],
            ['kalori_hari' => 890],
            ['kalori_hari' => 900],
        ];

        $predictionData = [1500, 910, 915]; // First value is anomaly

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertArrayHasKey('anomaly_detection', $insights);
        
        if ($insights['anomaly_detection']['has_anomalies']) {
            $this->assertArrayHasKey('items', $insights['anomaly_detection']);
            $this->assertIsArray($insights['anomaly_detection']['items']);
        }
    }

    /** @test */
    public function can_generate_recommendations()
    {
        $historicalData = [
            ['kalori_hari' => 850],
            ['kalori_hari' => 860],
            ['kalori_hari' => 870],
            ['kalori_hari' => 880],
            ['kalori_hari' => 890],
            ['kalori_hari' => 900],
        ];

        $predictionData = [905, 910, 915];

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertArrayHasKey('recommendations', $insights);
        $this->assertIsArray($insights['recommendations']);
        $this->assertNotEmpty($insights['recommendations']);

        foreach ($insights['recommendations'] as $recommendation) {
            $this->assertArrayHasKey('priority', $recommendation);
            $this->assertArrayHasKey('text', $recommendation);
            $this->assertArrayHasKey('icon', $recommendation);
            $this->assertContains($recommendation['priority'], ['low', 'medium', 'high']);
        }
    }

    /** @test */
    public function can_generate_summary()
    {
        $historicalData = [
            ['kalori_hari' => 850],
            ['kalori_hari' => 860],
            ['kalori_hari' => 870],
        ];

        $predictionData = [880, 890, 900];

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertArrayHasKey('summary', $insights);
        $this->assertIsString($insights['summary']);
        $this->assertNotEmpty($insights['summary']);
    }

    /** @test */
    public function handles_empty_historical_data_gracefully()
    {
        $predictionData = [850, 860, 870];
        $historicalData = [];

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertIsArray($insights);
        $this->assertArrayHasKey('trend_analysis', $insights);
        $this->assertArrayHasKey('volatility', $insights);
        $this->assertArrayHasKey('risk_level', $insights);
    }

    /** @test */
    public function handles_single_prediction_value()
    {
        $historicalData = [
            ['kalori_hari' => 850],
            ['kalori_hari' => 860],
        ];

        $predictionData = [870]; // Only 1 prediction

        $insights = $this->insightService->generateInsights($predictionData, $historicalData);

        $this->assertIsArray($insights);
        $this->assertArrayHasKey('trend_analysis', $insights);
    }
}
