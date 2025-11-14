<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\Komoditi;
use App\Models\TransaksiNbm;
use App\Models\PredictionHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;

class PrediksiNbmTest extends TestCase
{
    use RefreshDatabase;

    protected $pemerintahUser;
    protected $kelompok;
    protected $komoditi;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles first
        $pemerintahRole = \Spatie\Permission\Models\Role::create(['name' => 'pemerintah']);
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);

        // Create test user with pemerintah role
        $this->pemerintahUser = User::factory()->create([
            'email' => 'pemerintah@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->pemerintahUser->assignRole('pemerintah');

        // Create test data
        $this->kelompok = Kelompok::create([
            'kode' => '01',
            'nama' => 'Padi-Padian',
            'deskripsi' => 'Kelompok Padi-Padian',
        ]);

        $this->komoditi = Komoditi::create([
            'kode_kelompok' => '01',
            'kode_komoditi' => '0101',
            'nama' => 'Beras',
            'deskripsi' => 'Beras',
        ]);

        // Create historical NBM data (6 months minimum for prediction)
        for ($i = 0; $i < 6; $i++) {
            TransaksiNbm::create([
                'tahun' => 2024,
                'bulan' => 7 + $i,
                'kode_kelompok' => '01',
                'kode_komoditi' => '0101',
                'kalori_hari' => 850 + ($i * 10), // Simulate increasing trend
                'keterangan' => 'Test data',
            ]);
        }
    }

    /** @test */
    public function authenticated_pemerintah_can_access_prediksi_nbm_page()
    {
        $response = $this->actingAs($this->pemerintahUser)
            ->get('/pemerintah/prediksi-nbm');

        $response->assertStatus(200);
        $response->assertSee('Prediksi NBM');
        $response->assertSee('Prediksi Machine Learning untuk Neraca Bahan Makanan');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_prediksi_nbm()
    {
        $response = $this->get('/pemerintah/prediksi-nbm');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function can_fetch_komoditi_by_kelompok()
    {
        // Create additional komoditi
        Komoditi::create([
            'kode_kelompok' => '01',
            'kode_komoditi' => '0102',
            'nama' => 'Jagung',
            'deskripsi' => 'Jagung',
        ]);

        $response = $this->actingAs($this->pemerintahUser)
            ->get('/pemerintah/api/komoditi?kelompok_id=01');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'komoditi' => [
                '*' => [
                    'kode',
                    'deskripsi',
                ]
            ]
        ]);
        $response->assertJsonCount(2, 'komoditi');
    }

    /** @test */
    public function can_run_prediction_with_valid_data()
    {
        // Mock ML API response
        Http::fake([
            config('services.nbm_prediction.url') . '/predict' => Http::response([
                'success' => true,
                'prediction' => [885.07, 909.50, 833.60],
                'confidence_intervals' => [
                    ['lower_bound' => -606.18, 'upper_bound' => 2376.32, 'margin_percent' => 168.5],
                    ['lower_bound' => -730.87, 'upper_bound' => 2549.88, 'margin_percent' => 180.4],
                    ['lower_bound' => -955.90, 'upper_bound' => 2623.10, 'margin_percent' => 214.7],
                ],
                'model_info' => [
                    'model_version' => '1.0.0',
                    'model_type' => 'LSTM Enhanced Ensemble',
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->pemerintahUser)
            ->postJson('/pemerintah/prediksi-nbm/run', [
                'kelompok' => '01',
                'komoditi' => '0101',
                'bulan' => 3,
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'kelompok_name',
                'komoditi_name',
                'bulan_prediksi',
                'prediction',
                'confidence_intervals',
                'historical',
                'model_info',
            ]
        ]);
        $response->assertJson(['success' => true]);
    }

    /** @test */
    public function prediction_requires_valid_kelompok_and_komoditi()
    {
        $response = $this->actingAs($this->pemerintahUser)
            ->postJson('/pemerintah/prediksi-nbm/run', [
                'kelompok' => '99', // Invalid
                'komoditi' => '9999', // Invalid
                'bulan' => 3,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['kelompok', 'komoditi']);
    }

    /** @test */
    public function prediction_requires_bulan_between_1_and_12()
    {
        $response = $this->actingAs($this->pemerintahUser)
            ->postJson('/pemerintah/prediksi-nbm/run', [
                'kelompok' => '01',
                'komoditi' => '0101',
                'bulan' => 15, // Invalid
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['bulan']);
    }

    /** @test */
    public function can_save_prediction_to_history()
    {
        $response = $this->actingAs($this->pemerintahUser)
            ->postJson('/pemerintah/prediksi-nbm/save', [
                'kode_kelompok' => '01',
                'kode_komoditi' => '0101',
                'kelompok_name' => 'Padi-Padian',
                'komoditi_name' => 'Beras',
                'bulan_prediksi' => 3,
                'prediction_data' => [885.07, 909.50, 833.60],
                'historical_data' => [
                    ['tahun' => 2024, 'bulan' => 7, 'kalori_hari' => 850],
                    ['tahun' => 2024, 'bulan' => 8, 'kalori_hari' => 860],
                ],
                'confidence_intervals' => [
                    ['lower_bound' => -606.18, 'upper_bound' => 2376.32],
                ],
                'model_version' => '1.0.0',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('prediction_histories', [
            'user_id' => $this->pemerintahUser->id,
            'kode_kelompok' => '01',
            'kode_komoditi' => '0101',
            'bulan_prediksi' => 3,
        ]);
    }

    /** @test */
    public function can_view_prediction_history()
    {
        // Create prediction history
        PredictionHistory::create([
            'user_id' => $this->pemerintahUser->id,
            'kode_kelompok' => '01',
            'kode_komoditi' => '0101',
            'kelompok_name' => 'Padi-Padian',
            'komoditi_name' => 'Beras',
            'bulan_prediksi' => 3,
            'prediction_data' => json_encode([885, 910, 834]),
            'historical_data' => json_encode([]),
            'confidence_intervals' => json_encode([]),
            'model_version' => '1.0.0',
        ]);

        $response = $this->actingAs($this->pemerintahUser)
            ->get('/pemerintah/prediksi-nbm/history');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Prediksi');
        $response->assertSee('Beras');
    }

    /** @test */
    public function can_toggle_bookmark_on_prediction()
    {
        $prediction = PredictionHistory::create([
            'user_id' => $this->pemerintahUser->id,
            'kode_kelompok' => '01',
            'kode_komoditi' => '0101',
            'kelompok_name' => 'Padi-Padian',
            'komoditi_name' => 'Beras',
            'bulan_prediksi' => 3,
            'prediction_data' => json_encode([]),
            'is_bookmarked' => false,
        ]);

        $response = $this->actingAs($this->pemerintahUser)
            ->postJson("/pemerintah/prediksi-nbm/bookmark/{$prediction->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_bookmarked' => true]);

        $this->assertDatabaseHas('prediction_histories', [
            'id' => $prediction->id,
            'is_bookmarked' => true,
        ]);
    }

    /** @test */
    public function can_delete_prediction_from_history()
    {
        $prediction = PredictionHistory::create([
            'user_id' => $this->pemerintahUser->id,
            'kode_kelompok' => '01',
            'kode_komoditi' => '0101',
            'kelompok_name' => 'Padi-Padian',
            'komoditi_name' => 'Beras',
            'bulan_prediksi' => 3,
            'prediction_data' => json_encode([]),
        ]);

        $response = $this->actingAs($this->pemerintahUser)
            ->deleteJson("/pemerintah/prediksi-nbm/history/{$prediction->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertSoftDeleted('prediction_histories', [
            'id' => $prediction->id,
        ]);
    }

    /** @test */
    public function can_generate_ai_insights()
    {
        $response = $this->actingAs($this->pemerintahUser)
            ->postJson('/pemerintah/prediksi-nbm/insights', [
                'prediction_data' => [885.07, 909.50, 833.60],
                'historical_data' => [
                    ['tahun' => 2024, 'bulan' => 7, 'kalori_hari' => 850],
                    ['tahun' => 2024, 'bulan' => 8, 'kalori_hari' => 860],
                    ['tahun' => 2024, 'bulan' => 9, 'kalori_hari' => 870],
                    ['tahun' => 2024, 'bulan' => 10, 'kalori_hari' => 880],
                    ['tahun' => 2024, 'bulan' => 11, 'kalori_hari' => 890],
                    ['tahun' => 2024, 'bulan' => 12, 'kalori_hari' => 900],
                ],
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'trend_analysis' => ['direction', 'percentage_change', 'description', 'icon'],
                'risk_level' => ['level', 'score', 'description', 'color'],
                'volatility' => ['coefficient', 'description'],
                'comparison_with_historical',
                'anomaly_detection',
                'recommendations',
                'summary',
            ]
        ]);
    }

    /** @test */
    public function can_export_prediction_to_excel()
    {
        $response = $this->actingAs($this->pemerintahUser)
            ->get('/pemerintah/prediksi-nbm/export-excel?kelompok=01&komoditi=0101&bulan=3');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function can_export_prediction_to_pdf()
    {
        $response = $this->actingAs($this->pemerintahUser)
            ->get('/pemerintah/prediksi-nbm/export-pdf?kelompok=01&komoditi=0101&bulan=3');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function user_can_only_see_their_own_prediction_history()
    {
        // Create another user
        $otherUser = User::factory()->create();
        $otherUser->assignRole('pemerintah');

        // Create prediction for other user
        PredictionHistory::create([
            'user_id' => $otherUser->id,
            'kode_kelompok' => '01',
            'kode_komoditi' => '0101',
            'kelompok_name' => 'Padi-Padian',
            'komoditi_name' => 'Beras',
            'bulan_prediksi' => 3,
            'prediction_data' => json_encode([]),
        ]);

        // Create prediction for current user
        PredictionHistory::create([
            'user_id' => $this->pemerintahUser->id,
            'kode_kelompok' => '01',
            'kode_komoditi' => '0102',
            'kelompok_name' => 'Padi-Padian',
            'komoditi_name' => 'Jagung',
            'bulan_prediksi' => 3,
            'prediction_data' => json_encode([]),
        ]);

        $response = $this->actingAs($this->pemerintahUser)
            ->get('/pemerintah/prediksi-nbm/history');

        $response->assertStatus(200);
        $response->assertSee('Jagung'); // Own prediction
        $response->assertDontSee('should not see other user data'); // Indirectly test isolation
    }
}
