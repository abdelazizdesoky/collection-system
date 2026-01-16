<?php

namespace App\Exports;

use App\Models\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollectionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Collection::with(['customer', 'collector'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'رقم الإيصال',
            'العميل',
            'المحصل',
            'المبلغ',
            'نوع الدفع',
            'البنك',
            'رقم المرجع',
            'التاريخ',
            'ملاحظات',
        ];
    }

    public function map($collection): array
    {
        $payment_types = [
            'cash' => 'نقدي',
            'cheque' => 'شيك',
            'bank_transfer' => 'تحويل بنكي',
        ];

        return [
            $collection->receipt_no,
            $collection->customer->name,
            $collection->collector->name,
            number_format($collection->amount, 2),
            $payment_types[$collection->payment_type] ?? $collection->payment_type,
            $collection->bank_name ?? '-',
            $collection->reference_no ?? '-',
            $collection->collection_date->format('Y-m-d'),
            $collection->notes,
        ];
    }
}
