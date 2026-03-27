<?php

use App\Http\Controllers\Accounting\AccountAdjustmentController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CollectionPlanController;
use App\Http\Controllers\CollectionPlanItemController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\CollectorPortalController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\Inventory\ProductCategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Inventory\UnitController;
use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Purchasing\PurchaseInvoiceController;
use App\Http\Controllers\Purchasing\SupplierController;
use App\Http\Controllers\Sales\SaleInvoiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : view('auth.login');
});

// Language Switching (No auth required, accessible to all users)
Route::get('/set-language/{lang}', [LanguageController::class, 'setLanguage'])->name('set-language');

// Debug route: show locale sources (session, cookie, app)
Route::get('/debug-locale', function () {
    $cookie = request()->cookie('locale');

    return response()->json([
        'app_locale' => app()->getLocale(),
        'session_locale' => session()->get('locale'),
        'cookie_locale' => $cookie,
        'supported_locales' => ['en', 'ar'],
        'messages_ar_exists' => file_exists(resource_path('lang/ar/messages.php')),
    ]);
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function () {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $user = \Illuminate\Support\Facades\Auth::user();

            // Ensure standard roles exist
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'supervisor']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'accountant']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'plan_supervisor']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'collector']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user']);

            // Auto-assign roles based on user attributes if not already assigned
            if ($user->collector && ! $user->hasRole('collector')) {
                $user->assignRole('collector');
            }
            if (strtolower($user->email) === 'admin@admin.com' && ! $user->hasRole('admin')) {
                $user->assignRole('admin');
            }

            session()->flash('success', 'Welcome '.$user->name.'! You have logged in successfully.');

            return redirect()->intended('/dashboard');
        }

        // Authentication failed
        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    });
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Logout
    Route::post('/logout', function () {
        $name = auth()->user()->name;
        \Illuminate\Support\Facades\Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/')->with('success', 'You have logged out successfully. See you soon, '.$name.'!');
    })->name('logout');

    // 1. Admin ONLY: Restricted System Management
    Route::middleware('role:admin')->group(function () {
        // User Role Management
        Route::put('/users/{user}/roles', [UserController::class, 'assignRoles'])->name('users.assign-roles');
        // Generic admin table viewer
        Route::get('/admin/tables/{table}', [\App\Http\Controllers\AdminTableController::class, 'show'])->name('admin.tables.show');
        // Full Users CRUD
        Route::get('/users/export', [\App\Http\Controllers\AdminUsersController::class, 'export'])->name('users.export');
        Route::resource('users', \App\Http\Controllers\AdminUsersController::class);
        // Backups (Admin Only)
        Route::get('/backups', [BackupController::class, 'index'])->name('admin.backups.index');
        Route::post('/backups', [BackupController::class, 'create'])->name('admin.backups.create');
        Route::get('/backups/download/{filename}', [BackupController::class, 'download'])->name('admin.backups.download');
        Route::post('/backups/restore/{filename}', [BackupController::class, 'restore'])->name('admin.backups.restore');
        Route::delete('/backups/{filename}', [BackupController::class, 'destroy'])->name('admin.backups.destroy');

        // Audit Logs (Admin Only per request "monitoring procedures restricted to Admin")
        Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('admin.audit-logs.index');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('admin.settings.update');
    });

    // 2. Shared Management: Financial & Customer Focus (Admin, Supervisor, Accountant, Plan Supervisor)
    Route::middleware('role:admin|supervisor|accountant|plan_supervisor')->group(function () {
        // Customers management (Accountant needs to view/create customers for billing, Supervisor manages)
        Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
        Route::post('/customers/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
        Route::delete('/customers/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.forceDelete');
        Route::resource('customers', CustomerController::class);

        // Collections management
        Route::get('/collections/export', [CollectionController::class, 'export'])->name('collections.export');
        Route::post('/collections/{id}/restore', [CollectionController::class, 'restore'])->name('collections.restore');
        Route::delete('/collections/{id}/force-delete', [CollectionController::class, 'forceDelete'])->name('collections.forceDelete');
        Route::resource('collections', CollectionController::class);

        // Cheques management
        Route::get('/cheques/export', [ChequeController::class, 'export'])->name('cheques.export');
        Route::post('/cheques/{id}/restore', [ChequeController::class, 'restore'])->name('cheques.restore');
        Route::delete('/cheques/{id}/force-delete', [ChequeController::class, 'forceDelete'])->name('cheques.forceDelete');
        Route::resource('cheques', ChequeController::class);

        // Customer Accounts (Ledger)
        Route::get('/customer-accounts/export', [CustomerAccountController::class, 'export'])->name('customer-accounts.export');
        Route::get('/customer-accounts', [CustomerAccountController::class, 'index'])->name('customer-accounts.index');
        Route::get('/customers/{customer}/ledger', [CustomerAccountController::class, 'customerLedger'])->name('customer.ledger');
        Route::get('/customers/{customer}/ledger/create', [CustomerAccountController::class, 'create'])->name('customer.ledger.create');
        Route::post('/customers/{customer}/ledger', [CustomerAccountController::class, 'store'])->name('customer.ledger.store');
        Route::get('/customer-accounts/{customerAccount}', [CustomerAccountController::class, 'show'])->name('customer-accounts.show');

        // Account Adjustments (Settlements)
        Route::get('/customers/{customer}/adjustment', [AccountAdjustmentController::class, 'createCustomer'])->name('accounting.adjustments.customer');
        Route::post('/customers/{customer}/adjustment', [AccountAdjustmentController::class, 'storeCustomer'])->name('accounting.adjustments.customer.store');
        Route::get('/suppliers/{supplier}/adjustment', [AccountAdjustmentController::class, 'createSupplier'])->name('accounting.adjustments.supplier');
        Route::post('/suppliers/{supplier}/adjustment', [AccountAdjustmentController::class, 'storeSupplier'])->name('accounting.adjustments.supplier.store');

        // Reports
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

        // Installment System Routes (Moved here so Supervisor & Accountant access it)
        Route::prefix('installments')->name('installments.')->group(function () {
            Route::get('/', [InstallmentController::class, 'index'])->name('index');
            Route::get('/create', [InstallmentController::class, 'create'])->name('create');
            Route::post('/', [InstallmentController::class, 'store'])->name('store');
            Route::get('/{plan}', [InstallmentController::class, 'show'])->name('show');

            // Plan Management
            Route::get('/{plan}/edit', [InstallmentController::class, 'edit'])->name('edit');
            Route::put('/{plan}', [InstallmentController::class, 'update'])->name('update');
            Route::delete('/{plan}', [InstallmentController::class, 'destroy'])->name('destroy');

            // Item Management
            Route::get('/items/{installment}/edit', [InstallmentController::class, 'editItem'])->name('item.edit');
            Route::put('/items/{installment}', [InstallmentController::class, 'updateItem'])->name('item.update');
            Route::post('/items/{installment}/postpone', [InstallmentController::class, 'postponeItem'])->name('item.postpone');
            Route::delete('/items/{installment}', [InstallmentController::class, 'destroyItem'])->name('item.destroy');
        });

        // Issue Management
        Route::post('/issues/{id}/restore', [\App\Http\Controllers\IssueController::class, 'restore'])->name('issues.restore');
        Route::delete('/issues/{id}/force-delete', [\App\Http\Controllers\IssueController::class, 'forceDelete'])->name('issues.force-delete');
        Route::resource('issues', \App\Http\Controllers\IssueController::class)->only(['index', 'show', 'update', 'destroy'])->withTrashed(['show', 'destroy']);
    });

    // 3. Shared Management: Planning Focus (Admin, Supervisor, Plan Supervisor)
    Route::middleware('role:admin|supervisor|plan_supervisor')->group(function () {
        Route::get('/collection-plans/export', [CollectionPlanController::class, 'export'])->name('collection-plans.export');
        Route::resource('collection-plans', CollectionPlanController::class);
        Route::resource('collection-plan-items', CollectionPlanItemController::class);

        Route::get('/installments/{plan}/print', [InstallmentController::class, 'printReceipts'])->name('installments.print');

        Route::get('/visit-plans/export', [\App\Http\Controllers\VisitPlanController::class, 'export'])->name('visit-plans.export');
        Route::resource('visit-plans', \App\Http\Controllers\VisitPlanController::class);
        Route::post('/visit-plan-items', [\App\Http\Controllers\VisitPlanItemController::class, 'store'])->name('visit-plan-items.store');
        Route::patch('/visit-plan-items/{visitPlanItem}/priority', [\App\Http\Controllers\VisitPlanItemController::class, 'updatePriority'])->name('visit-plan-items.update-priority');
        Route::patch('/visit-plan-items/{visitPlanItem}/status', [\App\Http\Controllers\VisitPlanItemController::class, 'updateStatus'])->name('visit-plan-items.update-status');
        Route::delete('/visit-plan-items/{visitPlanItem}', [\App\Http\Controllers\VisitPlanItemController::class, 'destroy'])->name('visit-plan-items.destroy');

        // AJAX: Used by both Collection Plans and Visit Plans
        Route::get('/collectors/{collector}/customers', [\App\Http\Controllers\VisitPlanController::class, 'getCustomers'])->name('collectors.customers');
    });

    // 4. Master Data & Staff (Admin, Supervisor)
    Route::middleware('role:admin|supervisor')->group(function () {
        // Collectors (Staff) - Restricted from Accountant
        Route::get('/collectors/export', [CollectorController::class, 'export'])->name('collectors.export');
        Route::post('/collectors/{id}/restore', [CollectorController::class, 'restore'])->name('collectors.restore');
        Route::delete('/collectors/{id}/force-delete', [CollectorController::class, 'forceDelete'])->name('collectors.forceDelete');
        Route::resource('collectors', CollectorController::class);

        Route::resource('areas', \App\Http\Controllers\AreaController::class);
        Route::resource('banks', \App\Http\Controllers\BankController::class);
        Route::resource('visit-types', \App\Http\Controllers\VisitTypeController::class);

        // === Inventory Module ===
        Route::resource('product-categories', ProductCategoryController::class);
        Route::resource('units', UnitController::class);
        Route::resource('products', ProductController::class);
        Route::resource('warehouses', WarehouseController::class);
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/movements', [StockController::class, 'movements'])->name('stock.movements');

        // === Purchasing Module ===
        Route::resource('suppliers', SupplierController::class);
        Route::get('/purchase-invoices', [PurchaseInvoiceController::class, 'index'])->name('purchase-invoices.index');
        Route::get('/purchase-invoices/create', [PurchaseInvoiceController::class, 'create'])->name('purchase-invoices.create');
        Route::post('/purchase-invoices', [PurchaseInvoiceController::class, 'store'])->name('purchase-invoices.store');
        Route::get('/purchase-invoices/{purchaseInvoice}', [PurchaseInvoiceController::class, 'show'])->name('purchase-invoices.show');
        Route::post('/purchase-invoices/{purchaseInvoice}/confirm', [PurchaseInvoiceController::class, 'confirm'])->name('purchase-invoices.confirm');
        Route::post('/purchase-invoices/{purchaseInvoice}/payment', [PurchaseInvoiceController::class, 'addPayment'])->name('purchase-invoices.add-payment');
        Route::delete('/purchase-invoices/{purchaseInvoice}', [PurchaseInvoiceController::class, 'destroy'])->name('purchase-invoices.destroy');

        // === Sales Module ===
        Route::get('/sale-invoices', [SaleInvoiceController::class, 'index'])->name('sale-invoices.index');
        Route::get('/sale-invoices/create', [SaleInvoiceController::class, 'create'])->name('sale-invoices.create');
        Route::post('/sale-invoices', [SaleInvoiceController::class, 'store'])->name('sale-invoices.store');
        Route::get('/sale-invoices/{saleInvoice}', [SaleInvoiceController::class, 'show'])->name('sale-invoices.show');
        Route::get('/sale-invoices/{saleInvoice}/print', [SaleInvoiceController::class, 'print'])->name('sale-invoices.print');
        Route::post('/sale-invoices/{saleInvoice}/confirm', [SaleInvoiceController::class, 'confirm'])->name('sale-invoices.confirm');
        Route::post('/sale-invoices/{saleInvoice}/payment', [SaleInvoiceController::class, 'addPayment'])->name('sale-invoices.add-payment');
        Route::delete('/sale-invoices/{saleInvoice}', [SaleInvoiceController::class, 'destroy'])->name('sale-invoices.destroy');

        // === Ad-hoc Action Approvals ===
        Route::prefix('approvals')->name('admin.approvals.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ApprovalController::class, 'index'])->name('index');
            Route::post('/{type}/{id}/approve', [\App\Http\Controllers\ApprovalController::class, 'approve'])->name('approve');
            Route::post('/{type}/{id}/reject', [\App\Http\Controllers\ApprovalController::class, 'reject'])->name('reject');
        });
    });

    // Shared Reporting & Print Routes (Accessible to Admin, Supervisor, and Collector)
    Route::get('/visit-details/{visit}', [\App\Http\Controllers\VisitPlanController::class, 'showVisitDetails'])->name('visit.details');
    Route::get('/receipt/{collection}', [CollectorPortalController::class, 'printReceipt'])->name('shared.receipt');
});

