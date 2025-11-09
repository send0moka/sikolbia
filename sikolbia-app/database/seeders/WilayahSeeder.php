<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            
            // 1. Seed wilayah_kategori
            $this->seedWilayahKategori();
            
            // 2. Seed all provinces (id_kategori: 1)
            $provinces = $this->getProvincesData();
            DB::table('wilayah')->insert($provinces);
            
            // 3. Seed all regencies/cities (id_kategori: 2 for regencies, 3 for cities)
            $regencies = $this->getRegenciesData();
            
            // Insert in chunks to handle large dataset
            foreach (array_chunk($regencies, 100) as $chunk) {
                DB::table('wilayah')->insert($chunk);
            }
            
            DB::commit();
            $this->command->info('Successfully seeded ' . (count($provinces) + count($regencies)) . ' wilayah records');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Failed to seed wilayah data: ' . $e->getMessage());
            Log::error('Wilayah Seeder Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function seedWilayahKategori(): void
    {
        $categories = [
            ['id' => 1, 'deskripsi' => 'Provinsi'],
            ['id' => 2, 'deskripsi' => 'Kabupaten'],
            ['id' => 3, 'deskripsi' => 'Kota'],
            ['id' => 4, 'deskripsi' => 'Kecamatan'],
            ['id' => 5, 'deskripsi' => 'Kelurahan/Desa'],
            ['id' => 6, 'deskripsi' => 'Stasiun'],
        ];
        
        foreach ($categories as $category) {
            DB::table('wilayah_kategori')->updateOrInsert(
                ['id' => $category['id']],
                $category
            );
        }
    }
    
    private function getProvincesData(): array
    {
        return [
            ['id' => 1, 'kode' => '11', 'nama' => 'Aceh', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 1],
            ['id' => 2, 'kode' => '12', 'nama' => 'Sumatera Utara', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 2],
            ['id' => 3, 'kode' => '13', 'nama' => 'Sumatera Barat', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 3],
            ['id' => 4, 'kode' => '14', 'nama' => 'Riau', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 4],
            ['id' => 5, 'kode' => '15', 'nama' => 'Jambi', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 5],
            ['id' => 6, 'kode' => '16', 'nama' => 'Sumatera Selatan', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 6],
            ['id' => 7, 'kode' => '17', 'nama' => 'Bengkulu', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 7],
            ['id' => 8, 'kode' => '18', 'nama' => 'Lampung', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 8],
            ['id' => 9, 'kode' => '19', 'nama' => 'Kepulauan Bangka Belitung', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 9],
            ['id' => 10, 'kode' => '21', 'nama' => 'Kepulauan Riau', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 10],
            ['id' => 11, 'kode' => '31', 'nama' => 'DKI Jakarta', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 11],
            ['id' => 12, 'kode' => '32', 'nama' => 'Jawa Barat', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 12],
            ['id' => 13, 'kode' => '33', 'nama' => 'Jawa Tengah', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 13],
            ['id' => 14, 'kode' => '34', 'nama' => 'DI Yogyakarta', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 14],
            ['id' => 15, 'kode' => '35', 'nama' => 'Jawa Timur', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 15],
            ['id' => 16, 'kode' => '36', 'nama' => 'Banten', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 16],
            ['id' => 17, 'kode' => '51', 'nama' => 'Bali', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 17],
            ['id' => 18, 'kode' => '52', 'nama' => 'Nusa Tenggara Barat', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 18],
            ['id' => 19, 'kode' => '53', 'nama' => 'Nusa Tenggara Timur', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 19],
            ['id' => 20, 'kode' => '61', 'nama' => 'Kalimantan Barat', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 20],
            ['id' => 21, 'kode' => '62', 'nama' => 'Kalimantan Tengah', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 21],
            ['id' => 22, 'kode' => '63', 'nama' => 'Kalimantan Selatan', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 22],
            ['id' => 23, 'kode' => '64', 'nama' => 'Kalimantan Timur', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 23],
            ['id' => 24, 'kode' => '65', 'nama' => 'Kalimantan Utara', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 24],
            ['id' => 25, 'kode' => '71', 'nama' => 'Sulawesi Utara', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 25],
            ['id' => 26, 'kode' => '72', 'nama' => 'Sulawesi Tengah', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 26],
            ['id' => 27, 'kode' => '73', 'nama' => 'Sulawesi Selatan', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 27],
            ['id' => 28, 'kode' => '74', 'nama' => 'Sulawesi Tenggara', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 28],
            ['id' => 29, 'kode' => '75', 'nama' => 'Gorontalo', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 29],
            ['id' => 30, 'kode' => '76', 'nama' => 'Sulawesi Barat', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 30],
            ['id' => 31, 'kode' => '81', 'nama' => 'Maluku', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 31],
            ['id' => 32, 'kode' => '82', 'nama' => 'Maluku Utara', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 32],
            ['id' => 33, 'kode' => '91', 'nama' => 'Papua Barat', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 33],
            ['id' => 34, 'kode' => '92', 'nama' => 'Papua', 'id_kategori' => 1, 'id_parent' => null, 'sorter' => 34],
        ];
    }
    
    private function getRegenciesData(): array
    {
        $regencies = [];
        $id = 35; // Start ID after provinces
        
        // Helper function to add regencies/cities
        $addRegions = function($regionsData, $provinceId) use (&$regencies, &$id) {
            $sorter = 35;
            foreach ($regionsData as $region) {
                $regencies[] = [
                    'id' => $id++,
                    'kode' => $region['kode'],
                    'nama' => $region['nama'],
                    'id_kategori' => $region['id_kategori'],
                    'id_parent' => $provinceId,
                    'sorter' => $sorter++,
                ];
            }
        };
        
        // 1. Aceh (ID:1) - 23 regencies/cities
        $addRegions([
            ['kode' => '1101', 'nama' => 'Kab. Simeulue', 'id_kategori' => 2],
            ['kode' => '1102', 'nama' => 'Kab. Aceh Singkil', 'id_kategori' => 2],
            ['kode' => '1103', 'nama' => 'Kab. Aceh Selatan', 'id_kategori' => 2],
            ['kode' => '1104', 'nama' => 'Kab. Aceh Tenggara', 'id_kategori' => 2],
            ['kode' => '1105', 'nama' => 'Kab. Aceh Timur', 'id_kategori' => 2],
            ['kode' => '1106', 'nama' => 'Kab. Aceh Tengah', 'id_kategori' => 2],
            ['kode' => '1107', 'nama' => 'Kab. Aceh Barat', 'id_kategori' => 2],
            ['kode' => '1108', 'nama' => 'Kab. Aceh Besar', 'id_kategori' => 2],
            ['kode' => '1109', 'nama' => 'Kab. Pidie', 'id_kategori' => 2],
            ['kode' => '1110', 'nama' => 'Kab. Bireuen', 'id_kategori' => 2],
            ['kode' => '1111', 'nama' => 'Kab. Aceh Utara', 'id_kategori' => 2],
            ['kode' => '1112', 'nama' => 'Kab. Aceh Barat Daya', 'id_kategori' => 2],
            ['kode' => '1113', 'nama' => 'Kab. Gayo Lues', 'id_kategori' => 2],
            ['kode' => '1114', 'nama' => 'Kab. Aceh Tamiang', 'id_kategori' => 2],
            ['kode' => '1115', 'nama' => 'Kab. Nagan Raya', 'id_kategori' => 2],
            ['kode' => '1116', 'nama' => 'Kab. Aceh Jaya', 'id_kategori' => 2],
            ['kode' => '1117', 'nama' => 'Kab. Bener Meriah', 'id_kategori' => 2],
            ['kode' => '1118', 'nama' => 'Kab. Pidie Jaya', 'id_kategori' => 2],
            ['kode' => '1171', 'nama' => 'Kota Banda Aceh', 'id_kategori' => 3],
            ['kode' => '1172', 'nama' => 'Kota Sabang', 'id_kategori' => 3],
            ['kode' => '1173', 'nama' => 'Kota Langsa', 'id_kategori' => 3],
            ['kode' => '1174', 'nama' => 'Kota Lhokseumawe', 'id_kategori' => 3],
            ['kode' => '1175', 'nama' => 'Kota Subulussalam', 'id_kategori' => 3],
        ], 1);
        
        // 2. Sumatera Utara (ID:2) - 33 regencies/cities
        $addRegions([
            ['kode' => '1201', 'nama' => 'Kab. Nias', 'id_kategori' => 2],
            ['kode' => '1202', 'nama' => 'Kab. Mandailing Natal', 'id_kategori' => 2],
            ['kode' => '1203', 'nama' => 'Kab. Tapanuli Selatan', 'id_kategori' => 2],
            ['kode' => '1204', 'nama' => 'Kab. Tapanuli Tengah', 'id_kategori' => 2],
            ['kode' => '1205', 'nama' => 'Kab. Tapanuli Utara', 'id_kategori' => 2],
            ['kode' => '1206', 'nama' => 'Kab. Toba Samosir', 'id_kategori' => 2],
            ['kode' => '1207', 'nama' => 'Kab. Labuhanbatu', 'id_kategori' => 2],
            ['kode' => '1208', 'nama' => 'Kab. Asahan', 'id_kategori' => 2],
            ['kode' => '1209', 'nama' => 'Kab. Simalungun', 'id_kategori' => 2],
            ['kode' => '1210', 'nama' => 'Kab. Dairi', 'id_kategori' => 2],
            ['kode' => '1211', 'nama' => 'Kab. Karo', 'id_kategori' => 2],
            ['kode' => '1212', 'nama' => 'Kab. Deli Serdang', 'id_kategori' => 2],
            ['kode' => '1213', 'nama' => 'Kab. Langkat', 'id_kategori' => 2],
            ['kode' => '1214', 'nama' => 'Kab. Nias Selatan', 'id_kategori' => 2],
            ['kode' => '1215', 'nama' => 'Kab. Humbang Hasundutan', 'id_kategori' => 2],
            ['kode' => '1216', 'nama' => 'Kab. Pakpak Bharat', 'id_kategori' => 2],
            ['kode' => '1217', 'nama' => 'Kab. Samosir', 'id_kategori' => 2],
            ['kode' => '1218', 'nama' => 'Kab. Serdang Bedagai', 'id_kategori' => 2],
            ['kode' => '1219', 'nama' => 'Kab. Batu Bara', 'id_kategori' => 2],
            ['kode' => '1220', 'nama' => 'Kab. Padang Lawas Utara', 'id_kategori' => 2],
            ['kode' => '1221', 'nama' => 'Kab. Padang Lawas', 'id_kategori' => 2],
            ['kode' => '1222', 'nama' => 'Kab. Labuhanbatu Selatan', 'id_kategori' => 2],
            ['kode' => '1223', 'nama' => 'Kab. Labuhanbatu Utara', 'id_kategori' => 2],
            ['kode' => '1224', 'nama' => 'Kab. Nias Utara', 'id_kategori' => 2],
            ['kode' => '1225', 'nama' => 'Kab. Nias Barat', 'id_kategori' => 2],
            ['kode' => '1271', 'nama' => 'Kota Sibolga', 'id_kategori' => 3],
            ['kode' => '1272', 'nama' => 'Kota Tanjung Balai', 'id_kategori' => 3],
            ['kode' => '1273', 'nama' => 'Kota Pematang Siantar', 'id_kategori' => 3],
            ['kode' => '1274', 'nama' => 'Kota Tebing Tinggi', 'id_kategori' => 3],
            ['kode' => '1275', 'nama' => 'Kota Medan', 'id_kategori' => 3],
            ['kode' => '1276', 'nama' => 'Kota Binjai', 'id_kategori' => 3],
            ['kode' => '1277', 'nama' => 'Kota Padangsidimpuan', 'id_kategori' => 3],
            ['kode' => '1278', 'nama' => 'Kota Gunungsitoli', 'id_kategori' => 3],
        ], 2);
        
        // 3. Sumatera Barat (ID:3) - 19 regencies/cities
        $addRegions([
            ['kode' => '1301', 'nama' => 'Kab. Kepulauan Mentawai', 'id_kategori' => 2],
            ['kode' => '1302', 'nama' => 'Kab. Pesisir Selatan', 'id_kategori' => 2],
            ['kode' => '1303', 'nama' => 'Kab. Solok', 'id_kategori' => 2],
            ['kode' => '1304', 'nama' => 'Kab. Sijunjung', 'id_kategori' => 2],
            ['kode' => '1305', 'nama' => 'Kab. Tanah Datar', 'id_kategori' => 2],
            ['kode' => '1306', 'nama' => 'Kab. Padang Pariaman', 'id_kategori' => 2],
            ['kode' => '1307', 'nama' => 'Kab. Agam', 'id_kategori' => 2],
            ['kode' => '1308', 'nama' => 'Kab. Lima Puluh Kota', 'id_kategori' => 2],
            ['kode' => '1309', 'nama' => 'Kab. Pasaman', 'id_kategori' => 2],
            ['kode' => '1310', 'nama' => 'Kab. Solok Selatan', 'id_kategori' => 2],
            ['kode' => '1311', 'nama' => 'Kab. Dharmasraya', 'id_kategori' => 2],
            ['kode' => '1312', 'nama' => 'Kab. Pasaman Barat', 'id_kategori' => 2],
            ['kode' => '1371', 'nama' => 'Kota Padang', 'id_kategori' => 3],
            ['kode' => '1372', 'nama' => 'Kota Solok', 'id_kategori' => 3],
            ['kode' => '1373', 'nama' => 'Kota Sawah Lunto', 'id_kategori' => 3],
            ['kode' => '1374', 'nama' => 'Kota Padang Panjang', 'id_kategori' => 3],
            ['kode' => '1375', 'nama' => 'Kota Bukittinggi', 'id_kategori' => 3],
            ['kode' => '1376', 'nama' => 'Kota Payakumbuh', 'id_kategori' => 3],
            ['kode' => '1377', 'nama' => 'Kota Pariaman', 'id_kategori' => 3],
        ], 3);
        
        // 4. Riau (ID:4) - 12 regencies/cities
        $addRegions([
            ['kode' => '1401', 'nama' => 'Kab. Kuantan Singingi', 'id_kategori' => 2],
            ['kode' => '1402', 'nama' => 'Kab. Indragiri Hulu', 'id_kategori' => 2],
            ['kode' => '1403', 'nama' => 'Kab. Indragiri Hilir', 'id_kategori' => 2],
            ['kode' => '1404', 'nama' => 'Kab. Pelalawan', 'id_kategori' => 2],
            ['kode' => '1405', 'nama' => 'Kab. Siak', 'id_kategori' => 2],
            ['kode' => '1406', 'nama' => 'Kab. Kampar', 'id_kategori' => 2],
            ['kode' => '1407', 'nama' => 'Kab. Rokan Hulu', 'id_kategori' => 2],
            ['kode' => '1408', 'nama' => 'Kab. Bengkalis', 'id_kategori' => 2],
            ['kode' => '1409', 'nama' => 'Kab. Rokan Hilir', 'id_kategori' => 2],
            ['kode' => '1410', 'nama' => 'Kab. Kepulauan Meranti', 'id_kategori' => 2],
            ['kode' => '1471', 'nama' => 'Kota Pekanbaru', 'id_kategori' => 3],
            ['kode' => '1472', 'nama' => 'Kota Dumai', 'id_kategori' => 3],
        ], 4);
        
        // 5. Jambi (ID:5) - 11 regencies/cities
        $addRegions([
            ['kode' => '1501', 'nama' => 'Kab. Kerinci', 'id_kategori' => 2],
            ['kode' => '1502', 'nama' => 'Kab. Merangin', 'id_kategori' => 2],
            ['kode' => '1503', 'nama' => 'Kab. Sarolangun', 'id_kategori' => 2],
            ['kode' => '1504', 'nama' => 'Kab. Batang Hari', 'id_kategori' => 2],
            ['kode' => '1505', 'nama' => 'Kab. Muaro Jambi', 'id_kategori' => 2],
            ['kode' => '1506', 'nama' => 'Kab. Tanjung Jabung Timur', 'id_kategori' => 2],
            ['kode' => '1507', 'nama' => 'Kab. Tanjung Jabung Barat', 'id_kategori' => 2],
            ['kode' => '1508', 'nama' => 'Kab. Tebo', 'id_kategori' => 2],
            ['kode' => '1509', 'nama' => 'Kab. Bungo', 'id_kategori' => 2],
            ['kode' => '1571', 'nama' => 'Kota Jambi', 'id_kategori' => 3],
            ['kode' => '1572', 'nama' => 'Kota Sungai Penuh', 'id_kategori' => 3],
        ], 5);

        // 6. Sumatera Selatan (ID: 6)
        $addRegions([
            ['kode' => '1601', 'nama' => 'Kab. Ogan Komering Ulu', 'id_kategori' => 2],
            ['kode' => '1602', 'nama' => 'Kab. Ogan Komering Ilir', 'id_kategori' => 2],
            ['kode' => '1603', 'nama' => 'Kab. Muara Enim', 'id_kategori' => 2],
            ['kode' => '1604', 'nama' => 'Kab. Lahat', 'id_kategori' => 2],
            ['kode' => '1605', 'nama' => 'Kab. Musi Rawas', 'id_kategori' => 2],
            ['kode' => '1606', 'nama' => 'Kab. Musi Banyuasin', 'id_kategori' => 2],
            ['kode' => '1607', 'nama' => 'Kab. Banyu Asin', 'id_kategori' => 2],
            ['kode' => '1608', 'nama' => 'Kab. Ogan Komering Ulu Selatan', 'id_kategori' => 2],
            ['kode' => '1609', 'nama' => 'Kab. Ogan Komering Ulu Timur', 'id_kategori' => 2],
            ['kode' => '1610', 'nama' => 'Kab. Ogan Ilir', 'id_kategori' => 2],
            ['kode' => '1611', 'nama' => 'Kab. Empat Lawang', 'id_kategori' => 2],
            ['kode' => '1612', 'nama' => 'Kab. Penukal Abab Lematang Ilir', 'id_kategori' => 2],
            ['kode' => '1613', 'nama' => 'Kab. Musi Rawas Utara', 'id_kategori' => 2],
            ['kode' => '1671', 'nama' => 'Kota Palembang', 'id_kategori' => 3],
            ['kode' => '1672', 'nama' => 'Kota Prabumulih', 'id_kategori' => 3],
            ['kode' => '1673', 'nama' => 'Kota Pagar Alam', 'id_kategori' => 3],
            ['kode' => '1674', 'nama' => 'Kota Lubuklinggau', 'id_kategori' => 3],
        ], 6);

        // 7. Bengkulu (ID: 7)
        $addRegions([
            ['kode' => '1701', 'nama' => 'Kab. Bengkulu Selatan', 'id_kategori' => 2],
            ['kode' => '1702', 'nama' => 'Kab. Rejang Lebong', 'id_kategori' => 2],
            ['kode' => '1703', 'nama' => 'Kab. Bengkulu Utara', 'id_kategori' => 2],
            ['kode' => '1704', 'nama' => 'Kab. Kaur', 'id_kategori' => 2],
            ['kode' => '1705', 'nama' => 'Kab. Seluma', 'id_kategori' => 2],
            ['kode' => '1706', 'nama' => 'Kab. Mukomuko', 'id_kategori' => 2],
            ['kode' => '1707', 'nama' => 'Kab. Lebong', 'id_kategori' => 2],
            ['kode' => '1708', 'nama' => 'Kab. Kepahiang', 'id_kategori' => 2],
            ['kode' => '1709', 'nama' => 'Kab. Bengkulu Tengah', 'id_kategori' => 2],
            ['kode' => '1771', 'nama' => 'Kota Bengkulu', 'id_kategori' => 3],
        ], 7);

        // 8. Lampung (ID: 8)
        $addRegions([
            ['kode' => '1801', 'nama' => 'Kab. Lampung Barat', 'id_kategori' => 2],
            ['kode' => '1802', 'nama' => 'Kab. Tanggamus', 'id_kategori' => 2],
            ['kode' => '1803', 'nama' => 'Kab. Lampung Selatan', 'id_kategori' => 2],
            ['kode' => '1804', 'nama' => 'Kab. Lampung Timur', 'id_kategori' => 2],
            ['kode' => '1805', 'nama' => 'Kab. Lampung Tengah', 'id_kategori' => 2],
            ['kode' => '1806', 'nama' => 'Kab. Lampung Utara', 'id_kategori' => 2],
            ['kode' => '1807', 'nama' => 'Kab. Way Kanan', 'id_kategori' => 2],
            ['kode' => '1808', 'nama' => 'Kab. Tulangbawang', 'id_kategori' => 2],
            ['kode' => '1809', 'nama' => 'Kab. Pesawaran', 'id_kategori' => 2],
            ['kode' => '1810', 'nama' => 'Kab. Pringsewu', 'id_kategori' => 2],
            ['kode' => '1811', 'nama' => 'Kab. Mesuji', 'id_kategori' => 2],
            ['kode' => '1812', 'nama' => 'Kab. Tulang Bawang Barat', 'id_kategori' => 2],
            ['kode' => '1813', 'nama' => 'Kab. Pesisir Barat', 'id_kategori' => 2],
            ['kode' => '1871', 'nama' => 'Kota Bandar Lampung', 'id_kategori' => 3],
            ['kode' => '1872', 'nama' => 'Kota Metro', 'id_kategori' => 3],
        ], 8);

        // 9. Kepulauan Bangka Belitung (ID: 9)
        $addRegions([
            ['kode' => '1901', 'nama' => 'Kab. Bangka', 'id_kategori' => 2],
            ['kode' => '1902', 'nama' => 'Kab. Belitung', 'id_kategori' => 2],
            ['kode' => '1903', 'nama' => 'Kab. Bangka Barat', 'id_kategori' => 2],
            ['kode' => '1904', 'nama' => 'Kab. Bangka Tengah', 'id_kategori' => 2],
            ['kode' => '1905', 'nama' => 'Kab. Bangka Selatan', 'id_kategori' => 2],
            ['kode' => '1906', 'nama' => 'Kab. Belitung Timur', 'id_kategori' => 2],
            ['kode' => '1971', 'nama' => 'Kota Pangkal Pinang', 'id_kategori' => 3],
        ], 9);

        // 10. Kepulauan Riau (ID: 10)
        $addRegions([
            ['kode' => '2101', 'nama' => 'Kab. Karimun', 'id_kategori' => 2],
            ['kode' => '2102', 'nama' => 'Kab. Bintan', 'id_kategori' => 2],
            ['kode' => '2103', 'nama' => 'Kab. Natuna', 'id_kategori' => 2],
            ['kode' => '2104', 'nama' => 'Kab. Lingga', 'id_kategori' => 2],
            ['kode' => '2105', 'nama' => 'Kab. Kepulauan Anambas', 'id_kategori' => 2],
            ['kode' => '2171', 'nama' => 'Kota Batam', 'id_kategori' => 3],
            ['kode' => '2172', 'nama' => 'Kota Tanjung Pinang', 'id_kategori' => 3],
        ], 10);

        // 11. DKI Jakarta (ID: 11)
        $addRegions([
            ['kode' => '3101', 'nama' => 'Kab. Kepulauan Seribu', 'id_kategori' => 2],
            ['kode' => '3171', 'nama' => 'Kota Jakarta Selatan', 'id_kategori' => 3],
            ['kode' => '3172', 'nama' => 'Kota Jakarta Timur', 'id_kategori' => 3],
            ['kode' => '3173', 'nama' => 'Kota Jakarta Pusat', 'id_kategori' => 3],
            ['kode' => '3174', 'nama' => 'Kota Jakarta Barat', 'id_kategori' => 3],
            ['kode' => '3175', 'nama' => 'Kota Jakarta Utara', 'id_kategori' => 3],
        ], 11);

        // 12. Jawa Barat (ID: 12)
        $addRegions([
            ['kode' => '3201', 'nama' => 'Kab. Bogor', 'id_kategori' => 2],
            ['kode' => '3202', 'nama' => 'Kab. Sukabumi', 'id_kategori' => 2],
            ['kode' => '3203', 'nama' => 'Kab. Cianjur', 'id_kategori' => 2],
            ['kode' => '3204', 'nama' => 'Kab. Bandung', 'id_kategori' => 2],
            ['kode' => '3205', 'nama' => 'Kab. Garut', 'id_kategori' => 2],
            ['kode' => '3206', 'nama' => 'Kab. Tasikmalaya', 'id_kategori' => 2],
            ['kode' => '3207', 'nama' => 'Kab. Ciamis', 'id_kategori' => 2],
            ['kode' => '3208', 'nama' => 'Kab. Kuningan', 'id_kategori' => 2],
            ['kode' => '3209', 'nama' => 'Kab. Cirebon', 'id_kategori' => 2],
            ['kode' => '3210', 'nama' => 'Kab. Majalengka', 'id_kategori' => 2],
            ['kode' => '3211', 'nama' => 'Kab. Sumedang', 'id_kategori' => 2],
            ['kode' => '3212', 'nama' => 'Kab. Indramayu', 'id_kategori' => 2],
            ['kode' => '3213', 'nama' => 'Kab. Subang', 'id_kategori' => 2],
            ['kode' => '3214', 'nama' => 'Kab. Purwakarta', 'id_kategori' => 2],
            ['kode' => '3215', 'nama' => 'Kab. Karawang', 'id_kategori' => 2],
            ['kode' => '3216', 'nama' => 'Kab. Bekasi', 'id_kategori' => 2],
            ['kode' => '3217', 'nama' => 'Kab. Bandung Barat', 'id_kategori' => 2],
            ['kode' => '3218', 'nama' => 'Kab. Pangandaran', 'id_kategori' => 2],
            ['kode' => '3271', 'nama' => 'Kota Bogor', 'id_kategori' => 3],
            ['kode' => '3272', 'nama' => 'Kota Sukabumi', 'id_kategori' => 3],
            ['kode' => '3273', 'nama' => 'Kota Bandung', 'id_kategori' => 3],
            ['kode' => '3274', 'nama' => 'Kota Cirebon', 'id_kategori' => 3],
            ['kode' => '3275', 'nama' => 'Kota Bekasi', 'id_kategori' => 3],
            ['kode' => '3276', 'nama' => 'Kota Depok', 'id_kategori' => 3],
            ['kode' => '3277', 'nama' => 'Kota Cimahi', 'id_kategori' => 3],
            ['kode' => '3278', 'nama' => 'Kota Tasikmalaya', 'id_kategori' => 3],
            ['kode' => '3279', 'nama' => 'Kota Banjar', 'id_kategori' => 3],
        ], 12);

        // 13. Jawa Tengah (ID: 13)
        $addRegions([
            ['kode' => '3301', 'nama' => 'Kab. Cilacap', 'id_kategori' => 2],
            ['kode' => '3302', 'nama' => 'Kab. Banyumas', 'id_kategori' => 2],
            ['kode' => '3303', 'nama' => 'Kab. Purbalingga', 'id_kategori' => 2],
            ['kode' => '3304', 'nama' => 'Kab. Banjarnegara', 'id_kategori' => 2],
            ['kode' => '3305', 'nama' => 'Kab. Kebumen', 'id_kategori' => 2],
            ['kode' => '3306', 'nama' => 'Kab. Purworejo', 'id_kategori' => 2],
            ['kode' => '3307', 'nama' => 'Kab. Wonosobo', 'id_kategori' => 2],
            ['kode' => '3308', 'nama' => 'Kab. Magelang', 'id_kategori' => 2],
            ['kode' => '3309', 'nama' => 'Kab. Boyolali', 'id_kategori' => 2],
            ['kode' => '3310', 'nama' => 'Kab. Klaten', 'id_kategori' => 2],
            ['kode' => '3311', 'nama' => 'Kab. Sukoharjo', 'id_kategori' => 2],
            ['kode' => '3312', 'nama' => 'Kab. Wonogiri', 'id_kategori' => 2],
            ['kode' => '3313', 'nama' => 'Kab. Karanganyar', 'id_kategori' => 2],
            ['kode' => '3314', 'nama' => 'Kab. Sragen', 'id_kategori' => 2],
            ['kode' => '3315', 'nama' => 'Kab. Grobogan', 'id_kategori' => 2],
            ['kode' => '3316', 'nama' => 'Kab. Blora', 'id_kategori' => 2],
            ['kode' => '3317', 'nama' => 'Kab. Rembang', 'id_kategori' => 2],
            ['kode' => '3318', 'nama' => 'Kab. Pati', 'id_kategori' => 2],
            ['kode' => '3319', 'nama' => 'Kab. Kudus', 'id_kategori' => 2],
            ['kode' => '3320', 'nama' => 'Kab. Jepara', 'id_kategori' => 2],
            ['kode' => '3321', 'nama' => 'Kab. Demak', 'id_kategori' => 2],
            ['kode' => '3322', 'nama' => 'Kab. Semarang', 'id_kategori' => 2],
            ['kode' => '3323', 'nama' => 'Kab. Temanggung', 'id_kategori' => 2],
            ['kode' => '3324', 'nama' => 'Kab. Kendal', 'id_kategori' => 2],
            ['kode' => '3325', 'nama' => 'Kab. Batang', 'id_kategori' => 2],
            ['kode' => '3326', 'nama' => 'Kab. Pekalongan', 'id_kategori' => 2],
            ['kode' => '3327', 'nama' => 'Kab. Pemalang', 'id_kategori' => 2],
            ['kode' => '3328', 'nama' => 'Kab. Tegal', 'id_kategori' => 2],
            ['kode' => '3329', 'nama' => 'Kab. Brebes', 'id_kategori' => 2],
            ['kode' => '3371', 'nama' => 'Kota Magelang', 'id_kategori' => 3],
            ['kode' => '3372', 'nama' => 'Kota Surakarta', 'id_kategori' => 3],
            ['kode' => '3373', 'nama' => 'Kota Salatiga', 'id_kategori' => 3],
            ['kode' => '3374', 'nama' => 'Kota Semarang', 'id_kategori' => 3],
            ['kode' => '3375', 'nama' => 'Kota Pekalongan', 'id_kategori' => 3],
            ['kode' => '3376', 'nama' => 'Kota Tegal', 'id_kategori' => 3],
        ], 13);

        // 14. DI Yogyakarta (ID: 14)
        $addRegions([
            ['kode' => '3401', 'nama' => 'Kab. Kulon Progo', 'id_kategori' => 2],
            ['kode' => '3402', 'nama' => 'Kab. Bantul', 'id_kategori' => 2],
            ['kode' => '3403', 'nama' => 'Kab. Gunung Kidul', 'id_kategori' => 2],
            ['kode' => '3404', 'nama' => 'Kab. Sleman', 'id_kategori' => 2],
            ['kode' => '3471', 'nama' => 'Kota Yogyakarta', 'id_kategori' => 3],
        ], 14);

        // 15. Jawa Timur (ID: 15)
        $addRegions([
            ['kode' => '3501', 'nama' => 'Kab. Pacitan', 'id_kategori' => 2],
            ['kode' => '3502', 'nama' => 'Kab. Ponorogo', 'id_kategori' => 2],
            ['kode' => '3503', 'nama' => 'Kab. Trenggalek', 'id_kategori' => 2],
            ['kode' => '3504', 'nama' => 'Kab. Tulungagung', 'id_kategori' => 2],
            ['kode' => '3505', 'nama' => 'Kab. Blitar', 'id_kategori' => 2],
            ['kode' => '3506', 'nama' => 'Kab. Kediri', 'id_kategori' => 2],
            ['kode' => '3507', 'nama' => 'Kab. Malang', 'id_kategori' => 2],
            ['kode' => '3508', 'nama' => 'Kab. Lumajang', 'id_kategori' => 2],
            ['kode' => '3509', 'nama' => 'Kab. Jember', 'id_kategori' => 2],
            ['kode' => '3510', 'nama' => 'Kab. Banyuwangi', 'id_kategori' => 2],
            ['kode' => '3511', 'nama' => 'Kab. Bondowoso', 'id_kategori' => 2],
            ['kode' => '3512', 'nama' => 'Kab. Situbondo', 'id_kategori' => 2],
            ['kode' => '3513', 'nama' => 'Kab. Probolinggo', 'id_kategori' => 2],
            ['kode' => '3514', 'nama' => 'Kab. Pasuruan', 'id_kategori' => 2],
            ['kode' => '3515', 'nama' => 'Kab. Sidoarjo', 'id_kategori' => 2],
            ['kode' => '3516', 'nama' => 'Kab. Mojokerto', 'id_kategori' => 2],
            ['kode' => '3517', 'nama' => 'Kab. Jombang', 'id_kategori' => 2],
            ['kode' => '3518', 'nama' => 'Kab. Nganjuk', 'id_kategori' => 2],
            ['kode' => '3519', 'nama' => 'Kab. Madiun', 'id_kategori' => 2],
            ['kode' => '3520', 'nama' => 'Kab. Magetan', 'id_kategori' => 2],
            ['kode' => '3521', 'nama' => 'Kab. Ngawi', 'id_kategori' => 2],
            ['kode' => '3522', 'nama' => 'Kab. Bojonegoro', 'id_kategori' => 2],
            ['kode' => '3523', 'nama' => 'Kab. Tuban', 'id_kategori' => 2],
            ['kode' => '3524', 'nama' => 'Kab. Lamongan', 'id_kategori' => 2],
            ['kode' => '3525', 'nama' => 'Kab. Gresik', 'id_kategori' => 2],
            ['kode' => '3526', 'nama' => 'Kab. Bangkalan', 'id_kategori' => 2],
            ['kode' => '3527', 'nama' => 'Kab. Sampang', 'id_kategori' => 2],
            ['kode' => '3528', 'nama' => 'Kab. Pamekasan', 'id_kategori' => 2],
            ['kode' => '3529', 'nama' => 'Kab. Sumenep', 'id_kategori' => 2],
            ['kode' => '3571', 'nama' => 'Kota Kediri', 'id_kategori' => 3],
            ['kode' => '3572', 'nama' => 'Kota Blitar', 'id_kategori' => 3],
            ['kode' => '3573', 'nama' => 'Kota Malang', 'id_kategori' => 3],
            ['kode' => '3574', 'nama' => 'Kota Probolinggo', 'id_kategori' => 3],
            ['kode' => '3575', 'nama' => 'Kota Pasuruan', 'id_kategori' => 3],
            ['kode' => '3576', 'nama' => 'Kota Mojokerto', 'id_kategori' => 3],
            ['kode' => '3577', 'nama' => 'Kota Madiun', 'id_kategori' => 3],
            ['kode' => '3578', 'nama' => 'Kota Surabaya', 'id_kategori' => 3],
            ['kode' => '3579', 'nama' => 'Kota Batu', 'id_kategori' => 3],
        ], 15);

        // 16. Banten (ID: 16)
        $addRegions([
            ['kode' => '3601', 'nama' => 'Kab. Pandeglang', 'id_kategori' => 2],
            ['kode' => '3602', 'nama' => 'Kab. Lebak', 'id_kategori' => 2],
            ['kode' => '3603', 'nama' => 'Kab. Tangerang', 'id_kategori' => 2],
            ['kode' => '3604', 'nama' => 'Kab. Serang', 'id_kategori' => 2],
            ['kode' => '3671', 'nama' => 'Kota Tangerang', 'id_kategori' => 3],
            ['kode' => '3672', 'nama' => 'Kota Cilegon', 'id_kategori' => 3],
            ['kode' => '3673', 'nama' => 'Kota Serang', 'id_kategori' => 3],
            ['kode' => '3674', 'nama' => 'Kota Tangerang Selatan', 'id_kategori' => 3],
        ], 16);

        // 17. Bali (ID: 17)
        $addRegions([
            ['kode' => '5101', 'nama' => 'Kab. Jembrana', 'id_kategori' => 2],
            ['kode' => '5102', 'nama' => 'Kab. Tabanan', 'id_kategori' => 2],
            ['kode' => '5103', 'nama' => 'Kab. Badung', 'id_kategori' => 2],
            ['kode' => '5104', 'nama' => 'Kab. Gianyar', 'id_kategori' => 2],
            ['kode' => '5105', 'nama' => 'Kab. Klungkung', 'id_kategori' => 2],
            ['kode' => '5106', 'nama' => 'Kab. Bangli', 'id_kategori' => 2],
            ['kode' => '5107', 'nama' => 'Kab. Karang Asem', 'id_kategori' => 2],
            ['kode' => '5108', 'nama' => 'Kab. Buleleng', 'id_kategori' => 2],
            ['kode' => '5171', 'nama' => 'Kota Denpasar', 'id_kategori' => 3],
        ], 17);

        // 18. Nusa Tenggara Barat (ID: 18)
        $addRegions([
            ['kode' => '5201', 'nama' => 'Kab. Lombok Barat', 'id_kategori' => 2],
            ['kode' => '5202', 'nama' => 'Kab. Lombok Tengah', 'id_kategori' => 2],
            ['kode' => '5203', 'nama' => 'Kab. Lombok Timur', 'id_kategori' => 2],
            ['kode' => '5204', 'nama' => 'Kab. Sumbawa', 'id_kategori' => 2],
            ['kode' => '5205', 'nama' => 'Kab. Dompu', 'id_kategori' => 2],
            ['kode' => '5206', 'nama' => 'Kab. Bima', 'id_kategori' => 2],
            ['kode' => '5207', 'nama' => 'Kab. Sumbawa Barat', 'id_kategori' => 2],
            ['kode' => '5208', 'nama' => 'Kab. Lombok Utara', 'id_kategori' => 2],
            ['kode' => '5271', 'nama' => 'Kota Mataram', 'id_kategori' => 3],
            ['kode' => '5272', 'nama' => 'Kota Bima', 'id_kategori' => 3],
        ], 18);

        // 19. Nusa Tenggara Timur (ID: 19)
        $addRegions([
            ['kode' => '5301', 'nama' => 'Kab. Sumba Barat', 'id_kategori' => 2],
            ['kode' => '5302', 'nama' => 'Kab. Sumba Timur', 'id_kategori' => 2],
            ['kode' => '5303', 'nama' => 'Kab. Kupang', 'id_kategori' => 2],
            ['kode' => '5304', 'nama' => 'Kab. Timor Tengah Selatan', 'id_kategori' => 2],
            ['kode' => '5305', 'nama' => 'Kab. Timor Tengah Utara', 'id_kategori' => 2],
            ['kode' => '5306', 'nama' => 'Kab. Belu', 'id_kategori' => 2],
            ['kode' => '5307', 'nama' => 'Kab. Alor', 'id_kategori' => 2],
            ['kode' => '5308', 'nama' => 'Kab. Lembata', 'id_kategori' => 2],
            ['kode' => '5309', 'nama' => 'Kab. Flores Timur', 'id_kategori' => 2],
            ['kode' => '5310', 'nama' => 'Kab. Sikka', 'id_kategori' => 2],
            ['kode' => '5311', 'nama' => 'Kab. Ende', 'id_kategori' => 2],
            ['kode' => '5312', 'nama' => 'Kab. Ngada', 'id_kategori' => 2],
            ['kode' => '5313', 'nama' => 'Kab. Manggarai', 'id_kategori' => 2],
            ['kode' => '5314', 'nama' => 'Kab. Rote Ndao', 'id_kategori' => 2],
            ['kode' => '5315', 'nama' => 'Kab. Manggarai Barat', 'id_kategori' => 2],
            ['kode' => '5316', 'nama' => 'Kab. Sumba Tengah', 'id_kategori' => 2],
            ['kode' => '5317', 'nama' => 'Kab. Sumba Barat Daya', 'id_kategori' => 2],
            ['kode' => '5318', 'nama' => 'Kab. Nagekeo', 'id_kategori' => 2],
            ['kode' => '5319', 'nama' => 'Kab. Manggarai Timur', 'id_kategori' => 2],
            ['kode' => '5320', 'nama' => 'Kab. Sabu Raijua', 'id_kategori' => 2],
            ['kode' => '5321', 'nama' => 'Kab. Malaka', 'id_kategori' => 2],
            ['kode' => '5371', 'nama' => 'Kota Kupang', 'id_kategori' => 3],
        ], 19);

        // 20. Kalimantan Barat (ID: 20)
        $addRegions([
            ['kode' => '6101', 'nama' => 'Kab. Sambas', 'id_kategori' => 2],
            ['kode' => '6102', 'nama' => 'Kab. Bengkayang', 'id_kategori' => 2],
            ['kode' => '6103', 'nama' => 'Kab. Landak', 'id_kategori' => 2],
            ['kode' => '6104', 'nama' => 'Kab. Mempawah', 'id_kategori' => 2],
            ['kode' => '6105', 'nama' => 'Kab. Sanggau', 'id_kategori' => 2],
            ['kode' => '6106', 'nama' => 'Kab. Ketapang', 'id_kategori' => 2],
            ['kode' => '6107', 'nama' => 'Kab. Sintang', 'id_kategori' => 2],
            ['kode' => '6108', 'nama' => 'Kab. Kapuas Hulu', 'id_kategori' => 2],
            ['kode' => '6109', 'nama' => 'Kab. Sekadau', 'id_kategori' => 2],
            ['kode' => '6110', 'nama' => 'Kab. Melawi', 'id_kategori' => 2],
            ['kode' => '6111', 'nama' => 'Kab. Kayong Utara', 'id_kategori' => 2],
            ['kode' => '6112', 'nama' => 'Kab. Kubu Raya', 'id_kategori' => 2],
            ['kode' => '6171', 'nama' => 'Kota Pontianak', 'id_kategori' => 3],
            ['kode' => '6172', 'nama' => 'Kota Singkawang', 'id_kategori' => 3],
        ], 20);

        // 21. Kalimantan Tengah (ID: 21)
        $addRegions([
            ['kode' => '6201', 'nama' => 'Kab. Kotawaringin Barat', 'id_kategori' => 2],
            ['kode' => '6202', 'nama' => 'Kab. Kotawaringin Timur', 'id_kategori' => 2],
            ['kode' => '6203', 'nama' => 'Kab. Kapuas', 'id_kategori' => 2],
            ['kode' => '6204', 'nama' => 'Kab. Barito Selatan', 'id_kategori' => 2],
            ['kode' => '6205', 'nama' => 'Kab. Barito Utara', 'id_kategori' => 2],
            ['kode' => '6206', 'nama' => 'Kab. Sukamara', 'id_kategori' => 2],
            ['kode' => '6207', 'nama' => 'Kab. Lamandau', 'id_kategori' => 2],
            ['kode' => '6208', 'nama' => 'Kab. Seruyan', 'id_kategori' => 2],
            ['kode' => '6209', 'nama' => 'Kab. Katingan', 'id_kategori' => 2],
            ['kode' => '6210', 'nama' => 'Kab. Pulang Pisau', 'id_kategori' => 2],
            ['kode' => '6211', 'nama' => 'Kab. Gunung Mas', 'id_kategori' => 2],
            ['kode' => '6212', 'nama' => 'Kab. Barito Timur', 'id_kategori' => 2],
            ['kode' => '6213', 'nama' => 'Kab. Murung Raya', 'id_kategori' => 2],
            ['kode' => '6271', 'nama' => 'Kota Palangka Raya', 'id_kategori' => 3],
        ], 21);

        // 22. Kalimantan Selatan (ID: 22)
        $addRegions([
            ['kode' => '6301', 'nama' => 'Kab. Tanah Laut', 'id_kategori' => 2],
            ['kode' => '6302', 'nama' => 'Kab. Kota Baru', 'id_kategori' => 2],
            ['kode' => '6303', 'nama' => 'Kab. Banjar', 'id_kategori' => 2],
            ['kode' => '6304', 'nama' => 'Kab. Barito Kuala', 'id_kategori' => 2],
            ['kode' => '6305', 'nama' => 'Kab. Tapin', 'id_kategori' => 2],
            ['kode' => '6306', 'nama' => 'Kab. Hulu Sungai Selatan', 'id_kategori' => 2],
            ['kode' => '6307', 'nama' => 'Kab. Hulu Sungai Tengah', 'id_kategori' => 2],
            ['kode' => '6308', 'nama' => 'Kab. Hulu Sungai Utara', 'id_kategori' => 2],
            ['kode' => '6309', 'nama' => 'Kab. Tabalong', 'id_kategori' => 2],
            ['kode' => '6310', 'nama' => 'Kab. Tanah Bumbu', 'id_kategori' => 2],
            ['kode' => '6311', 'nama' => 'Kab. Balangan', 'id_kategori' => 2],
            ['kode' => '6371', 'nama' => 'Kota Banjarmasin', 'id_kategori' => 3],
            ['kode' => '6372', 'nama' => 'Kota Banjar Baru', 'id_kategori' => 3],
        ], 22);

        // 23. Kalimantan Timur (ID: 23)
        $addRegions([
            ['kode' => '6401', 'nama' => 'Kab. Paser', 'id_kategori' => 2],
            ['kode' => '6402', 'nama' => 'Kab. Kutai Barat', 'id_kategori' => 2],
            ['kode' => '6403', 'nama' => 'Kab. Kutai Kartanegara', 'id_kategori' => 2],
            ['kode' => '6404', 'nama' => 'Kab. Kutai Timur', 'id_kategori' => 2],
            ['kode' => '6405', 'nama' => 'Kab. Berau', 'id_kategori' => 2],
            ['kode' => '6409', 'nama' => 'Kab. Penajam Paser Utara', 'id_kategori' => 2],
            ['kode' => '6411', 'nama' => 'Kab. Mahakam Hulu', 'id_kategori' => 2],
            ['kode' => '6471', 'nama' => 'Kota Balikpapan', 'id_kategori' => 3],
            ['kode' => '6472', 'nama' => 'Kota Samarinda', 'id_kategori' => 3],
            ['kode' => '6474', 'nama' => 'Kota Bontang', 'id_kategori' => 3],
        ], 23);

        // 24. Kalimantan Utara (ID: 24)
        $addRegions([
            ['kode' => '6501', 'nama' => 'Kab. Malinau', 'id_kategori' => 2],
            ['kode' => '6502', 'nama' => 'Kab. Bulungan', 'id_kategori' => 2],
            ['kode' => '6503', 'nama' => 'Kab. Tana Tidung', 'id_kategori' => 2],
            ['kode' => '6504', 'nama' => 'Kab. Nunukan', 'id_kategori' => 2],
            ['kode' => '6571', 'nama' => 'Kota Tarakan', 'id_kategori' => 3],
        ], 24);

        // 25. Sulawesi Utara (ID: 25)
        $addRegions([
            ['kode' => '7101', 'nama' => 'Kab. Bolaang Mongondow', 'id_kategori' => 2],
            ['kode' => '7102', 'nama' => 'Kab. Minahasa', 'id_kategori' => 2],
            ['kode' => '7103', 'nama' => 'Kab. Kepulauan Sangihe', 'id_kategori' => 2],
            ['kode' => '7104', 'nama' => 'Kab. Kepulauan Talaud', 'id_kategori' => 2],
            ['kode' => '7105', 'nama' => 'Kab. Minahasa Selatan', 'id_kategori' => 2],
            ['kode' => '7106', 'nama' => 'Kab. Minahasa Utara', 'id_kategori' => 2],
            ['kode' => '7107', 'nama' => 'Kab. Bolaang Mongondow Utara', 'id_kategori' => 2],
            ['kode' => '7108', 'nama' => 'Kab. Siau Tagulandang Biaro', 'id_kategori' => 2],
            ['kode' => '7109', 'nama' => 'Kab. Minahasa Tenggara', 'id_kategori' => 2],
            ['kode' => '7110', 'nama' => 'Kab. Bolaang Mongondow Selatan', 'id_kategori' => 2],
            ['kode' => '7111', 'nama' => 'Kab. Bolaang Mongondow Timur', 'id_kategori' => 2],
            ['kode' => '7171', 'nama' => 'Kota Manado', 'id_kategori' => 3],
            ['kode' => '7172', 'nama' => 'Kota Bitung', 'id_kategori' => 3],
            ['kode' => '7173', 'nama' => 'Kota Tomohon', 'id_kategori' => 3],
            ['kode' => '7174', 'nama' => 'Kota Kotamobagu', 'id_kategori' => 3],
        ], 25);

        // 26. Sulawesi Tengah (ID: 26)
        $addRegions([
            ['kode' => '7201', 'nama' => 'Kab. Banggai Kepulauan', 'id_kategori' => 2],
            ['kode' => '7202', 'nama' => 'Kab. Banggai', 'id_kategori' => 2],
            ['kode' => '7203', 'nama' => 'Kab. Morowali', 'id_kategori' => 2],
            ['kode' => '7204', 'nama' => 'Kab. Poso', 'id_kategori' => 2],
            ['kode' => '7205', 'nama' => 'Kab. Donggala', 'id_kategori' => 2],
            ['kode' => '7206', 'nama' => 'Kab. Toli-Toli', 'id_kategori' => 2],
            ['kode' => '7207', 'nama' => 'Kab. Buol', 'id_kategori' => 2],
            ['kode' => '7208', 'nama' => 'Kab. Parigi Moutong', 'id_kategori' => 2],
            ['kode' => '7209', 'nama' => 'Kab. Tojo Una-Una', 'id_kategori' => 2],
            ['kode' => '7210', 'nama' => 'Kab. Sigi', 'id_kategori' => 2],
            ['kode' => '7211', 'nama' => 'Kab. Banggai Laut', 'id_kategori' => 2],
            ['kode' => '7212', 'nama' => 'Kab. Morowali Utara', 'id_kategori' => 2],
            ['kode' => '7271', 'nama' => 'Kota Palu', 'id_kategori' => 3],
        ], 26);

        // 27. Sulawesi Selatan (ID: 27)
        $addRegions([
            ['kode' => '7301', 'nama' => 'Kab. Kepulauan Selayar', 'id_kategori' => 2],
            ['kode' => '7302', 'nama' => 'Kab. Bulukumba', 'id_kategori' => 2],
            ['kode' => '7303', 'nama' => 'Kab. Bantaeng', 'id_kategori' => 2],
            ['kode' => '7304', 'nama' => 'Kab. Jeneponto', 'id_kategori' => 2],
            ['kode' => '7305', 'nama' => 'Kab. Takalar', 'id_kategori' => 2],
            ['kode' => '7306', 'nama' => 'Kab. Gowa', 'id_kategori' => 2],
            ['kode' => '7307', 'nama' => 'Kab. Sinjai', 'id_kategori' => 2],
            ['kode' => '7308', 'nama' => 'Kab. Maros', 'id_kategori' => 2],
            ['kode' => '7309', 'nama' => 'Kab. Pangkajene Dan Kepulauan', 'id_kategori' => 2],
            ['kode' => '7310', 'nama' => 'Kab. Barru', 'id_kategori' => 2],
            ['kode' => '7311', 'nama' => 'Kab. Bone', 'id_kategori' => 2],
            ['kode' => '7312', 'nama' => 'Kab. Soppeng', 'id_kategori' => 2],
            ['kode' => '7313', 'nama' => 'Kab. Wajo', 'id_kategori' => 2],
            ['kode' => '7314', 'nama' => 'Kab. Sidenreng Rappang', 'id_kategori' => 2],
            ['kode' => '7315', 'nama' => 'Kab. Pinrang', 'id_kategori' => 2],
            ['kode' => '7316', 'nama' => 'Kab. Enrekang', 'id_kategori' => 2],
            ['kode' => '7317', 'nama' => 'Kab. Luwu', 'id_kategori' => 2],
            ['kode' => '7318', 'nama' => 'Kab. Tana Toraja', 'id_kategori' => 2],
            ['kode' => '7322', 'nama' => 'Kab. Luwu Utara', 'id_kategori' => 2],
            ['kode' => '7325', 'nama' => 'Kab. Luwu Timur', 'id_kategori' => 2],
            ['kode' => '7326', 'nama' => 'Kab. Toraja Utara', 'id_kategori' => 2],
            ['kode' => '7371', 'nama' => 'Kota Makassar', 'id_kategori' => 3],
            ['kode' => '7372', 'nama' => 'Kota Pare-Pare', 'id_kategori' => 3],
            ['kode' => '7373', 'nama' => 'Kota Palopo', 'id_kategori' => 3],
        ], 27);

        // 28. Sulawesi Tenggara (ID: 28)
        $addRegions([
            ['kode' => '7401', 'nama' => 'Kab. Buton', 'id_kategori' => 2],
            ['kode' => '7402', 'nama' => 'Kab. Muna', 'id_kategori' => 2],
            ['kode' => '7403', 'nama' => 'Kab. Konawe', 'id_kategori' => 2],
            ['kode' => '7404', 'nama' => 'Kab. Kolaka', 'id_kategori' => 2],
            ['kode' => '7405', 'nama' => 'Kab. Konawe Selatan', 'id_kategori' => 2],
            ['kode' => '7406', 'nama' => 'Kab. Bombana', 'id_kategori' => 2],
            ['kode' => '7407', 'nama' => 'Kab. Wakatobi', 'id_kategori' => 2],
            ['kode' => '7408', 'nama' => 'Kab. Kolaka Utara', 'id_kategori' => 2],
            ['kode' => '7409', 'nama' => 'Kab. Buton Utara', 'id_kategori' => 2],
            ['kode' => '7410', 'nama' => 'Kab. Konawe Utara', 'id_kategori' => 2],
            ['kode' => '7411', 'nama' => 'Kab. Kolaka Timur', 'id_kategori' => 2],
            ['kode' => '7412', 'nama' => 'Kab. Konawe Kepulauan', 'id_kategori' => 2],
            ['kode' => '7413', 'nama' => 'Kab. Muna Barat', 'id_kategori' => 2],
            ['kode' => '7414', 'nama' => 'Kab. Buton Tengah', 'id_kategori' => 2],
            ['kode' => '7415', 'nama' => 'Kab. Buton Selatan', 'id_kategori' => 2],
            ['kode' => '7471', 'nama' => 'Kota Kendari', 'id_kategori' => 3],
            ['kode' => '7472', 'nama' => 'Kota Baubau', 'id_kategori' => 3],
        ], 28);

        // 29. Gorontalo (ID: 29)
        $addRegions([
            ['kode' => '7501', 'nama' => 'Kab. Boalemo', 'id_kategori' => 2],
            ['kode' => '7502', 'nama' => 'Kab. Gorontalo', 'id_kategori' => 2],
            ['kode' => '7503', 'nama' => 'Kab. Pohuwato', 'id_kategori' => 2],
            ['kode' => '7504', 'nama' => 'Kab. Bone Bolango', 'id_kategori' => 2],
            ['kode' => '7505', 'nama' => 'Kab. Gorontalo Utara', 'id_kategori' => 2],
            ['kode' => '7571', 'nama' => 'Kota Gorontalo', 'id_kategori' => 3],
        ], 29);

        // 30. Sulawesi Barat (ID: 30)
        $addRegions([
            ['kode' => '7601', 'nama' => 'Kab. Majene', 'id_kategori' => 2],
            ['kode' => '7602', 'nama' => 'Kab. Polewali Mandar', 'id_kategori' => 2],
            ['kode' => '7603', 'nama' => 'Kab. Mamasa', 'id_kategori' => 2],
            ['kode' => '7604', 'nama' => 'Kab. Mamuju', 'id_kategori' => 2],
            ['kode' => '7605', 'nama' => 'Kab. Pasangkayu', 'id_kategori' => 2],
            ['kode' => '7606', 'nama' => 'Kab. Mamuju Tengah', 'id_kategori' => 2],
        ], 30);

        // 31. Maluku (ID: 31)
        $addRegions([
            ['kode' => '8101', 'nama' => 'Kab. Kepulauan Tanimbar', 'id_kategori' => 2],
            ['kode' => '8102', 'nama' => 'Kab. Maluku Tenggara', 'id_kategori' => 2],
            ['kode' => '8103', 'nama' => 'Kab. Maluku Tengah', 'id_kategori' => 2],
            ['kode' => '8104', 'nama' => 'Kab. Buru', 'id_kategori' => 2],
            ['kode' => '8105', 'nama' => 'Kab. Kepulauan Aru', 'id_kategori' => 2],
            ['kode' => '8106', 'nama' => 'Kab. Seram Bagian Barat', 'id_kategori' => 2],
            ['kode' => '8107', 'nama' => 'Kab. Seram Bagian Timur', 'id_kategori' => 2],
            ['kode' => '8108', 'nama' => 'Kab. Maluku Barat Daya', 'id_kategori' => 2],
            ['kode' => '8109', 'nama' => 'Kab. Buru Selatan', 'id_kategori' => 2],
            ['kode' => '8171', 'nama' => 'Kota Ambon', 'id_kategori' => 3],
            ['kode' => '8172', 'nama' => 'Kota Tual', 'id_kategori' => 3],
        ], 31);

        // 32. Maluku Utara (ID: 32)
        $addRegions([
            ['kode' => '8201', 'nama' => 'Kab. Halmahera Barat', 'id_kategori' => 2],
            ['kode' => '8202', 'nama' => 'Kab. Halmahera Tengah', 'id_kategori' => 2],
            ['kode' => '8203', 'nama' => 'Kab. Kepulauan Sula', 'id_kategori' => 2],
            ['kode' => '8204', 'nama' => 'Kab. Halmahera Selatan', 'id_kategori' => 2],
            ['kode' => '8205', 'nama' => 'Kab. Halmahera Utara', 'id_kategori' => 2],
            ['kode' => '8206', 'nama' => 'Kab. Halmahera Timur', 'id_kategori' => 2],
            ['kode' => '8207', 'nama' => 'Kab. Pulau Morotai', 'id_kategori' => 2],
            ['kode' => '8208', 'nama' => 'Kab. Pulau Taliabu', 'id_kategori' => 2],
            ['kode' => '8271', 'nama' => 'Kota Ternate', 'id_kategori' => 3],
            ['kode' => '8272', 'nama' => 'Kota Tidore Kepulauan', 'id_kategori' => 3],
        ], 32);

        // 33. Papua Barat (ID: 33)
        $addRegions([
            ['kode' => '9101', 'nama' => 'Kab. Fakfak', 'id_kategori' => 2],
            ['kode' => '9102', 'nama' => 'Kab. Kaimana', 'id_kategori' => 2],
            ['kode' => '9103', 'nama' => 'Kab. Teluk Wondama', 'id_kategori' => 2],
            ['kode' => '9104', 'nama' => 'Kab. Teluk Bintuni', 'id_kategori' => 2],
            ['kode' => '9105', 'nama' => 'Kab. Manokwari', 'id_kategori' => 2],
            ['kode' => '9106', 'nama' => 'Kab. Sorong Selatan', 'id_kategori' => 2],
            ['kode' => '9107', 'nama' => 'Kab. Sorong', 'id_kategori' => 2],
            ['kode' => '9108', 'nama' => 'Kab. Raja Ampat', 'id_kategori' => 2],
            ['kode' => '9109', 'nama' => 'Kab. Tambrauw', 'id_kategori' => 2],
            ['kode' => '9110', 'nama' => 'Kab. Maybrat', 'id_kategori' => 2],
            ['kode' => '9111', 'nama' => 'Kab. Manokwari Selatan', 'id_kategori' => 2],
            ['kode' => '9112', 'nama' => 'Kab. Pegunungan Arfak', 'id_kategori' => 2],
            ['kode' => '9171', 'nama' => 'Kota Sorong', 'id_kategori' => 3],
        ], 33);

        // 34. Papua (ID: 34)
        $addRegions([
            ['kode' => '9201', 'nama' => 'Kab. Merauke', 'id_kategori' => 2],
            ['kode' => '9202', 'nama' => 'Kab. Jayawijaya', 'id_kategori' => 2],
            ['kode' => '9203', 'nama' => 'Kab. Jayapura', 'id_kategori' => 2],
            ['kode' => '9204', 'nama' => 'Kab. Nabire', 'id_kategori' => 2],
            ['kode' => '9205', 'nama' => 'Kab. Kepulauan Yapen', 'id_kategori' => 2],
            ['kode' => '9206', 'nama' => 'Kab. Biak Numfor', 'id_kategori' => 2],
            ['kode' => '9207', 'nama' => 'Kab. Paniai', 'id_kategori' => 2],
            ['kode' => '9208', 'nama' => 'Kab. Puncak Jaya', 'id_kategori' => 2],
            ['kode' => '9209', 'nama' => 'Kab. Mimika', 'id_kategori' => 2],
            ['kode' => '9210', 'nama' => 'Kab. Boven Digoel', 'id_kategori' => 2],
            ['kode' => '9211', 'nama' => 'Kab. Mappi', 'id_kategori' => 2],
            ['kode' => '9212', 'nama' => 'Kab. Asmat', 'id_kategori' => 2],
            ['kode' => '9213', 'nama' => 'Kab. Yahukimo', 'id_kategori' => 2],
            ['kode' => '9214', 'nama' => 'Kab. Pegunungan Bintang', 'id_kategori' => 2],
            ['kode' => '9215', 'nama' => 'Kab. Tolikara', 'id_kategori' => 2],
            ['kode' => '9216', 'nama' => 'Kab. Sarmi', 'id_kategori' => 2],
            ['kode' => '9217', 'nama' => 'Kab. Keerom', 'id_kategori' => 2],
            ['kode' => '9218', 'nama' => 'Kab. Waropen', 'id_kategori' => 2],
            ['kode' => '9219', 'nama' => 'Kab. Supiori', 'id_kategori' => 2],
            ['kode' => '9220', 'nama' => 'Kab. Mamberamo Raya', 'id_kategori' => 2],
            ['kode' => '9221', 'nama' => 'Kab. Nduga', 'id_kategori' => 2],
            ['kode' => '9222', 'nama' => 'Kab. Lanny Jaya', 'id_kategori' => 2],
            ['kode' => '9223', 'nama' => 'Kab. Mamberamo Tengah', 'id_kategori' => 2],
            ['kode' => '9224', 'nama' => 'Kab. Yalimo', 'id_kategori' => 2],
            ['kode' => '9225', 'nama' => 'Kab. Puncak', 'id_kategori' => 2],
            ['kode' => '9226', 'nama' => 'Kab. Dogiyai', 'id_kategori' => 2],
            ['kode' => '9227', 'nama' => 'Kab. Intan Jaya', 'id_kategori' => 2],
            ['kode' => '9228', 'nama' => 'Kab. Deiyai', 'id_kategori' => 2],
            ['kode' => '9271', 'nama' => 'Kota Jayapura', 'id_kategori' => 3],
        ], 34);

        return $regencies;
    }
}
