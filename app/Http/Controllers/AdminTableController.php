<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTableController extends Controller
{
    /**
     * Display a generic table view for the given table slug.
     */
    public function show(Request $request, string $table): View
    {
        // Only admin can view this
        if (! auth()->user() || ! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        // Map table slug to model class and relations to eager load
        $map = [
            'customers' => [\App\Models\Customer::class, ['collections', 'cheques']],
            'collectors' => [\App\Models\Collector::class, ['user', 'collections']],
            'collections' => [\App\Models\Collection::class, ['customer', 'collector']],
            'cheques' => [\App\Models\Cheque::class, ['customer']],
            'collection-plans' => [\App\Models\CollectionPlan::class, ['collector', 'items.customer']],
            'collection-plan-items' => [\App\Models\CollectionPlanItem::class, ['customer', 'collectionPlan']],
            'customer-accounts' => [\App\Models\CustomerAccount::class, ['customer']],
            'users' => [\App\Models\User::class, ['roles', 'collector']],
        ];

        if (! isset($map[$table])) {
            abort(404);
        }

        [$modelClass, $relations] = $map[$table];

        // Fetch recent records (limit to 100 for performance)
        $query = $modelClass::query();

        if (! empty($relations)) {
            $query->with($relations);
        }

        $rows = $query->latest()->take(100)->get();

        // Build a set of column keys from the first row
        $columns = [];
        if ($rows->count() > 0) {
            $first = $rows->first()->toArray();
            // remove nested arrays that are relationships for display simplicity
            foreach ($first as $key => $value) {
                if (in_array($key, ['password', 'remember_token'])) continue;
                $columns[] = $key;
            }
        }

        return view('admin.tables.show', [
            'title' => ucwords(str_replace(['-', '_'], ' ', $table)),
            'table' => $table,
            'rows' => $rows,
            'columns' => $columns,
        ]);
    }
}
