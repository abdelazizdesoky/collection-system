@extends('layouts.app')

@section('title', 'لوحة التحكم - الإدارة')

@section('content')
<div class="max-w-7xl mx-auto text-right" dir="rtl">
    <!-- Welcome Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1">مرحباً، {{ auth()->user()->name }}</h1>
                    <p class="text-blue-100 text-lg opacity-90">لوحة تحكم النظام - نظرة عامة شاملة على جميع العمليات</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('collections.create') }}" class="bg-white dark:bg-blue-600 text-blue-600 dark:text-white hover:bg-blue-50 dark:hover:bg-blue-700 px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    تحصيل جديد
                </a>
                <a href="{{ route('collection-plans.create') }}" class="bg-blue-800 text-white hover:bg-blue-900 px-6 py-3 rounded-xl font-bold border border-blue-400/30 transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    إنشاء خطة
                </a>
            </div>
        </div>
        <!-- Decorative subtle background arcs -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -top-10 w-48 h-48 bg-blue-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Customers -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-dark-border p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col items-center text-center">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-xl text-blue-600 dark:text-blue-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1">{{ $totalCustomers }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-medium">إجمالي العملاء</div>
                <a href="{{ route('customers.index') }}" class="mt-4 text-blue-600 font-bold text-sm hover:underline">عرض الكل ←</a>
            </div>
        </div>

        <!-- Total Collectors -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-dark-border p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col items-center text-center">
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-3 rounded-xl text-emerald-600 dark:text-emerald-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1">{{ $totalCollectors }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-medium">إجمالي المحصلون</div>
                <a href="{{ route('collectors.index') }}" class="mt-4 text-emerald-600 font-bold text-sm hover:underline">إدارة المحصلين ←</a>
            </div>
        </div>

        <!-- Total Collections -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-dark-border p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col items-center text-center">
                <div class="bg-amber-50 dark:bg-amber-900/20 p-3 rounded-xl text-amber-600 dark:text-amber-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-2xl lg:text-3xl font-black text-gray-800 dark:text-white mb-1">ج.م {{ number_format($totalCollections, 0) }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-medium">إجمالي التحصيلات</div>
                <a href="{{ route('collections.index') }}" class="mt-4 text-amber-600 font-bold text-sm hover:underline">السجل المالي ←</a>
            </div>
        </div>

        <!-- Pending Cheques -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-dark-border p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col items-center text-center">
                <div class="bg-rose-50 dark:bg-rose-900/20 p-3 rounded-xl text-rose-600 dark:text-rose-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1">{{ $pendingCheques }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-medium">شيكات معلقة</div>
                <a href="{{ route('cheques.index') }}" class="mt-4 text-rose-600 font-bold text-sm hover:underline">مراجعة الشيكات ←</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Recent Collections Table Card -->
        <div class="lg:col-span-2 bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">آخر التحصيلات</h2>
                <a href="{{ route('collections.index') }}" class="text-blue-600 text-sm font-bold hover:underline">المزيد</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-500 text-sm">
                            <th class="px-6 py-4 font-bold">رقم الإيصال</th>
                            <th class="px-6 py-4 font-bold">العميل</th>
                            <th class="px-6 py-4 font-bold">المحصل</th>
                            <th class="px-6 py-4 font-bold">المبلغ</th>
                            <th class="px-6 py-4 font-bold">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                        @forelse($recentCollections as $collection)
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/20 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-700 font-bold px-3 py-1 rounded-lg text-xs group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                        {{ $collection->receipt_no }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800 dark:text-gray-200">{{ $collection->customer->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $collection->collector->name }}</td>
                                <td class="px-6 py-4 text-sm font-black text-emerald-600 dark:text-emerald-400">
                                    <div class="flex items-center justify-between">
                                        <span>ج.م {{ number_format($collection->amount, 0) }}</span>
                                        @if($collection->attachment)
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="توجد صورة مرفقة">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400 text-left">{{ $collection->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">لا توجد عمليات تحصيل مسجلة مؤخراً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Collectors Card -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border bg-gray-50/50 dark:bg-gray-800/20 flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">أداء المحصلين</h2>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-dark-border py-2">
                @forelse($topCollectors as $collector)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="bg-blue-100 text-blue-700 w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm">
                                {{ mb_substr($collector->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $collector->name }}</p>
                                <p class="text-xs text-gray-400">{{ $collector->phone }}</p>
                            </div>
                        </div>
                        <div class="text-left font-black text-blue-600 text-sm">
                            ج.م {{ number_format($collector->collections_sum_amount ?? 0, 0) }}
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-400 font-medium">لا توجد بيانات متاحة</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Active Plans Section -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-dark-border overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800/20 dark:to-dark-card">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 p-2 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">خطط التحصيل النشطة</h2>
            </div>
            <div class="text-sm font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-4 py-1 rounded-full">
                إجمالي الملفات: {{ $totalCollectionPlanItems ?? 0 }}
            </div>
        </div>

        @if($activePlans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/40 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-black">الخطة</th>
                            <th class="px-6 py-4 font-black">المحصل</th>
                            <th class="px-6 py-4 font-black">التاريخ</th>
                            <th class="px-6 py-4 font-black">الإنجاز</th>
                            <th class="px-6 py-4 font-black text-left">المتوقع / المحصل</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                        @foreach($activePlans as $plan)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="font-black text-gray-900 dark:text-gray-100">{{ $plan->name }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1 uppercase">{{ $plan->collection_type === 'special' ? 'خطة خاصة' : 'تحصيل عادي' }}</div>
                                </td>
                                <td class="px-6 py-5 font-bold text-blue-600">{{ $plan->collector->name }}</td>
                                <td class="px-6 py-5 text-sm text-gray-500">{{ $plan->date->format('Y-m-d') }}</td>
                                <td class="px-6 py-5 min-w-[200px]">
                                    @php $progress = $plan->getProgressPercentage(); @endphp
                                    <div class="flex flex-col gap-2">
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-black {{ $progress >= 100 ? 'text-emerald-600' : 'text-blue-600' }}">{{ $progress }}% مكتمل</span>
                                            <span class="text-gray-400">{{ $plan->items->whereNotNull('collection_id')->count() }} من {{ $plan->items->count() }} عميل</span>
                                        </div>
                                        <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500 bg-gradient-to-r {{ $progress >= 100 ? 'from-emerald-500 to-teal-400' : 'from-blue-600 to-indigo-500' }}" 
                                                 style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-left">
                                    <div class="font-black text-gray-800 dark:text-gray-200">ج.م {{ number_format($plan->getTotalCollectedAmount(), 0) }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">من ج.م {{ number_format($plan->getTotalExpectedAmount(), 0) }} متوقع</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-16 text-center text-gray-400 font-medium">
                <p class="text-lg">لا توجد خطط نشطة في الوقت الحالي</p>
                <a href="{{ route('collection-plans.create') }}" class="text-blue-600 hover:underline mt-4 inline-block font-bold">ابدأ بإنشاء أول خطة الآن ←</a>
            </div>
        @endif
    </div>

    <!-- Users Management Grid -->
    @role('admin')
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/20">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">إدارة مستخدمي النظام</h2>
            <div class="flex items-center gap-3">
                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-bold">{{ $totalUsers }} مستخدم</span>
                <a href="{{ route('users.index') }}" class="text-blue-600 dark:text-blue-400 text-sm font-bold hover:underline">إدارة الكل</a>
                <a href="{{ route('admin.audit-logs.index') }}" class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-3 py-1 rounded-full text-xs font-bold hover:bg-amber-200 transition-colors">مراقبة النظام</a>
            </div>
        </div>

        @if ($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs">
                            <th class="px-6 py-4 font-black">المستخدم</th>
                            <th class="px-6 py-4 font-black">الصلاحيات</th>
                            <th class="px-6 py-4 font-black">الحالة</th>
                            <th class="px-6 py-4 font-black">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase
                                                @if($role->name === 'admin') bg-rose-100 text-rose-700
                                                @elseif($role->name === 'supervisor') bg-indigo-100 text-indigo-700
                                                @else bg-blue-100 text-blue-700
                                                @endif">
                                                {{ $role->name === 'admin' ? 'مدير' : ($role->name === 'supervisor' ? 'مشرف' : 'محصل') }}
                                            </span>
                                        @endforeach
                                        @if($user->roles->isEmpty())
                                            <span class="text-xs text-gray-400 italic">بدون صلاحيات</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full ml-1.5 underline-offset-4 animate-pulse"></span>
                                        نشط حالياً
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <button onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}')" class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                                        تعديل الأدوار
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-400 font-medium italic">
                لا يوجد مستخدمين متاحين حالياً
            </div>
        @endif
    </div>
    @endrole

    <!-- Overdue Cheques Floating Alert -->
    @role('admin|supervisor')
    @if($overdueCheques->count() > 0)
        <div class="mt-8 bg-rose-50 border border-rose-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="bg-rose-500 p-3 rounded-2xl text-white shadow-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-black text-rose-900 mb-2">تنبيه: شيكات متأخرة!</h3>
                    <p class="text-rose-700 mb-4 font-medium">يوجد عدد {{ $overdueCheques->count() }} شيكات تجاوزت تاريخ الاستحقاق وتطلب اتخاذ إجراء فوري.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($overdueCheques->take(6) as $cheque)
                            <a href="{{ route('cheques.show', $cheque) }}" class="bg-white border border-rose-100 p-3 rounded-xl hover:shadow-md transition-shadow flex justify-between items-center group">
                                <div class="font-bold text-gray-800 text-sm group-hover:text-rose-600">{{ $cheque->customer->name }}</div>
                                <div class="text-rose-600 font-black text-xs">ج.م {{ number_format($cheque->amount, 0) }}</div>
                            </a>
                        @endforeach
                    </div>
                    @if($overdueCheques->count() > 6)
                        <a href="{{ route('cheques.index') }}?status=overdue" class="mt-4 inline-block text-rose-600 font-bold text-sm hover:underline">عرض جميع الشيكات المتأخرة ←</a>
                    @endif
                </div>
            </div>
        </div>
    @endif
    @endrole
</div>

<!-- Role Management Modal -->
@role('admin')
<div id="roleModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all scale-100">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-8 py-6 text-white text-right">
            <h3 class="text-2xl font-black">تحديث الصلاحيات</h3>
            <p class="text-blue-100 opacity-80 mt-1" id="userName"></p>
        </div>
        <form id="roleForm" method="POST" action="" class="p-8">
            @csrf
            @method('PUT')
            <div class="space-y-4 mb-8">
                <label class="flex items-center p-4 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-blue-500 transition-all group has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                    <input type="checkbox" name="roles[]" value="admin" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="mr-4">
                        <span class="block font-black text-gray-800 group-hover:text-blue-700">مدير نظام</span>
                        <span class="text-xs text-gray-400">صلاحيات كاملة لجميع أقسام النظام</span>
                    </div>
                </label>
                <label class="flex items-center p-4 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-blue-500 transition-all group has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                    <input type="checkbox" name="roles[]" value="supervisor" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="mr-4">
                        <span class="block font-black text-gray-800 group-hover:text-blue-700">مشرف عمليات</span>
                        <span class="text-xs text-gray-400">إدارة الخطط والتحصيلات والمراجعة</span>
                    </div>
                </label>
                <label class="flex items-center p-4 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-blue-500 transition-all group has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                    <input type="checkbox" name="roles[]" value="collector" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="mr-4">
                        <span class="block font-black text-gray-800 group-hover:text-blue-700">محصل ميداني</span>
                        <span class="text-xs text-gray-400">صلاحيات مقتصرة على تسجيل التحصيلات</span>
                    </div>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-lg transition-all transform hover:scale-[1.02]">
                    حفظ التغييرات
                </button>
                <button type="button" onclick="closeRoleModal()" class="px-8 bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 py-4 rounded-2xl transition-all">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRoleModal(userId, userName) {
        document.getElementById('userName').textContent = 'المستخدم: ' + userName;
        const form = document.getElementById('roleForm');
        form.action = `/users/${userId}/roles`;
        document.getElementById('roleModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endrole
@endsection