// Collector Portal Routes - Dedicated interface for collectors only
Route::middleware(['auth', 'role:collector'])->prefix('collector')->name('collector.')->group(function () {
    Route::get('/', [CollectorPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/plans/{plan}', [CollectorPortalController::class, 'showPlan'])->name('plan');
    Route::get('/collect/{planItem}', [CollectorPortalController::class, 'showCollectForm'])->name('collect');
    Route::post('/collect/{planItem}', [CollectorPortalController::class, 'storeCollection'])->name('collect.store');

    // Visit Routes
    Route::get('/visit-dashboard', [CollectorPortalController::class, 'visitDashboard'])->name('visits');
    Route::get('/visit-plans/{visitPlan}', [CollectorPortalController::class, 'showVisitPlan'])->name('visit-plan');
    Route::get('/visit/{visitPlanItem}', [CollectorPortalController::class, 'showVisitForm'])->name('visit');
    Route::post('/visit/{visitPlanItem}', [CollectorPortalController::class, 'storeVisit'])->name('visit.store');
    Route::put('/issues/{issue}', [CollectorPortalController::class, 'updateIssue'])->name('update-issue');

    // Collector Sales Route
    Route::get('/sale-invoice/create/{customer?}', [CollectorPortalController::class, 'createSaleInvoice'])->name('sale-invoice.create');
    Route::post('/sale-invoice/store', [CollectorPortalController::class, 'storeSaleInvoice'])->name('sale-invoice.store');

    // Ad-hoc Actions (Out of Plan)
    Route::get('/adhoc', [CollectorPortalController::class, 'adhocLanding'])->name('adhoc');
    Route::get('/adhoc/collect/{customer}', [CollectorPortalController::class, 'showAdhocCollectForm'])->name('adhoc.collect');
    Route::get('/adhoc/visit/{customer}', [CollectorPortalController::class, 'showAdhocVisitForm'])->name('adhoc.visit');
    Route::post('/adhoc/collect/{customer}', [CollectorPortalController::class, 'storeAdhocCollection'])->name('adhoc.store-collection');
    Route::post('/adhoc/visit/{customer}', [CollectorPortalController::class, 'storeAdhocVisit'])->name('adhoc.store-visit');

    // Tracking
    Route::get('/my-approvals', [CollectorPortalController::class, 'myApprovals'])->name('my-approvals');
});

// POS Seller Portal Routes
Route::middleware(['auth', 'role:seller|admin'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [\App\Http\Controllers\POSController::class, 'index'])->name('index');
    Route::post('/store', [\App\Http\Controllers\POSController::class, 'store'])->name('store');
});
