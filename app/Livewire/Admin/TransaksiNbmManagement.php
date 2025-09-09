<?php

namespace App\Livewire\Admin;

use App\Models\TransaksiNbm;
use App\Models\Kelompok;
use App\Models\Komoditi;
use App\Exports\TransaksiNbmExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class TransaksiNbmManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showViewModal = false;
    public $viewingTransaksi = [];
    
    // Sorting
    public $sortField = '';
    public $sortDirection = 'asc';
    
    // Filters
    public $filterTahun = '';
    public $filterKelompok = '';
    public $filterKomoditi = '';
    public $filterStatusAngka = '';
    public $showFilters = false;
    
    // Form fields
    public $kode_kelompok = '';
    public $kode_komoditi = '';
    public $tahun = '';
    public $bulan = '';
    public $kuartal = '';
    public $periode_data = '';
    public $status_angka = 'tetap';
    public $masukan = '';
    public $keluaran = '';
    public $impor = '';
    public $ekspor = '';
    public $perubahan_stok = '';
    public $pakan = '';
    public $bibit = '';
    public $makanan = '';
    public $bukan_makanan = '';
    public $tercecer = '';
    public $penggunaan_lain = '';
    public $bahan_makanan = '';
    public $kg_tahun = '';
    public $gram_hari = '';
    public $kalori_hari = '';
    public $protein_hari = '';
    public $lemak_hari = '';
    public $harga_produsen = '';
    public $harga_konsumen = '';
    public $inflasi_komoditi = '';
    public $nilai_tukar_usd = '';
    public $populasi_indonesia = '';
    public $gdp_per_kapita = '';
    public $tingkat_kemiskinan = '';
    public $curah_hujan_mm = '';
    public $suhu_rata_celsius = '';
    public $indeks_el_nino = '';
    public $luas_panen_ha = '';
    public $produktivitas_ton_ha = '';
    public $kebijakan_impor = '';
    public $subsidi_pemerintah = '';
    public $stok_bulog = '';
    public $confidence_score = '';
    public $data_source = '';
    public $validation_status = '';
    public $outlier_flag = '';
    
    public $editingTransaksi = null;
    public $deletingTransaksi = null;
    public $exportFormat = 'xlsx';

    // Data untuk select options
    public $kelompokOptions = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'filterTahun' => ['except' => ''],
        'filterKelompok' => ['except' => ''],
        'filterKomoditi' => ['except' => ''],
        'filterStatusAngka' => ['except' => ''],
    ];

    protected $casts = [
        'perPage' => 'integer',
    ];

    public function mount()
    {
        $this->loadSelectOptions();
    }

    public function loadSelectOptions()
    {
        $this->kelompokOptions = Kelompok::orderBy('kode')->get(['kode', 'nama'])->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedKodeKelompok()
    {
        // Reset komoditi saat kelompok berubah
        $this->kode_komoditi = '';
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            if ($this->sortDirection === 'asc') {
                $this->sortDirection = 'desc';
            } else {
                // Reset to unsorted state when clicking desc again
                $this->sortField = '';
                $this->sortDirection = 'asc';
            }
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }

    public function resetSort()
    {
        $this->sortField = '';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearFilters()
    {
        $this->filterTahun = '';
        $this->filterKelompok = '';
        $this->filterKomoditi = '';
        $this->filterStatusAngka = '';
        $this->resetPage();
    }

    public function updatedFilterTahun()
    {
        $this->resetPage();
    }

    public function updatedFilterKelompok()
    {
        // Reset filter komoditi ketika kelompok berubah
        $this->filterKomoditi = '';
        $this->resetPage();
    }

    public function getKomoditiOptionsProperty()
    {
        return Komoditi::with('kelompok')
            ->when($this->filterKelompok, function($query) {
                $query->where('kode_kelompok', $this->filterKelompok);
            })
            ->orderBy('nama')
            ->get(['kode_komoditi as kode', 'nama'])
            ->toArray();
    }

    public function getModalKomoditiOptionsProperty()
    {
        if (empty($this->kode_kelompok)) {
            return [];
        }
        
        return Komoditi::where('kode_kelompok', $this->kode_kelompok)
            ->orderBy('nama')
            ->get(['kode_komoditi as kode', 'nama'])
            ->toArray();
    }

    public function updatedFilterKomoditi()
    {
        $this->resetPage();
    }

    public function updatedFilterStatusAngka()
    {
        $this->resetPage();
    }

    protected $rules = [
        'kode_kelompok' => 'required',
        'kode_komoditi' => 'required',
        'tahun' => 'required|integer|min:1900|max:2100',
        'bulan' => 'nullable|integer|min:1|max:12',
        'kuartal' => 'nullable|integer|min:1|max:4',
        'periode_data' => 'nullable|string',
        'status_angka' => 'required|in:tetap,sementara,sangat sementara',
        'masukan' => 'nullable|numeric|min:0',
        'keluaran' => 'nullable|numeric|min:0',
        'impor' => 'nullable|numeric|min:0',
        'ekspor' => 'nullable|numeric|min:0',
        'perubahan_stok' => 'nullable|numeric',
        'pakan' => 'nullable|numeric|min:0',
        'bibit' => 'nullable|numeric|min:0',
        'makanan' => 'nullable|numeric|min:0',
        'bukan_makanan' => 'nullable|numeric|min:0',
        'tercecer' => 'nullable|numeric|min:0',
        'penggunaan_lain' => 'nullable|numeric|min:0',
        'bahan_makanan' => 'nullable|numeric|min:0',
        'kg_tahun' => 'nullable|numeric|min:0',
        'gram_hari' => 'nullable|numeric|min:0',
        'kalori_hari' => 'nullable|numeric|min:0',
        'protein_hari' => 'nullable|numeric|min:0',
        'lemak_hari' => 'nullable|numeric|min:0',
        'harga_produsen' => 'nullable|numeric|min:0',
        'harga_konsumen' => 'nullable|numeric|min:0',
        'inflasi_komoditi' => 'nullable|numeric',
        'nilai_tukar_usd' => 'nullable|numeric',
        'populasi_indonesia' => 'nullable|numeric|min:0',
        'gdp_per_kapita' => 'nullable|numeric|min:0',
        'tingkat_kemiskinan' => 'nullable|numeric|min:0',
        'curah_hujan_mm' => 'nullable|numeric',
        'suhu_rata_celsius' => 'nullable|numeric',
        'indeks_el_nino' => 'nullable|numeric',
        'luas_panen_ha' => 'nullable|numeric|min:0',
        'produktivitas_ton_ha' => 'nullable|numeric|min:0',
        'kebijakan_impor' => 'nullable|string',
        'subsidi_pemerintah' => 'nullable|numeric|min:0',
        'stok_bulog' => 'nullable|numeric|min:0',
        'confidence_score' => 'nullable|numeric',
        'data_source' => 'nullable|string',
        'validation_status' => 'nullable|string',
        'outlier_flag' => 'nullable|boolean',
    ];

    private function validateKomoditiKelompok()
    {
        if (empty($this->kode_kelompok)) {
            $this->addError('kode_kelompok', 'Kelompok harus dipilih terlebih dahulu.');
            return;
        }

        if (empty($this->kode_komoditi)) {
            $this->addError('kode_komoditi', 'Komoditi harus dipilih.');
            return;
        }

        // Validasi bahwa komoditi yang dipilih sesuai dengan kelompok
        $komoditi = Komoditi::where('kode_komoditi', $this->kode_komoditi)
            ->where('kode_kelompok', $this->kode_kelompok)
            ->first();
            
        if (!$komoditi) {
            $this->addError('kode_komoditi', 'Komoditi yang dipilih tidak sesuai dengan kelompok.');
            return;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage($value)
    {
        if (! in_array((int)$value, $this->perPageOptions, true)) {
            $this->perPage = 10;
        }
        $this->resetPage();
    }

    public function updatingPerPage($value)
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->loadSelectOptions(); // Memuat data untuk select options
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($transaksiId)
    {
        $this->editingTransaksi = TransaksiNbm::findOrFail($transaksiId);
        $this->fillFormFromModel($this->editingTransaksi);
        $this->loadSelectOptions(); // Memuat data untuk select options
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($transaksiId)
    {
        $this->deletingTransaksi = TransaksiNbm::findOrFail($transaksiId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deletingTransaksi = null;
    }

    public function createTransaksi()
    {
        $this->validate();
        $this->validateKomoditiKelompok();

        DB::transaction(function () {
            // Hapus data lama jika ada kombinasi unik yang sama
            TransaksiNbm::where('tahun', $this->tahun)
                ->where('kode_komoditi', $this->kode_komoditi)
                ->where('status_angka', $this->status_angka)
                ->delete();

            // Buat data baru
            TransaksiNbm::create($this->getFormData());
        });

        session()->flash('message', 'Transaksi NBM berhasil dibuat.');
        $this->closeCreateModal();
    }

    public function updateTransaksi()
    {
        $this->validate();
        $this->validateKomoditiKelompok();

        $original = $this->editingTransaksi;
        $formData = $this->getFormData();

        // Cek apakah ada perubahan pada kolom unik
        $isUniqueKeyChanged = $original->tahun != $formData['tahun'] ||
                              $original->kode_komoditi != $formData['kode_komoditi'] ||
                              $original->status_angka != $formData['status_angka'];

        DB::transaction(function () use ($isUniqueKeyChanged, $formData) {
            if ($isUniqueKeyChanged) {
                // Jika kunci unik berubah, hapus data lama dan buat yang baru
                // Ini secara efektif mengganti record dengan ID baru

                // Hapus dulu jika ada data lain yang sama dengan kombinasi unik baru
                TransaksiNbm::where('tahun', $formData['tahun'])
                    ->where('kode_komoditi', $formData['kode_komoditi'])
                    ->where('status_angka', $formData['status_angka'])
                    ->delete();

                // Hapus data asli yang sedang diedit
                $this->editingTransaksi->delete();

                // Buat record baru
                TransaksiNbm::create($formData);

                session()->flash('message', 'Transaksi NBM berhasil diganti dengan data baru.');
            } else {
                // Jika tidak ada perubahan pada kunci unik, cukup perbarui data
                $this->editingTransaksi->update($formData);
                session()->flash('message', 'Transaksi NBM berhasil diupdate.');
            }
        });

        $this->closeEditModal();
    }

    public function deleteTransaksi()
    {
        if ($this->deletingTransaksi) {
            $this->deletingTransaksi->delete();
            session()->flash('message', 'Transaksi NBM berhasil dihapus.');
            $this->closeDeleteModal();
        }
    }

    private function getFormData()
    {
        return [
            'kode_kelompok' => $this->kode_kelompok,
            'kode_komoditi' => $this->kode_komoditi,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan ?: null,
            'kuartal' => $this->kuartal ?: null,
            'periode_data' => $this->periode_data ?: null,
            'status_angka' => $this->status_angka,
            'masukan' => $this->masukan ?: null,
            'keluaran' => $this->keluaran ?: null,
            'impor' => $this->impor ?: null,
            'ekspor' => $this->ekspor ?: null,
            'perubahan_stok' => $this->perubahan_stok ?: null,
            'pakan' => $this->pakan ?: null,
            'bibit' => $this->bibit ?: null,
            'makanan' => $this->makanan ?: null,
            'bukan_makanan' => $this->bukan_makanan ?: null,
            'tercecer' => $this->tercecer ?: null,
            'penggunaan_lain' => $this->penggunaan_lain ?: null,
            'bahan_makanan' => $this->bahan_makanan ?: null,
            'kg_tahun' => $this->kg_tahun ?: null,
            'gram_hari' => $this->gram_hari ?: null,
            'kalori_hari' => $this->kalori_hari ?: null,
            'protein_hari' => $this->protein_hari ?: null,
            'lemak_hari' => $this->lemak_hari ?: null,
            'harga_produsen' => $this->harga_produsen ?: null,
            'harga_konsumen' => $this->harga_konsumen ?: null,
            'inflasi_komoditi' => $this->inflasi_komoditi ?: null,
            'nilai_tukar_usd' => $this->nilai_tukar_usd ?: null,
            'populasi_indonesia' => $this->populasi_indonesia ?: null,
            'gdp_per_kapita' => $this->gdp_per_kapita ?: null,
            'tingkat_kemiskinan' => $this->tingkat_kemiskinan ?: null,
            'curah_hujan_mm' => $this->curah_hujan_mm ?: null,
            'suhu_rata_celsius' => $this->suhu_rata_celsius ?: null,
            'indeks_el_nino' => $this->indeks_el_nino ?: null,
            'luas_panen_ha' => $this->luas_panen_ha ?: null,
            'produktivitas_ton_ha' => $this->produktivitas_ton_ha ?: null,
            'kebijakan_impor' => $this->kebijakan_impor ?: null,
            'subsidi_pemerintah' => $this->subsidi_pemerintah ?: null,
            'stok_bulog' => $this->stok_bulog ?: null,
            'confidence_score' => $this->confidence_score ?: null,
            'data_source' => $this->data_source ?: null,
            'validation_status' => $this->validation_status ?: null,
            'outlier_flag' => $this->outlier_flag ?: null,
        ];
    }

    private function fillFormFromModel($transaksi)
    {
    $this->kode_kelompok = $transaksi->kode_kelompok;
    $this->kode_komoditi = $transaksi->kode_komoditi;
    $this->tahun = $transaksi->tahun;
    $this->bulan = $transaksi->bulan;
    $this->kuartal = $transaksi->kuartal;
    $this->periode_data = $transaksi->periode_data;
    $this->status_angka = $transaksi->status_angka;
    $this->masukan = $transaksi->masukan;
    $this->keluaran = $transaksi->keluaran;
    $this->impor = $transaksi->impor;
    $this->ekspor = $transaksi->ekspor;
    $this->perubahan_stok = $transaksi->perubahan_stok;
    $this->pakan = $transaksi->pakan;
    $this->bibit = $transaksi->bibit;
    $this->makanan = $transaksi->makanan;
    $this->bukan_makanan = $transaksi->bukan_makanan;
    $this->tercecer = $transaksi->tercecer;
    $this->penggunaan_lain = $transaksi->penggunaan_lain;
    $this->bahan_makanan = $transaksi->bahan_makanan;
    $this->kg_tahun = $transaksi->kg_tahun;
    $this->gram_hari = $transaksi->gram_hari;
    $this->kalori_hari = $transaksi->kalori_hari;
    $this->protein_hari = $transaksi->protein_hari;
    $this->lemak_hari = $transaksi->lemak_hari;
    $this->harga_produsen = $transaksi->harga_produsen;
    $this->harga_konsumen = $transaksi->harga_konsumen;
    $this->inflasi_komoditi = $transaksi->inflasi_komoditi;
    $this->nilai_tukar_usd = $transaksi->nilai_tukar_usd;
    $this->populasi_indonesia = $transaksi->populasi_indonesia;
    $this->gdp_per_kapita = $transaksi->gdp_per_kapita;
    $this->tingkat_kemiskinan = $transaksi->tingkat_kemiskinan;
    $this->curah_hujan_mm = $transaksi->curah_hujan_mm;
    $this->suhu_rata_celsius = $transaksi->suhu_rata_celsius;
    $this->indeks_el_nino = $transaksi->indeks_el_nino;
    $this->luas_panen_ha = $transaksi->luas_panen_ha;
    $this->produktivitas_ton_ha = $transaksi->produktivitas_ton_ha;
    $this->kebijakan_impor = $transaksi->kebijakan_impor;
    $this->subsidi_pemerintah = $transaksi->subsidi_pemerintah;
    $this->stok_bulog = $transaksi->stok_bulog;
    $this->confidence_score = $transaksi->confidence_score;
    $this->data_source = $transaksi->data_source;
    $this->validation_status = $transaksi->validation_status;
    $this->outlier_flag = $transaksi->outlier_flag;
    }

    private function resetForm()
    {
    $this->kode_kelompok = '';
    $this->kode_komoditi = '';
    $this->tahun = '';
    $this->bulan = '';
    $this->kuartal = '';
    $this->periode_data = '';
    $this->status_angka = 'tetap';
    $this->masukan = '';
    $this->keluaran = '';
    $this->impor = '';
    $this->ekspor = '';
    $this->perubahan_stok = '';
    $this->pakan = '';
    $this->bibit = '';
    $this->makanan = '';
    $this->bukan_makanan = '';
    $this->tercecer = '';
    $this->penggunaan_lain = '';
    $this->bahan_makanan = '';
    $this->kg_tahun = '';
    $this->gram_hari = '';
    $this->kalori_hari = '';
    $this->protein_hari = '';
    $this->lemak_hari = '';
    $this->harga_produsen = '';
    $this->harga_konsumen = '';
    $this->inflasi_komoditi = '';
    $this->nilai_tukar_usd = '';
    $this->populasi_indonesia = '';
    $this->gdp_per_kapita = '';
    $this->tingkat_kemiskinan = '';
    $this->curah_hujan_mm = '';
    $this->suhu_rata_celsius = '';
    $this->indeks_el_nino = '';
    $this->luas_panen_ha = '';
    $this->produktivitas_ton_ha = '';
    $this->kebijakan_impor = '';
    $this->subsidi_pemerintah = '';
    $this->stok_bulog = '';
    $this->confidence_score = '';
    $this->data_source = '';
    $this->validation_status = '';
    $this->outlier_flag = '';
    $this->editingTransaksi = null;
    $this->resetErrorBag();
    }

    public function render()
    {
        $perPage = (int) $this->perPage;
        if (! in_array($perPage, $this->perPageOptions, true)) {
            $perPage = 10;
        }

        $query = TransaksiNbm::with(['kelompok', 'komoditi'])
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('kode_kelompok', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_komoditi', 'like', '%' . $this->search . '%')
                      ->orWhere('tahun', 'like', '%' . $this->search . '%')
                      ->orWhere('status_angka', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kelompok', function($q) {
                          $q->where('nama', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('komoditi', function($q) {
                          $q->where('nama', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filterTahun, function ($query) {
                $query->where('tahun', $this->filterTahun);
            })
            ->when($this->filterKelompok, function ($query) {
                $query->where('kode_kelompok', $this->filterKelompok);
            })
            ->when($this->filterKomoditi, function ($query) {
                $query->where('kode_komoditi', $this->filterKomoditi);
            })
            ->when($this->filterStatusAngka, function ($query) {
                $query->where('status_angka', $this->filterStatusAngka);
            });

        // Apply sorting only if sortField is set
        if (!empty($this->sortField)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            // Default ordering when no sort is applied (by ID for consistency)
            $query->orderBy('id', 'asc');
        }

        $transaksiNbms = $query->paginate($perPage);

        // Load filter options
        $kelompokOptions = Kelompok::orderBy('nama')
            ->get(['kode', 'nama'])
            ->toArray();
        
        $tahunOptions = TransaksiNbm::distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
            
        $statusAngkaOptions = TransaksiNbm::distinct()
            ->orderBy('status_angka')
            ->pluck('status_angka');

        return view('livewire.admin.transaksi-nbm-management', [
            'transaksiNbms' => $transaksiNbms,
            'kelompokOptions' => $kelompokOptions,
            'tahunOptions' => $tahunOptions,
            'statusAngkaOptions' => $statusAngkaOptions,
        ]);
    }

    public function view($id)
    {
        $transaksi = TransaksiNbm::with(['kelompok', 'komoditi'])->findOrFail($id);
        $this->viewingTransaksi = $transaksi->toArray();
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewingTransaksi = [];
    }

    public function export()
    {
        $format = strtolower($this->exportFormat ?? 'xlsx');
        if (! in_array($format, ['xlsx','csv'], true)) {
            $format = 'xlsx';
        }

        $filename = 'transaksi-nbm-' . now()->format('Ymd-His') . '.' . $format;
        
        return Excel::download(new TransaksiNbmExport(), $filename, $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX);
    }

    public function print()
    {
        $this->dispatch('print-transaksi-nbm');
    }

    public function printAll()
    {
        $allData = $this->getAllDataForPrint();
        $this->dispatch('print-all-transaksi-nbm', data: $allData->toArray());
    }

    public function getAllDataForPrint()
    {
        $query = TransaksiNbm::with(['kelompok', 'komoditi'])
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('kode_kelompok', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_komoditi', 'like', '%' . $this->search . '%')
                      ->orWhere('tahun', 'like', '%' . $this->search . '%')
                      ->orWhere('status_angka', 'like', '%' . $this->search . '%')
                      ->orWhereHas('kelompok', function($q) {
                          $q->where('nama', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('komoditi', function($q) {
                          $q->where('nama', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filterTahun, function ($query) {
                $query->where('tahun', $this->filterTahun);
            })
            ->when($this->filterKelompok, function ($query) {
                $query->where('kode_kelompok', $this->filterKelompok);
            })
            ->when($this->filterKomoditi, function ($query) {
                $query->where('kode_komoditi', $this->filterKomoditi);
            })
            ->when($this->filterStatusAngka, function ($query) {
                $query->where('status_angka', $this->filterStatusAngka);
            });

        // Apply sorting only if sortField is set
        if (!empty($this->sortField)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            // Default ordering when no sort is applied (by ID for consistency)
            $query->orderBy('id', 'asc');
        }

        return $query->get();
    }
}
