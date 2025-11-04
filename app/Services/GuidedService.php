<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * GuidedService: backend-driven guided flow for the Pertanian chatbot.
 * Produces simple JSON-like arrays: [ 'message' => string, 'options' => [ {label,value,type}, ... ] ]
 * so the frontend can render bubbles (options/checklists) without hardcoding datasets.
 */
class GuidedService
{
    /**
     * Determine next step payload for guided conversation.
     * $currentStep: start | module | region | year | confirm
     * $context: associative array carrying selections so far (e.g., ['module' => 'lahan', 'provinsi_ids' => [33]])
     */
    public function getStep(string $currentStep, array $context = []): array
    {
        $step = strtolower(trim($currentStep ?: 'start'));

        switch ($step) {
            case 'start':
            case 'module':
                return $this->buildResponse('Silakan pilih modul data yang ingin Anda lihat.', $this->getModuleOptions());

            case 'region':
                $module = isset($context['module']) ? (string)$context['module'] : null;
                $regions = $this->getRegionOptions($module);
                return $this->buildResponse('Pilih wilayah (Provinsi) terlebih dahulu.', $regions);

            case 'year':
                $years = $this->getYearOptions();
                return $this->buildResponse('Pilih tahun data yang ingin ditampilkan.', $years);

            case 'confirm':
                $module = (string)($context['module'] ?? '');
                $provCount = is_array($context['provinsi_ids'] ?? null) ? count($context['provinsi_ids']) : 0;
                $kabCount = is_array($context['kabupaten_ids'] ?? null) ? count($context['kabupaten_ids']) : 0;
                $years = is_array($context['tahun_ids'] ?? null) ? $context['tahun_ids'] : [];
                $labelModule = $this->moduleLabel($module);
                $yrText = !empty($years) ? implode(', ', array_map('strval', $years)) : 'tahun terbaru';
                $wilText = $provCount + $kabCount > 0 ? ($provCount + $kabCount).' wilayah' : 'belum ada wilayah';
                $msg = "Tampilkan data {$labelModule} untuk {$wilText} pada {$yrText}?";
                return $this->buildResponse($msg, [
                    ['label' => 'Tampilkan', 'value' => 'confirm_show', 'type' => 'guided'],
                    ['label' => 'Ubah Pilihan', 'value' => 'back', 'type' => 'guided'],
                ]);

            default:
                return $this->buildResponse('Silakan pilih modul data yang ingin Anda lihat.', $this->getModuleOptions());
        }
    }

    /** Return module choices. Keep values consistent with existing moduleType slugs. */
    private function getModuleOptions(): array
    {
        return [
            ['label' => 'Lahan', 'value' => 'lahan', 'type' => 'guided'],
            ['label' => 'Benih & Pupuk', 'value' => 'benih-pupuk', 'type' => 'guided'],
            ['label' => 'Iklim & OPT DPI', 'value' => 'iklim-opt-dpi', 'type' => 'guided'],
        ];
    }

    /**
     * Return province options. If a module is specified, future enhancement can filter provinces with available data.
     */
    private function getRegionOptions(?string $module = null): array
    {
        try {
            $rs = new ReportService();
            $provs = $rs->getProvinces();
            $options = [];
            foreach ($provs as $p) {
                $options[] = [ 'label' => (string)$p->nama, 'value' => (string)$p->id, 'type' => 'guided' ];
            }
            return $options;
        } catch (\Throwable $e) {
            try { Log::warning('GuidedService getRegionOptions failed', ['err' => $e->getMessage()]); } catch (\Throwable $ee) {}
            return [];
        }
    }

    /** Aggregate years across all modules so guided flow can offer a unified year list. */
    private function getYearOptions(): array
    {
        $modules = ['lahan','benih-pupuk','iklim-opt-dpi'];
        $years = [];
        try {
            $rs = new ReportService();
            foreach ($modules as $m) {
                try {
                    $ys = $rs->getAvailableYears($m);
                    foreach ($ys as $y) { if (is_numeric($y)) { $years[(int)$y] = true; } }
                } catch (\Throwable $e) { /* skip per module */ }
            }
        } catch (\Throwable $e) { /* noop */ }
        $list = array_keys($years);
        sort($list, SORT_NUMERIC);
        return array_map(fn($y) => ['label' => (string)$y, 'value' => (string)$y, 'type' => 'guided'], $list);
    }

    /** Build the standard chatbot response shape for guided prompts. */
    private function buildResponse(string $prompt, array $options): array
    {
        return [
            'message' => (string)$prompt,
            'options' => array_values($options),
        ];
    }

    private function moduleLabel(?string $slug): string
    {
        $m = strtolower((string)$slug);
        return $m === 'benih-pupuk' ? 'Benih & Pupuk' : ($m === 'iklim-opt-dpi' ? 'Iklim & OPT DPI' : 'Lahan');
    }
}
