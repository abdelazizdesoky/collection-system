<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Bank;
use App\Models\Collection;
use App\Models\CollectionPlan;
use App\Models\CollectionPlanItem;
use App\Models\Collector;
use App\Models\Customer;
use App\Models\User;
use App\Models\VisitPlan;
use App\Models\VisitPlanItem;
use App\Models\VisitType;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

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
        Role::firstOrCreate(['name' => 'plan_supervisor']);
        Role::firstOrCreate(['name' => 'accountant']);
        Role::firstOrCreate(['name' => 'collector']);
        Role::firstOrCreate(['name' => 'user']);

        $this->command->info('✓ Roles ensured: admin, supervisor, plan_supervisor, accountant, collector, user');

        // ========================================
        // 1. Company Settings (Merged from SettingSeeder)
        // ========================================
        $settings = [
            'company_name' => ' متجرى',
            'company_activity' => ' متجرى للخدمات',
            'company_phone' => '01000000000',
            'company_address' => 'القاهرة، مصر',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        $this->command->info('✓ Company settings seeded.');

        // ========================================
        // 2. Create Areas (Egyptian Governorates)
        // ========================================
        $governorates = [
            'القاهرة', 'الجيزة', 'الإسكندرية', 'القليوبية', 'الدقهلية', 
            'الشرقية', 'المنوفية', 'الغربية', 'البحيرة', 'الفيوم', 
            'بني سويف', 'المنيا', 'أسيوط', 'سوهاج', 'قنا', 'الأقصر', 
            'أسوان', 'بورسعيد', 'الإسماعيلية', 'السويس', 'دمياط', 
            'كفر الشيخ', 'مطروح', 'الوادي الجديد', 'البحر الأحمر', 
            'شمال سيناء', 'جنوب سيناء'
        ];

        $areas = [];
        foreach ($governorates as $gov) {
            $areas[] = Area::withTrashed()->updateOrCreate(['name' => $gov], ['deleted_at' => null]);
        }
        $this->command->info('✓ Created/Restored all Egyptian governorates as areas.');

        // ========================================
        // 3. Create Banks & Wallets & Apps (Comprehensive List)
        // ========================================
        $banksData = [
            // Major Banks
            ['name' => 'البنك الأهلي المصري', 'type' => 'bank'],
            ['name' => 'بنك مصر', 'type' => 'bank'],
            ['name' => 'البنك التجاري الدولي (CIB)', 'type' => 'bank'],
            ['name' => 'بنك قطر الوطني الأهلي (QNB)', 'type' => 'bank'],
            ['name' => 'مصرف أبوظبي الإسلامي', 'type' => 'bank'],
            ['name' => 'بنك فيصل الإسلامي', 'type' => 'bank'],
            ['name' => 'بنك القاهرة', 'type' => 'bank'],
            ['name' => 'البنك العربي الإفريقي الدولي', 'type' => 'bank'],
            ['name' => 'بنك الإسكندرية', 'type' => 'bank'],
            ['name' => 'بنك التعمير والإسكان', 'type' => 'bank'],
            ['name' => 'البنك المصري الخليجي (EG Bank)', 'type' => 'bank'],
            ['name' => 'بنك البركة مصر', 'type' => 'bank'],
            ['name' => 'بنك الكويت الوطني (NBK)', 'type' => 'bank'],
            ['name' => 'كريدي أجريكول مصر', 'type' => 'bank'],
            ['name' => 'بنك إتش إس بي سي (HSBC)', 'type' => 'bank'],
            ['name' => 'المصرف المتحد', 'type' => 'bank'],
            ['name' => 'بنك قناة السويس', 'type' => 'bank'],
            ['name' => 'بنك سايب (saib)', 'type' => 'bank'],
            ['name' => 'ميد بنك (MIDBANK)', 'type' => 'bank'],
            ['name' => 'بنك الإمارات دبي الوطني', 'type' => 'bank'],
            ['name' => 'البنك المصري لتنمية الصادرات', 'type' => 'bank'],
            ['name' => 'التجاري وفا بنك مصر', 'type' => 'bank'],
            ['name' => 'البنك الأهلي الكويتي - مصر', 'type' => 'bank'],
            ['name' => 'بنك الاستثمار العربي (aiBANK)', 'type' => 'bank'],
            ['name' => 'بنك أبوظبي التجاري - مصر', 'type' => 'bank'],
            ['name' => 'بنك أبوظبي الأول (FAB)', 'type' => 'bank'],
            ['name' => 'بنك كريدى أجريكول', 'type' => 'bank'],
            ['name' => 'بنك ستاندرد تشارترد', 'type' => 'bank'],
            ['name' => 'بنك المؤسسة العربية المصرفية (ABC)', 'type' => 'bank'],
            ['name' => 'بنك ناصر الاجتماعي', 'type' => 'bank'],
            ['name' => 'البنك الزراعي المصري', 'type' => 'bank'],
            
            // Wallets
            ['name' => 'فودافون كاش', 'type' => 'wallet'],
            ['name' => 'اتصالات كاش', 'type' => 'wallet'],
            ['name' => 'أورنج كاش', 'type' => 'wallet'],
            ['name' => 'وي باي (WE Pay)', 'type' => 'wallet'],
            ['name' => 'فوري باي (FawryPay)', 'type' => 'wallet'],
            ['name' => 'أمان (Aman)', 'type' => 'wallet'],
            ['name' => 'بي تك (B.TECH)', 'type' => 'wallet'],
            ['name' => 'محفظة بنك مصر', 'type' => 'wallet'],
            ['name' => 'محفظة البنك الأهلي (NBE Phone)', 'type' => 'wallet'],
            ['name' => 'محفظة CIB Smart', 'type' => 'wallet'],
            ['name' => 'محفظة QNB', 'type' => 'wallet'],
            ['name' => 'محفظة بنك القاهرة', 'type' => 'wallet'],
            ['name' => 'محفظة بنك الإسكندرية', 'type' => 'wallet'],
            ['name' => 'محفظة بنك سايب (saib Wallet)', 'type' => 'wallet'],
            
            // Apps & Digital Finance
            ['name' => 'إنستا باي (InstaPay)', 'type' => 'app'],
            ['name' => 'تيلدا (Telda)', 'type' => 'app'],
            ['name' => 'كليفر (Klivvr)', 'type' => 'app'],
            ['name' => 'نيكستا (Nexta)', 'type' => 'app'],
            ['name' => 'لاكي (Lucky)', 'type' => 'app'],
            ['name' => 'ماي فوري (MyFawry)', 'type' => 'app'],
            ['name' => 'فاليو (valU)', 'type' => 'app'],
            ['name' => 'سهولة (Shahula)', 'type' => 'app'],
            ['name' => 'أمان للمدفوعات (Aman)', 'type' => 'app'],
            ['name' => 'بساطة (Basata)', 'type' => 'app'],
            ['name' => 'كاش ن كول (Cash n Call)', 'type' => 'app'],
        ];

        foreach ($banksData as $bank) {
            Bank::withTrashed()->updateOrCreate(['name' => $bank['name']], [
                'type' => $bank['type'],
                'deleted_at' => null
            ]);
        }
        $this->command->info('✓ Created/Restored all requested Egyptian banks, wallets, and apps.');

        // ========================================
        // 4. Create Visit Types
        // ========================================
        $visitTypes = [
            ['name' => 'collection', 'label' => 'تحصيل مالي', 'is_system' => true],
            ['name' => 'order', 'label' => 'طلب بضاعة', 'is_system' => true],
            ['name' => 'issue', 'label' => 'متابعة مشكلة', 'is_system' => true],
            ['name' => 'marketing', 'label' => 'زيارة ترويجية', 'is_system' => false],
            ['name' => 'delivery', 'label' => 'تسليم أوردر', 'is_system' => false],
        ];

        foreach ($visitTypes as $vt) {
            VisitType::withTrashed()->updateOrCreate(['name' => $vt['name']], [
                'label' => $vt['label'],
                'is_system' => $vt['is_system'],
                'deleted_at' => null
            ]);
        }
        $this->command->info('✓ Created/Restored standard visit types.');

        // ========================================
        // 5. Create Users for all Roles
        // ========================================
        
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'مدير النظام', 'password' => Hash::make('password')]
        );
        $admin->syncRoles(['admin']);

        // Supervisor
        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@test.com'],
            ['name' => 'أحمد المشرف', 'password' => Hash::make('password')]
        );
        $supervisor->syncRoles(['supervisor']);

        // Plan Supervisor
        $planSupervisor = User::firstOrCreate(
            ['email' => 'plan@test.com'],
            ['name' => 'إيهاب مسئول التخطيط', 'password' => Hash::make('password')]
        );
        $planSupervisor->syncRoles(['plan_supervisor']);

        // Accountant
        $accountant = User::firstOrCreate(
            ['email' => 'accountant@test.com'],
            ['name' => 'سارة المحاسبة', 'password' => Hash::make('password')]
        );
        $accountant->syncRoles(['accountant']);

        $this->command->info('✓ Created/Updated users for all roles.');

        // ========================================
        // 6. Create Collectors
        // ========================================
        $collectorsData = [
            ['code' => 'COL-001', 'name' => 'محمد كمال', 'phone' => '01012345678', 'email' => 'collector1@test.com', 'area' => 'القاهرة'], 
            ['code' => 'COL-002', 'name' => 'سيد علي', 'phone' => '01112345678', 'email' => 'collector2@test.com', 'area' => 'الجيزة'], 
            ['code' => 'COL-003', 'name' => 'إبراهيم حسن', 'phone' => '01212345678', 'email' => 'collector3@test.com', 'area' => 'الإسكندرية'], 
        ];

        $collectors = [];
        foreach ($collectorsData as $data) {
            $collector = Collector::withTrashed()->updateOrCreate(
                ['phone' => $data['phone']],
                [
                    'code' => $data['code'],
                    'area' => $data['area'],
                    'deleted_at' => null
                ]
            );
            
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'collector_id' => $collector->id,
                ]
            );
            $user->syncRoles(['collector']);
            $collectors[] = $collector;
        }
        $this->command->info('✓ Created/Restored collectors with unique codes.');

        // ========================================
        // 7. Create Test Customers
        // ========================================
        $customersList = [
            ['code' => 'CUST-0001', 'name' => 'شركة النور للتجارة', 'phone' => '01122334455', 'area_id' => $areas[0]->id, 'balance' => 50000],
            ['code' => 'CUST-0002', 'name' => 'مكتبة المعرفة', 'phone' => '01222334455', 'area_id' => $areas[0]->id, 'balance' => 12000],
            ['code' => 'CUST-0003', 'name' => 'سوير ماركت التحرير', 'phone' => '01022334455', 'area_id' => $areas[0]->id, 'balance' => 8500],
            ['code' => 'CUST-0004', 'name' => 'صيدلية العزبي - الجيزة', 'phone' => '01133445566', 'area_id' => $areas[1]->id, 'balance' => 35000],
            ['code' => 'CUST-0005', 'name' => 'حلواني العبد - المهندسين', 'phone' => '01233445566', 'area_id' => $areas[1]->id, 'balance' => 22000],
            ['code' => 'CUST-0006', 'name' => 'مطعم كوك دور - الإسكندرية', 'phone' => '01033445566', 'area_id' => $areas[2]->id, 'balance' => 45000],
            ['code' => 'CUST-0007', 'name' => 'معرض رنين - المنصورة', 'phone' => '01144556677', 'area_id' => $areas[4]->id, 'balance' => 95000],
            ['code' => 'CUST-0008', 'name' => 'توكيل توشيبا - الزقازيز', 'phone' => '01244556677', 'area_id' => $areas[5]->id, 'balance' => 120000],
            ['code' => 'CUST-0009', 'name' => 'مول العرب - 6 أكتوبر', 'phone' => '01044556677', 'area_id' => $areas[1]->id, 'balance' => 500000],
            ['code' => 'CUST-0010', 'name' => 'بقالة الأمل - طنطا', 'phone' => '01155667788', 'area_id' => $areas[7]->id, 'balance' => 5400],
        ];

        $createdCustomers = [];
        foreach ($customersList as $index => $cData) {
            $createdCustomers[] = Customer::withTrashed()->updateOrCreate(
                ['phone' => $cData['phone']],
                [
                    'code' => $cData['code'],
                    'name' => $cData['name'],
                    'address' => 'العنوان الافتراضي - ' . $cData['name'],
                    'opening_balance' => $cData['balance'],
                    'balance_type' => 'debit',
                    'area_id' => $cData['area_id'],
                    'collector_id' => $collectors[$index % count($collectors)]->id,
                    'deleted_at' => null
                ]
            );
        }
        $this->command->info('✓ Created/Restored 10 realistic customers with unique codes.');

        // ========================================
        // 8. Create Collection Plans
        // ========================================
        foreach ($collectors as $index => $collector) {
            $name = 'خطة تحصيل - ' . ($areas[$index]->name ?? 'منطقة') . ' - ' . today()->format('Y-m-d');
            $plan = CollectionPlan::withTrashed()->updateOrCreate(
                [
                    'collector_id' => $collector->id,
                    'date' => today(),
                    'name' => $name,
                ],
                [
                    'collection_type' => 'daily',
                    'type' => 'cash',
                    'status' => 'open', 
                    'deleted_at' => null
                ]
            );

            // Add 3 customers to each plan
            $collectorCustomers = Customer::where('collector_id', $collector->id)->limit(3)->get();
            foreach ($collectorCustomers as $cIndex => $customer) {
                CollectionPlanItem::withTrashed()->updateOrCreate(
                    [
                        'collection_plan_id' => $plan->id,
                        'customer_id' => $customer->id,
                    ],
                    [
                        'expected_amount' => $customer->opening_balance * 0.2,
                        'priority' => $cIndex + 1,
                        'status' => 'pending',
                        'deleted_at' => null
                    ]
                );
            }
        }
        $this->command->info('✓ Created/Restored collection plans.');

        // ========================================
        // 9. Create Visit Plans
        // ========================================
        foreach ($collectors as $index => $collector) {
            $vPlanName = 'خطة زيارات - ' . ($areas[$index]->name ?? 'منطقة') . ' - ' . today()->format('Y-m-d');
            $vPlan = VisitPlan::withTrashed()->updateOrCreate(
                [
                    'collector_id' => $collector->id,
                    'start_date' => today(),
                    'name' => $vPlanName,
                ],
                [
                    'end_date' => today(),
                    'frequency' => 'daily',
                    'status' => 'open',
                    'created_by' => $admin->id,
                    'deleted_at' => null
                ]
            );

            $collectorCustomers = Customer::where('collector_id', $collector->id)->limit(3)->get();
            foreach ($collectorCustomers as $cIndex => $customer) {
                VisitPlanItem::withTrashed()->updateOrCreate(
                    [
                        'visit_plan_id' => $vPlan->id,
                        'customer_id' => $customer->id,
                    ],
                    [
                        'priority' => $cIndex + 1,
                        'status' => 'pending',
                        'deleted_at' => null
                    ]
                );
            }
        }
        $this->command->info('✓ Created/Restored visit plans.');

        // ========================================
        // 10. Summary Table
        // ========================================
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('  🚀 Test Data Created Successfully!');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->newLine();
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@admin.com', 'password'],
                ['Supervisor', 'supervisor@test.com', 'password'],
                ['Plan Supervisor', 'plan@test.com', 'password'],
                ['Accountant', 'accountant@test.com', 'password'],
                ['Collector (Cairo)', 'collector1@test.com', 'password'],
                ['Collector (Giza)', 'collector2@test.com', 'password'],
            ]
        );
        $this->command->newLine();
    }
}
