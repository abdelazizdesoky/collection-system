<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Collector;
use App\Models\Collection;
use App\Models\Cheque;
use App\Models\CollectionPlan;
use App\Models\CollectionPlanItem;
use App\Models\CustomerAccount;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('collector')) {
            return $this->collectorDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function adminDashboard(): View
    {
        $totalCustomers = Customer::count();
        $totalCollectors = Collector::count();
        $totalCollections = Collection::sum('amount') ?? 0;
        $totalCheques = Cheque::sum('amount') ?? 0;
        $pendingCheques = Cheque::where('status', 'pending')->count();
        $totalCollectionPlans = CollectionPlan::count();
        $totalCollectionPlanItems = CollectionPlanItem::count();
        $totalCustomerAccounts = CustomerAccount::count();
        $totalUsers = User::count();

        // Recent collections
        $recentCollections = Collection::with(['customer', 'collector'])
            ->latest()
            ->take(10)
            ->get();

        // Overdue cheques
        $overdueCheques = Cheque::where('status', 'pending')
            ->whereDate('due_date', '<', now())
            ->with('customer')
            ->take(10)
            ->get();

        // Top collectors by collection amount
        $topCollectors = Collector::withSum('collections', 'amount')
            ->orderByDesc('collections_sum_amount')
            ->take(5)
            ->get();

        // Active collection plans
        $activePlans = CollectionPlan::with(['collector', 'items.customer'])
            ->latest()
            ->take(10)
            ->get();

        // All users with their roles
        $users = User::with('roles')
            ->latest()
            ->get();

        return view('dashboards.admin', [
            'totalCustomers' => $totalCustomers,
            'totalCollectors' => $totalCollectors,
            'totalCollections' => $totalCollections,
            'totalCheques' => $totalCheques,
            'pendingCheques' => $pendingCheques,
            'totalCollectionPlans' => $totalCollectionPlans,
            'totalCollectionPlanItems' => $totalCollectionPlanItems,
            'totalCustomerAccounts' => $totalCustomerAccounts,
            'totalUsers' => $totalUsers,
            'recentCollections' => $recentCollections,
            'overdueCheques' => $overdueCheques,
            'topCollectors' => $topCollectors,
            'activePlans' => $activePlans,
            'users' => $users,
        ]);
    }

    private function collectorDashboard(): View
    {
        $collector = auth()->user()->collector;

        if (!$collector) {
            return view('dashboards.user');
        }

        $assignedCustomers = $collector->collectionPlans()
            ->with('items.customer')
            ->get()
            ->pluck('items')
            ->flatten()
            ->pluck('customer')
            ->unique('id');

        $collectionsToday = Collection::where('collector_id', $collector->id)
            ->whereDate('created_at', now())
            ->sum('amount') ?? 0;

        $collectionsThisMonth = Collection::where('collector_id', $collector->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount') ?? 0;

        $totalCollections = Collection::where('collector_id', $collector->id)
            ->sum('amount') ?? 0;

        $activePlans = CollectionPlan::where('collector_id', $collector->id)
            ->with('items.customer')
            ->get();

        $recentCollections = Collection::where('collector_id', $collector->id)
            ->with('customer')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboards.collector', [
            'collector' => $collector,
            'assignedCustomers' => $assignedCustomers,
            'collectionsToday' => $collectionsToday,
            'collectionsThisMonth' => $collectionsThisMonth,
            'totalCollections' => $totalCollections,
            'activePlans' => $activePlans,
            'recentCollections' => $recentCollections,
        ]);
    }

    private function userDashboard(): View
    {
        return view('dashboards.user', [
            'message' => 'Welcome to the Collection System',
        ]);
    }
}
