<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\CollectionPlan;
use App\Models\CollectionPlanItem;
use App\Models\Collector;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========================================
        // 0. Ensure roles exist
        // ========================================
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'supervisor']);
        Role::firstOrCreate(['name' => 'collector']);

        // ========================================
        // 1. Create Admin User
        // ========================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('password'),
            ]
        );
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
        $this->command->info('✓ Admin: admin@admin.com / password');

        // ========================================
        // 2. Create Supervisor User
        // ========================================
        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@test.com'],
            [
                'name' => 'أحمد المشرف',
                'password' => Hash::make('password'),
            ]
        );
        if (! $supervisor->hasRole('supervisor')) {
            $supervisor->assignRole('supervisor');
        }
        $this->command->info('✓ Supervisor: supervisor@test.com / password');

        // ========================================
        // 3. Create Collectors (without 'name')
        // ========================================
        $collector1 = Collector::firstOrCreate(
            ['phone' => '01000000001'],
            [
                'area' => 'القاهرة',
            ]
        );

        $collector2 = Collector::firstOrCreate(
            ['phone' => '01000000002'],
            [
                'area' => 'الجيزة',
            ]
        );

        // ========================================
        // 4. Create Collector Users
        // ========================================
        $collectorUser1 = User::firstOrCreate(
            ['email' => 'collector1@test.com'],
            [
                'name' => 'محمد المحصل',
                'password' => Hash::make('password'),
                'collector_id' => $collector1->id,
            ]
        );
        if (! $collectorUser1->hasRole('collector')) {
            $collectorUser1->assignRole('collector');
        }
        $this->command->info('✓ Collector 1: collector1@test.com / password');

        $collectorUser2 = User::firstOrCreate(
            ['email' => 'collector2@test.com'],
            [
                'name' => 'علي المحصل',
                'password' => Hash::make('password'),
                'collector_id' => $collector2->id,
            ]
        );
        if (! $collectorUser2->hasRole('collector')) {
            $collectorUser2->assignRole('collector');
        }
        $this->command->info('✓ Collector 2: collector2@test.com / password');

        // ========================================
        // 5. Create Test Customers
        // ========================================
        $customers = [
            ['name' => 'شركة النور للتجارة', 'phone' => '01100000001', 'address' => 'شارع التحرير، القاهرة', 'opening_balance' => 5000.00, 'balance_type' => 'debit'],
            ['name' => 'مؤسسة الأمل', 'phone' => '01100000002', 'address' => 'المهندسين، الجيزة', 'opening_balance' => 3500.00, 'balance_type' => 'debit'],
            ['name' => 'شركة السلام', 'phone' => '01100000003', 'address' => 'مدينة نصر، القاهرة', 'opening_balance' => 7200.00, 'balance_type' => 'debit'],
            ['name' => 'مكتبة المعرفة', 'phone' => '01100000004', 'address' => 'العباسية، القاهرة', 'opening_balance' => 2800.00, 'balance_type' => 'debit'],
            ['name' => 'شركة البركة للمقاولات', 'phone' => '01100000005', 'address' => 'الدقي، الجيزة', 'opening_balance' => 15000.00, 'balance_type' => 'debit'],
            ['name' => 'سوبر ماركت الخير', 'phone' => '01100000006', 'address' => 'شبرا، القاهرة', 'opening_balance' => 4200.00, 'balance_type' => 'debit'],
            ['name' => 'صيدلية الشفاء', 'phone' => '01100000007', 'address' => 'حلوان، القاهرة', 'opening_balance' => 6500.00, 'balance_type' => 'debit'],
            ['name' => 'مطعم الطيب', 'phone' => '01100000008', 'address' => 'الهرم، الجيزة', 'opening_balance' => 1800.00, 'balance_type' => 'debit'],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $createdCustomers[] = Customer::firstOrCreate(
                ['phone' => $customerData['phone']],
                $customerData
            );
        }
        $this->command->info('✓ Created 8 test customers');

        // ========================================
        // 6. Create Collection Plans
        // ========================================
        $plan1 = CollectionPlan::firstOrCreate(
            [
                'collector_id' => $collector1->id,
                'date' => today(),
                'name' => 'خطة تحصيل القاهرة',
            ],
            [
                'collection_type' => 'daily',
                'type' => 'cash',
            ]
        );

        $plan1Customers = array_slice($createdCustomers, 0, 4);
        foreach ($plan1Customers as $index => $customer) {
            CollectionPlanItem::firstOrCreate(
                [
                    'collection_plan_id' => $plan1->id,
                    'customer_id' => $customer->id,
                ],
                [
                    'expected_amount' => $customer->opening_balance * 0.3,
                    'priority' => $index + 1,
                    'status' => 'pending',
                ]
            );
        }
        $this->command->info('✓ Created collection plan for Collector 1 with 4 customers');

        $plan2 = CollectionPlan::firstOrCreate(
            [
                'collector_id' => $collector2->id,
                'date' => today(),
                'name' => 'خطة تحصيل الجيزة',
            ],
            [
                'collection_type' => 'daily',
                'type' => 'cash',
            ]
        );

        $plan2Customers = array_slice($createdCustomers, 4, 4);
        foreach ($plan2Customers as $index => $customer) {
            CollectionPlanItem::firstOrCreate(
                [
                    'collection_plan_id' => $plan2->id,
                    'customer_id' => $customer->id,
                ],
                [
                    'expected_amount' => $customer->opening_balance * 0.3,
                    'priority' => $index + 1,
                    'status' => 'pending',
                ]
            );
        }
        $this->command->info('✓ Created collection plan for Collector 2 with 4 customers');

        // ========================================
        // 7. Summary
        // ========================================
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('  ✅ Test Data Created Successfully!');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->newLine();
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@admin.com', 'password'],
                ['Supervisor', 'supervisor@test.com', 'password'],
                ['Collector 1', 'collector1@test.com', 'password'],
                ['Collector 2', 'collector2@test.com', 'password'],
            ]
        );
    }
}
