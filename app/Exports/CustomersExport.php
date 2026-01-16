<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Customer::latest()->get();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'الهاتف',
            'العنوان',
            'الرصيد الافتتاحي',
            'نوع الرصيد',
            'تاريخ الإضافة',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->name,
            $customer->phone,
            $customer->address,
            number_format($customer->opening_balance, 2),
            $customer->balance_type === 'debit' ? 'مدين' : 'دائن',
            $customer->created_at->format('Y-m-d'),
        ];
    }
}
