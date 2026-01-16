<?php

namespace App\Exports;

use App\Models\Cheque;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ChequesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Cheque::with('customer')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'رقم الشيك',
            'العميل',
            'البنك',
            'المبلغ',
            'تاريخ الاستحقاق',
            'الحالة',
        ];
    }

    public function map($cheque): array
    {
        $statuses = [
            'pending' => 'معلق',
            'cleared' => 'تم التحصيل',
            'bounced' => 'مرفوض',
        ];

        return [
            $cheque->cheque_no,
            $cheque->customer->name,
            $cheque->bank_name,
            number_format($cheque->amount, 2),
            $cheque->due_date->format('Y-m-d'),
            $statuses[$cheque->status] ?? $cheque->status,
        ];
    }
}
