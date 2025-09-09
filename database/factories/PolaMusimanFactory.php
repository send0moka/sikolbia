<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PolaMusiman>
 */
class PolaMusimanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_komoditi' => $this->faker->randomElement([
                '0101', '0102', '0103', '0104', '0105', '0106',
                '0201', '0202', '0203', '0204', '0205',
                '0301', '0302',
                '0401', '0402', '0403', '0404', '0405', '0406',
                '0501', '0502', '0503', '0504', '0505', '0506'
            ]),
            'bulan' => $this->faker->numberBetween(1, 12),
            'faktor_musiman' => $this->faker->randomFloat(4, 0.8, 1.2),
            'volatilitas_historis' => $this->faker->randomFloat(4, 0.05, 0.3),
            'trend_5_tahun' => $this->faker->randomFloat(4, -0.1, 0.1),
            'confidence_interval_lower' => $this->faker->randomFloat(4, 0.7, 0.9),
            'confidence_interval_upper' => $this->faker->randomFloat(4, 1.1, 1.3),
        ];
    }

    /**
     * High seasonal variation pattern (for fruits)
     */
    public function highSeasonal()
    {
        return $this->state(function (array $attributes) {
            $bulan = $attributes['bulan'] ?? $this->faker->numberBetween(1, 12);
            
            // Create seasonal peaks for different months
            $seasonalFactor = match(true) {
                in_array($bulan, [6, 7, 8]) => $this->faker->randomFloat(4, 1.3, 1.6), // Peak season
                in_array($bulan, [12, 1, 2]) => $this->faker->randomFloat(4, 0.5, 0.7), // Low season
                default => $this->faker->randomFloat(4, 0.9, 1.1) // Normal season
            };

            return [
                'faktor_musiman' => $seasonalFactor,
                'volatilitas_historis' => $this->faker->randomFloat(4, 0.2, 0.4),
                'trend_5_tahun' => $this->faker->randomFloat(4, -0.05, 0.05),
                'confidence_interval_lower' => $seasonalFactor - 0.2,
                'confidence_interval_upper' => $seasonalFactor + 0.2,
            ];
        });
    }

    /**
     * Stable pattern (for rice and staples)
     */
    public function stable()
    {
        return $this->state(function (array $attributes) {
            return [
                'faktor_musiman' => $this->faker->randomFloat(4, 0.95, 1.05),
                'volatilitas_historis' => $this->faker->randomFloat(4, 0.02, 0.08),
                'trend_5_tahun' => $this->faker->randomFloat(4, -0.02, 0.02),
                'confidence_interval_lower' => $this->faker->randomFloat(4, 0.9, 0.95),
                'confidence_interval_upper' => $this->faker->randomFloat(4, 1.05, 1.1),
            ];
        });
    }
}
