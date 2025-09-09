<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BenihPupukExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $headers;
    protected $rows;
    protected $firstColumnHeader;

    public function __construct(array $headers, array $rows, string $firstColumnHeader)
    {
        $this->headers = $headers;
        $this->rows = $rows;
        $this->firstColumnHeader = $firstColumnHeader;
    }

    public function collection()
    {
        return collect($this->rows)->map(function ($row) {
            $values = collect($row['values'])->map(function ($value) {
                return $value !== null ? number_format($value, 2, ',', '.') : '-';
            })->all();
            return array_merge([$row['label']], $values);
        });
    }

    public function headings(): array
    {
        return array_merge([$this->firstColumnHeader], $this->headers);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}

