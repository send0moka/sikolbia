<?php

namespace App\Livewire\Admin\BenihPupuk;

use App\Models\BenihPupukData;
use App\Models\BenihPupukTopik;
use App\Models\BenihPupukVariabel;
use App\Models\BenihPupukKlasifikasi;
use App\Models\Bulan;
use App\Models\Wilayah;
use App\Exports\BenihPupukExport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class DataBenihPupuk extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $tahunFilter = '';
    
    #[Url]
    public $bulanFilter = '';
    
    #[Url]
    public $wilayahFilter = '';
    
    #[Url]
    public $variabelFilter = '';
    
    #[Url]
    public $klasifikasiFilter = '';
    
    #[Url]
    public $statusFilter = '';
    
    #[Url]
    public $sortBy = 'tahun';
    
    #[Url]
    public $sortDir = 'desc';

    public $perPage = 10;
    public array $perPageOptions = [5, 10, 25, 100];
    public $showModal = false;
    public $editMode = false;
    public $deleteId = null;
    public $showFilters = false;
    public $editingId = null;
    public $exportFormat = 'xlsx';
    public $tahun = '';
    public $id_bulan = '';
    public $id_wilayah = '';
    public $id_variabel = '';
    public $id_klasifikasi = '';
    public $nilai = '';
    public $status = 'A';

    protected $rules = [
        'tahun' => 'required|integer|min:2000|max:2050',
        'id_bulan' => 'required|exists:bulan,id',
        'id_wilayah' => 'required|exists:wilayah,id',
        'id_variabel' => 'required|exists:benih_pupuk_variabel,id',
        'id_klasifikasi' => 'required|exists:benih_pupuk_klasifikasi,id',
        'nilai' => 'nullable|numeric',
        'status' => 'required|in:A,I,D',
    ];

    public function updatingSearch()
    {
        // Debounce search to avoid too many requests
        $this->resetPage();
    }

    public function updatedSearch()
    {
        // Reset page when search changes
        $this->resetPage();
    }

    public function updatedTahunFilter()
    {
        $this->resetPage();
    }

    public function updatedBulanFilter()
    {
        $this->resetPage();
    }

    public function updatedWilayahFilter()
    {
        $this->resetPage();
    }

    public function updatedVariabelFilter()
    {
        $this->resetPage();
    }

    public function updatedKlasifikasiFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage($value)
    {
        if (! in_array((int)$value, $this->perPageOptions, true)) {
            $this->perPage = 10; // fallback
        }
        $this->resetPage();
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function resetSort()
    {
        $this->sortBy = 'tahun';
        $this->sortDir = 'desc';
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $data = BenihPupukData::findOrFail($id);
        $this->editingId = $id;
        $this->tahun = $data->tahun;
        $this->id_bulan = $data->id_bulan;
        $this->id_wilayah = $data->id_wilayah;
        $this->id_variabel = $data->id_variabel;
        $this->id_klasifikasi = $data->id_klasifikasi;
        $this->nilai = $data->nilai;
        $this->status = $data->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'tahun' => $this->tahun,
            'id_bulan' => $this->id_bulan,
            'id_wilayah' => $this->id_wilayah,
            'id_variabel' => $this->id_variabel,
            'id_klasifikasi' => $this->id_klasifikasi,
            'nilai' => $this->nilai,
            'status' => $this->status,
        ];

        if ($this->editingId) {
            BenihPupukData::find($this->editingId)->update($data);
            session()->flash('message', 'Data berhasil diperbarui.');
        } else {
            BenihPupukData::create($data);
            session()->flash('message', 'Data berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        BenihPupukData::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->tahun = '';
        $this->id_bulan = '';
        $this->id_wilayah = '';
        $this->id_variabel = '';
        $this->id_klasifikasi = '';
        $this->nilai = '';
        $this->status = 'A';
        $this->resetErrorBag();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function clearAllFilters()
    {
        $this->search = '';
        $this->tahunFilter = '';
        $this->bulanFilter = '';
        $this->wilayahFilter = '';
        $this->variabelFilter = '';
        $this->klasifikasiFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function export()
    {
        try {
            // Use URL parameters instead of session for download
            $format = strtolower($this->exportFormat ?? 'xlsx');
            if (! in_array($format, ['xlsx','csv'], true)) {
                $format = 'xlsx';
            }

            $filename = 'data-benih-pupuk-' . now()->format('Ymd-His') . '.' . $format;

            Log::info('Redirecting to download with parameters', ['filename' => $filename, 'format' => $format]);

            // Add success message
            session()->flash('message', 'Export berhasil dimulai - file akan didownload dalam beberapa saat');

            // Redirect with parameters
            return redirect('/admin/benih-pupuk/export/download?filename=' . urlencode($filename) . '&format=' . $format);

        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            session()->flash('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = BenihPupukData::withRelations();

        // Optimize query by selecting only needed fields
        $query->select([
            'id', 'tahun', 'id_bulan', 'id_wilayah', 'id_variabel',
            'id_klasifikasi', 'nilai', 'status', 'created_at', 'updated_at'
        ]);

        // Apply search with optimized query
        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                // Search in main table fields (fastest)
                $q->where('tahun', 'like', $searchTerm)
                  ->orWhere('nilai', 'like', $searchTerm)
                  ->orWhere('status', 'like', $searchTerm)
                  // Search in related tables using LEFT JOIN for better performance
                  ->orWhereRaw('EXISTS (
                      SELECT 1 FROM bulan b WHERE b.id = benih_pupuk_data.id_bulan
                      AND LOWER(b.nama) LIKE ?
                  )', [strtolower($searchTerm)])
                  ->orWhereRaw('EXISTS (
                      SELECT 1 FROM wilayah w WHERE w.id = benih_pupuk_data.id_wilayah
                      AND (LOWER(w.nama) LIKE ? OR LOWER(w.kode) LIKE ?)
                  )', [strtolower($searchTerm), strtolower($searchTerm)])
                  ->orWhereRaw('EXISTS (
                      SELECT 1 FROM benih_pupuk_variabel v WHERE v.id = benih_pupuk_data.id_variabel
                      AND LOWER(v.deskripsi) LIKE ?
                  )', [strtolower($searchTerm)])
                  ->orWhereRaw('EXISTS (
                      SELECT 1 FROM benih_pupuk_klasifikasi k WHERE k.id = benih_pupuk_data.id_klasifikasi
                      AND LOWER(k.deskripsi) LIKE ?
                  )', [strtolower($searchTerm)]);
            });
        }

        if ($this->tahunFilter) {
            $query->where('tahun', $this->tahunFilter);
        }

        if ($this->bulanFilter) {
            $query->where('id_bulan', $this->bulanFilter);
        }

        if ($this->wilayahFilter) {
            $query->where('id_wilayah', $this->wilayahFilter);
        }

        if ($this->variabelFilter) {
            $query->where('id_variabel', $this->variabelFilter);
        }

        if ($this->klasifikasiFilter) {
            $query->where('id_klasifikasi', $this->klasifikasiFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'bulan':
                $query->leftJoin('bulan', 'benih_pupuk_data.id_bulan', '=', 'bulan.id')
                      ->orderBy('bulan.nama', $this->sortDir)
                      ->select('benih_pupuk_data.*');
                break;
            case 'wilayah':
                $query->leftJoin('wilayah', 'benih_pupuk_data.id_wilayah', '=', 'wilayah.id')
                      ->orderBy('wilayah.nama', $this->sortDir)
                      ->select('benih_pupuk_data.*');
                break;
            case 'variabel':
                $query->leftJoin('benih_pupuk_variabel', 'benih_pupuk_data.id_variabel', '=', 'benih_pupuk_variabel.id')
                      ->orderBy('benih_pupuk_variabel.deskripsi', $this->sortDir)
                      ->select('benih_pupuk_data.*');
                break;
            case 'klasifikasi':
                $query->leftJoin('benih_pupuk_klasifikasi', 'benih_pupuk_data.id_klasifikasi', '=', 'benih_pupuk_klasifikasi.id')
                      ->orderBy('benih_pupuk_klasifikasi.deskripsi', $this->sortDir)
                      ->select('benih_pupuk_data.*');
                break;
            default:
                $query->orderBy($this->sortBy, $this->sortDir);
                break;
        }

        $data = $query->paginate($this->perPage);

        // Get filter options with caching
        $tahunOptions = cache()->remember('benih_pupuk_tahun_options', 3600, function () {
            return BenihPupukData::getAvailableYears();
        });

        $bulanOptions = cache()->remember('benih_pupuk_bulan_options', 3600, function () {
            return Bulan::orderBy('id')->pluck('nama', 'id')->toArray();
        });

        $wilayahOptions = cache()->remember('benih_pupuk_wilayah_options', 3600, function () {
            return Wilayah::orderBy('nama')->pluck('nama', 'id')->toArray();
        });

        $variabelOptions = cache()->remember('benih_pupuk_variabel_options', 3600, function () {
            return BenihPupukVariabel::getDropdownOptions();
        });

        $klasifikasiOptions = cache()->remember('benih_pupuk_klasifikasi_options', 3600, function () {
            return BenihPupukKlasifikasi::getDropdownOptions();
        });

        $statusOptions = cache()->remember('benih_pupuk_status_options', 3600, function () {
            return BenihPupukData::getStatusOptions();
        });

        return view('livewire.admin.benih-pupuk.data-benih-pupuk', compact(
            'data', 'tahunOptions', 'bulanOptions', 'wilayahOptions', 
            'variabelOptions', 'klasifikasiOptions', 'statusOptions'
        ));
    }

    public function print()
    {
        $this->dispatch('print-benih-pupuk');
    }

    public function printAll()
    {
        // Get all data without pagination for printing
        $allData = BenihPupukData::with(['bulan', 'wilayah', 'variabel', 'klasifikasi'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('wilayah', fn($sub) => $sub->where('nama', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('variabel', fn($sub) => $sub->where('deskripsi', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('klasifikasi', fn($sub) => $sub->where('deskripsi', 'like', '%' . $this->search . '%'))
                      ->orWhere('tahun', 'like', '%' . $this->search . '%')
                      ->orWhere('nilai', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->tahunFilter, fn($query) => $query->where('tahun', $this->tahunFilter))
            ->when($this->bulanFilter, fn($query) => $query->where('id_bulan', $this->bulanFilter))
            ->when($this->wilayahFilter, fn($query) => $query->where('id_wilayah', $this->wilayahFilter))
            ->when($this->variabelFilter, fn($query) => $query->where('id_variabel', $this->variabelFilter))
            ->when($this->klasifikasiFilter, fn($query) => $query->where('id_klasifikasi', $this->klasifikasiFilter))
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->sortBy, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDir);
            })
            ->limit(5000) // Limit to prevent memory exhaustion
            ->get()
            ->toArray();

        // Log::info('Print All Data Count: ' . count($allData));
        if (count($allData) >= 5000) {
            // Log::warning('Print All reached limit of 5000 records. Consider using filters to reduce data size.');
        }
        // Log::info('Print All Data Sample: ' . json_encode($allData[0] ?? 'No data'));

        $this->dispatch('print-all-benih-pupuk', data: $allData);
    }

    public function printAllChunked()
    {
        // Use chunked processing to handle large datasets without memory issues
        $allData = [];
        $chunkSize = 1000; // Process in chunks of 1000 records
        $totalProcessed = 0;

        BenihPupukData::with(['bulan', 'wilayah', 'variabel', 'klasifikasi'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('wilayah', fn($sub) => $sub->where('nama', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('variabel', fn($sub) => $sub->where('deskripsi', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('klasifikasi', fn($sub) => $sub->where('deskripsi', 'like', '%' . $this->search . '%'))
                      ->orWhere('tahun', 'like', '%' . $this->search . '%')
                      ->orWhere('nilai', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->tahunFilter, fn($query) => $query->where('tahun', $this->tahunFilter))
            ->when($this->bulanFilter, fn($query) => $query->where('id_bulan', $this->bulanFilter))
            ->when($this->wilayahFilter, fn($query) => $query->where('id_wilayah', $this->wilayahFilter))
            ->when($this->variabelFilter, fn($query) => $query->where('id_variabel', $this->variabelFilter))
            ->when($this->klasifikasiFilter, fn($query) => $query->where('id_klasifikasi', $this->klasifikasiFilter))
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->sortBy, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDir);
            })
            ->chunk($chunkSize, function ($chunk) use (&$allData, &$totalProcessed, $chunkSize) {
                $chunkData = $chunk->toArray();
                $allData = array_merge($allData, $chunkData);
                $totalProcessed += count($chunkData);

                // Log::info("Processed chunk of " . count($chunkData) . " records. Total: {$totalProcessed}");

                // Stop if we reach a reasonable limit to prevent memory issues
                if ($totalProcessed >= 10000) {
                    // Log::warning('Reached chunk processing limit of 10000 records');
                    return false; // Stop chunking
                }
            });

        // Log::info('Print All Chunked - Final Count: ' . count($allData));

        if (count($allData) >= 10000) {
            // Log::warning('Print All Chunked reached limit of 10000 records. Consider using more specific filters.');
        }

        $this->dispatch('print-all-benih-pupuk', data: $allData);
    }
}
