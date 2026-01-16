<?php

namespace App\Exports;

use App\Models\CollectionPlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollectionPlansExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return CollectionPlan::with('collector')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'اسم الخطة',
            'المحصل',
            'التاريخ',
            'نوع الخطة',
            'الإنجاز (%)',
            'المبلغ المحصل',
            'المبلغ المتوقع',
        ];
    }

    public function map($plan): array
    {
        $types = [
            'regular' => 'عادي',
            'special' => 'خاص',
            'bank' => 'بنكي',
            'cash' => 'نقدي',
            'cheque' => 'شيك',
        ];

        return [
            $plan->name,
            $plan->collector->name,
            $plan->date->format('Y-m-d'),
            $types[$plan->collection_type] ?? $plan->collection_type,
            $plan->getProgressPercentage() . '%',
            number_format($plan->getTotalCollectedAmount(), 2),
            number_format($plan->getTotalExpectedAmount(), 2),
        ];
    }
}
