<?php

namespace App\Exports;

use App\Models\Collector;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollectorsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Collector::with('user')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'الهاتف',
            'المنطقة',
            'تاريخ الإضافة',
        ];
    }

    public function map($collector): array
    {
        return [
            $collector->name,
            $collector->phone,
            $collector->area,
            $collector->created_at->format('Y-m-d'),
        ];
    }
}
