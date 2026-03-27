@extends('layouts.collector')

@section('title', 'إجراء خارج الخطة - بوابة المندوب')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6" x-data="{ searchQuery: '', selectedCustomer: null }">
    <!-- Header -->
    <div class="mb-8 text-right">
        <h1 class="text-3xl font-black text-gray-900 mb-2">إجراء خارج الخطة</h1>
        <p class="text-gray-600">اختر عميلاً لبدء عملية بيع، تحصيل، أو زيارة جديدة غير مجدولة.</p>
    </div>

    <!-- Customer Search -->
    <div class="bg-white rounded-3xl shadow-xl p-6 mb-8 border border-gray-100">
        <label class="block text-sm font-bold text-gray-700 mb-2 mr-2">ابحث عن العميل بالاسم أو الكود</label>
        <div class="relative">
            <input 
                type="text" 
                x-model="searchQuery" 
                placeholder="ابدأ الكتابة للبحث..."
                class="w-full pl-4 pr-12 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all text-lg font-bold"
            >
            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <!-- Search Results -->
        <div class="mt-4 space-y-2 max-h-96 overflow-y-auto custom-scrollbar">
            @foreach($customers as $customer)
            <div 
                x-show="searchQuery === '' || '{{ addslashes($customer->name) }} {{ $customer->code }}'.toLowerCase().includes(searchQuery.toLowerCase())"
                @click="selectedCustomer = { id: {{ $customer->id }}, name: '{{ addslashes($customer->name) }}', code: '{{ $customer->code }}' }"
                class="p-4 rounded-2xl border-2 transition-all cursor-pointer flex justify-between items-center"
                :class="selectedCustomer && selectedCustomer.id === {{ $customer->id }} ? 'border-emerald-500 bg-emerald-50' : 'border-gray-100 hover:border-emerald-200 hover:bg-gray-50'"
            >
                <div class="text-right">
                    <div class="font-black text-gray-900" x-text="'{{ $customer->name }}'"></div>
                    <div class="text-xs font-bold text-gray-500" x-text="'كود: {{ $customer->code }}'"></div>
                </div>
                <div x-show="selectedCustomer && selectedCustomer.id === {{ $customer->id }}" class="text-emerald-500">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Actions Grid -->
    <div x-show="selectedCustomer" x-transition.duration.300ms class="space-y-4">
        <h2 class="text-xl font-black text-gray-900 mb-4 text-right">ماذا تريد أن تفعل لـ <span class="text-emerald-600" x-text="selectedCustomer.name"></span>؟</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Sale Action -->
            <a :href="'{{ route('collector.sale-invoice.create') }}/' + selectedCustomer.id + '?adhoc=1'" 
               class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 flex flex-col items-center text-center group hover:bg-emerald-600 transition-all duration-300">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-500 transition-colors">
                    <svg class="w-8 h-8 text-emerald-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <span class="font-black text-gray-900 group-hover:text-white">فاتورة بيع</span>
                <span class="text-xs text-gray-500 group-hover:text-emerald-100 mt-1">تسجيل فاتورة جديدة</span>
            </a>

            <!-- Collection Action -->
            <a :href="'{{ url('collector/adhoc/collect') }}/' + selectedCustomer.id" 
               class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 flex flex-col items-center text-center group hover:bg-blue-600 transition-all duration-300">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-500 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="font-black text-gray-900 group-hover:text-white">تحصيل نقدية</span>
                <span class="text-xs text-gray-500 group-hover:text-blue-100 mt-1">استلام دفعة من العميل</span>
            </a>

            <!-- Visit Action -->
            <a :href="'{{ url('collector/adhoc/visit') }}/' + selectedCustomer.id" 
               class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 flex flex-col items-center text-center group hover:bg-purple-600 transition-all duration-300">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-500 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <span class="font-black text-gray-900 group-hover:text-white">تسجيل زيارة</span>
                <span class="text-xs text-gray-500 group-hover:text-purple-100 mt-1">زيارة معاينة أو متابعة</span>
            </a>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
</style>
@endsection
