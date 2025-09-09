<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarAlamat;

class PublicDaftarAlamatController extends Controller
{
    /**
     * Extract jenis instansi from nama_dinas based on keywords
     */
    private function extractJenisInstansi($namaDinas)
    {
        $namaDinas = strtolower($namaDinas);
        
        // Define keyword mapping for jenis instansi
        $jenisMapping = [
            'pertanian' => 'Dinas Pertanian',
            'perkebunan' => 'Dinas Perkebunan', 
            'kehutanan' => 'Dinas Kehutanan',
            'tanaman pangan' => 'Dinas Tanaman Pangan',
            'perikanan' => 'Dinas Perikanan',
            'peternakan' => 'Dinas Peternakan',
            'ketahanan pangan' => 'Dinas Ketahanan Pangan',
            'hortikultura' => 'Dinas Hortikultura',
            'balai pengkajian teknologi pertanian' => 'Balai Pengkajian Teknologi Pertanian',
            'bptp' => 'Balai Pengkajian Teknologi Pertanian'
        ];
        
        $detectedJenis = [];
        
        foreach ($jenisMapping as $keyword => $jenis) {
            if (strpos($namaDinas, $keyword) !== false) {
                $detectedJenis[] = $jenis;
            }
        }
        
        // Return unique jenis, if multiple found return the first one, if none found return default
        if (!empty($detectedJenis)) {
            return array_unique($detectedJenis);
        }
        
        return ['Dinas Pertanian']; // Default fallback
    }

    public function getData()
    {
        // Get actual data from database - filter for active records with coordinates
        $data = DaftarAlamat::active()
            ->withCoordinates()
            ->select([
                'id',
                'nama_dinas as nama',
                'alamat',
                'provinsi',
                'kabupaten_kota',
                'latitude as lat',
                'longitude as lng',
                'gambar',
                'telp',
                'email',
                'website'
            ])
            ->get()
            ->map(function ($item) {
                $jenisInstansi = $this->extractJenisInstansi($item->nama);
                
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'alamat' => $item->alamat,
                    'provinsi' => $item->provinsi,
                    'kabupaten_kota' => $item->kabupaten_kota,
                    'wilayah' => $item->kabupaten_kota . ', ' . $item->provinsi, // Keep for backward compatibility
                    'jenis' => implode(', ', $jenisInstansi), // Join multiple jenis with comma
                    'jenis_instansi' => $jenisInstansi, // Array of jenis instansi
                    'lat' => (float) $item->lat,
                    'lng' => (float) $item->lng,
                    'gambar' => $item->gambar_url, // This will use the getGambarUrlAttribute accessor
                    'telp' => $item->telp,
                    'email' => $item->email,
                    'website' => $item->website
                ];
            })
            ->toArray();

        return response()->json($data);
    }
}
