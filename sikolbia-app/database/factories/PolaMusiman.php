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
        $komoditiCodes = ['0101', '0102', '0103', '0201', '0202', '0301', '0401', '0403', '0501', '0502'];
        $bulan = $this->faker->numberBetween(1, 12);
        
        // Generate seasonal patterns based on commodity type and month
        $baseFactor = 1.0;
        $kodeKomoditi = $this->faker->randomElement($komoditiCodes);
        
        // Rice (0102) - more stable throughout year
        if ($kodeKomoditi === '0102') {
            $baseFactor = $this->faker->randomFloat(4, 0.95, 1.05);
        }
        // Fruits (05xx) - seasonal variations
        elseif (substr($kodeKomoditi, 0, 2) === '05') {
            $baseFactor = $this->faker->randomFloat(4, 0.7, 1.4);
        }
        // Staples - moderate variations
        else {
            $baseFactor = $this->faker->randomFloat(4, 0.85, 1.15);
        }

        $volatilitas = $this->faker->randomFloat(4, 0.05, 0.3);
        $trend = $this->faker->randomFloat(4, -0.02, 0.02);
        
        return [
            'kode_komoditi' => $kodeKomoditi,
            'bulan' => $bulan,
            'faktor_musiman' => $baseFactor,
            'volatilitas_historis' => $volatilitas,
            'trend_5_tahun' => $trend,
            'confidence_interval_lower' => $baseFactor - ($volatilitas * 1.96),
            'confidence_interval_upper' => $baseFactor + ($volatilitas * 1.96),
        ];
    }

    /**
     * High seasonal variation
     */
    public function highSeasonal()
    {
        return $this->state(function (array $attributes) {
            $factor = $this->faker->randomFloat(4, 0.6, 1.6);
            $volatilitas = $this->faker->randomFloat(4, 0.2, 0.4);
            
            return [
                'faktor_musiman' => $factor,
                'volatilitas_historis' => $volatilitas,
                'confidence_interval_lower' => $factor - ($volatilitas * 1.96),
                'confidence_interval_upper' => $factor + ($volatilitas * 1.96),
            ];
        });
    }

    /**
     * Stable commodity
     */
    public function stable()
    {
        return $this->state(function (array $attributes) {
            $factor = $this->faker->randomFloat(4, 0.95, 1.05);
            $volatilitas = $this->faker->randomFloat(4, 0.02, 0.08);
            
            return [
                'faktor_musiman' => $factor,
                'volatilitas_historis' => $volatilitas,
                'confidence_interval_lower' => $factor - ($volatilitas * 1.96),
                'confidence_interval_upper' => $factor + ($volatilitas * 1.96),
            ];
        });
    }
}
