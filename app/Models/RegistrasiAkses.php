<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class RegistrasiAkses extends Model
{
    use HasFactory;

    protected $table = 'registrasi_akses';

    protected $fillable = [
        'nama_lengkap',
        'nip_nik',
        'email',
        'telepon',
        'tipe_akses',
        'instansi',
        'institusi',
        'jenjang_pendidikan',
        'program_studi',
        'jabatan',
        'jenis_dinas',
        'unit_kerja',
        'provinsi',
        'gelar_akademik',
        'bidang_keahlian',
        'jenis_penelitian',
        'tujuan_penggunaan',
        'deskripsi_kebutuhan',
        'surat_permohonan',
        'id_instansi',
        'surat_atasan',
        'surat_keterangan_institusi',
        'proposal_penelitian',
        'dokumen_uploaded_at',
        'status',
        'catatan_admin',
        'tanggal_review',
        'tanggal_approval',
        'reviewed_by'
    ];

    protected $casts = [
        'tujuan_penggunaan' => 'array',
        'tanggal_review' => 'datetime',
        'tanggal_approval' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEW = 'review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_NEED_DOCUMENTS = 'need_documents';

    // Accessors
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_REVIEW => 'Sedang Review',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_NEED_DOCUMENTS => 'Butuh Dokumen'
        ];

        return $statusLabels[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        $statusColors = [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::STATUS_REVIEW => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            self::STATUS_NEED_DOCUMENTS => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300'
        ];

        return $statusColors[$this->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
    }

    public function getTipeAksesLabelAttribute()
    {
        return $this->tipe_akses === 'pemerintah' ? 'Instansi Pemerintah' : 'Akademisi/Peneliti';
    }

    // Relationships
    public function reviewer()
    {
        return $this->belongsTo(\App\Models\User::class, 'reviewed_by');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePemerintah($query)
    {
        return $query->where('tipe_akses', 'pemerintah');
    }

    public function scopeAkademisi($query)
    {
        return $query->where('tipe_akses', 'akademisi');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Methods
    public function getStatusColor()
    {
        return $this->status_color;
    }

    public function getStatusLabel()
    {
        return $this->status_label;
    }

    public function approve($adminId = null, $catatan = null)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'tanggal_approval' => now(),
            'reviewed_by' => $adminId,
            'catatan_admin' => $catatan
        ]);
    }

    public function reject($adminId = null, $catatan = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'tanggal_review' => now(),
            'reviewed_by' => $adminId,
            'catatan_admin' => $catatan
        ]);
    }

    public function needDocuments($adminId = null, $catatan = null)
    {
        $this->update([
            'status' => self::STATUS_NEED_DOCUMENTS,
            'tanggal_review' => now(),
            'reviewed_by' => $adminId,
            'catatan_admin' => $catatan
        ]);
    }
}
