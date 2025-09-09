<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    public function __construct(
        protected ?string $search = null
    ) {}

    public function collection(): Collection
    {
        return User::with('roles')
            ->when($this->search, function($q){
                $q->where(function($qq){
                    $search = $this->search;
                    $qq->where('name', 'like', "%$search%")
                       ->orWhere('email', 'like', "%$search%") ;
                });
            })
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nama', 'Email', 'Role', 'Tanggal Bergabung'];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->roles->pluck('name')->implode(', '),
            $user->created_at ? ExcelDate::dateTimeToExcel($user->created_at) : null,
        ];
    }

    public function columnFormats(): array
    {
        // Kolom E hanya tanggal (tanpa waktu)
        return [
            'E' => 'yyyy-mm-dd',
        ];
    }
}
