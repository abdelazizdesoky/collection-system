<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::with('roles')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'البريد الإلكتروني',
            'الأدوار',
            'تاريخ التسجيل',
        ];
    }

    public function map($user): array
    {
        $roleNames = [
            'admin' => 'مدير',
            'supervisor' => 'مشرف',
            'collector' => 'محصل',
        ];

        $roles = $user->roles->map(fn($role) => $roleNames[$role->name] ?? $role->name)->implode(', ');

        return [
            $user->name,
            $user->email,
            $roles,
            $user->created_at->format('Y-m-d'),
        ];
    }
}
