<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PertanianReportPage extends Component
{
    public function __construct(
        public string $title,
        public string $description,
        public string $moduleType,
        public array $initialData
    ) {}

    public function render()
    {
        return view('components.pertanian-report-page');
    }
}
