<?php

namespace App\Services;

/**
 * KnowledgeBaseService (scaffold)
 * - Provides short domain definitions and explanations for core datasets.
 * - Keep responses concise and user-facing (no internal jargon).
 */
class KnowledgeBaseService
{
    /** Return a concise definition or null if not recognized. */
    public function answer(string $query): ?string
    {
        $q = trim($query ?? '');
        $l = mb_strtolower($q, 'UTF-8');

        if (preg_match('/\b(lahan|sawah|bukan sawah)\b/u', $l)) {
            return 'Data Lahan berisi luas "Sawah" dan "Bukan Sawah" per wilayah dan tahun.';
        }
        if (preg_match('/\b(benih|pupuk)\b/u', $l)) {
            return 'Data Benih & Pupuk mencakup topik Benih dan Pupuk dengan variabel, klasifikasi, wilayah, bulan, dan tahun.';
        }
        if (preg_match('/\b(iklim|curah hujan|suhu|kelembaban|penyinaran|opt|dpi|banjir|kekeringan|puso)\b/u', $l)) {
            return 'Data Iklim & OPT DPI meliputi indikator Iklim (curah hujan, suhu, dll) serta kerusakan tanaman (DPI) per wilayah dan waktu.';
        }
        if (preg_match('/\b(klasifikasi)\b/u', $l)) {
            return 'Klasifikasi adalah kategori di bawah suatu variabel (misalnya jenis benih atau jenis pupuk) untuk memperjelas rincian data.';
        }
        return null;
    }
}
