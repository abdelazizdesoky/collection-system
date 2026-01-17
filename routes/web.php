<?php

use App\Http\Controllers\ChequeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CollectionPlanController;
use App\Http\Controllers\CollectionPlanItemController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\CollectorPortalController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BackupController;
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

    // Admin-only routes (Full access to everything)
    // Admin-only routes (Full access to everything)
    Route::middleware('role:admin')->group(function () {
        // User Role Management
        Route::put('/users/{user}/roles', [UserController::class, 'assignRoles'])->name('users.assign-roles');
        // Generic admin table viewer (shows recent rows for any mapped table)
        Route::get('/admin/tables/{table}', [\App\Http\Controllers\AdminTableController::class, 'show'])
            ->name('admin.tables.show');
        // Full Users CRUD for admin
        Route::get('/users/export', [\App\Http\Controllers\AdminUsersController::class, 'export'])->name('users.export');
        Route::resource('users', \App\Http\Controllers\AdminUsersController::class);
        // Audit Logs
        Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('admin.audit-logs.index');
        // Backups
        Route::get('/backups', [BackupController::class, 'index'])->name('admin.backups.index');
        Route::post('/backups', [BackupController::class, 'create'])->name('admin.backups.create');
        Route::get('/backups/download/{filename}', [BackupController::class, 'download'])->name('admin.backups.download');
        Route::post('/backups/restore/{filename}', [BackupController::class, 'restore'])->name('admin.backups.restore');
        Route::delete('/backups/{filename}', [BackupController::class, 'destroy'])->name('admin.backups.destroy');
    });

    // Supervisor routes (Customers, Collectors, Plans, Monitoring, Cheques)
    Route::middleware('role:admin|supervisor')->group(function () {
        // Customers management
        Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
        Route::resource('customers', CustomerController::class);
        // Collectors management
        Route::get('/collectors/export', [CollectorController::class, 'export'])->name('collectors.export');
        Route::resource('collectors', CollectorController::class);
        // Collection Plans management
        Route::get('/collection-plans/export', [CollectionPlanController::class, 'export'])->name('collection-plans.export');
        Route::resource('collection-plans', CollectionPlanController::class);
        Route::resource('collection-plan-items', CollectionPlanItemController::class);
        // Collections management
        Route::get('/collections/export', [CollectionController::class, 'export'])->name('collections.export');
        Route::resource('collections', CollectionController::class);
        // Cheques management
        Route::get('/cheques/export', [ChequeController::class, 'export'])->name('cheques.export');
        Route::resource('cheques', ChequeController::class);
        // Customer Accounts (Ledger)
        Route::get('/customer-accounts', [CustomerAccountController::class, 'index'])->name('customer-accounts.index');
        Route::get('/customers/{customer}/ledger', [CustomerAccountController::class, 'customerLedger'])->name('customer.ledger');
        Route::get('/customers/{customer}/ledger/create', [CustomerAccountController::class, 'create'])->name('customer.ledger.create');
        Route::post('/customers/{customer}/ledger', [CustomerAccountController::class, 'store'])->name('customer.ledger.store');
        Route::get('/customer-accounts/{customerAccount}', [CustomerAccountController::class, 'show'])->name('customer-accounts.show');
    });
});

// Collector Portal Routes - Dedicated interface for collectors only
Route::middleware(['auth', 'role:collector'])->prefix('collector')->name('collector.')->group(function () {
    Route::get('/', [CollectorPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/plans/{plan}', [CollectorPortalController::class, 'showPlan'])->name('plan');
    Route::get('/collect/{planItem}', [CollectorPortalController::class, 'showCollectForm'])->name('collect');
    Route::post('/collect/{planItem}', [CollectorPortalController::class, 'storeCollection'])->name('collect.store');
    Route::get('/receipt/{collection}', [CollectorPortalController::class, 'printReceipt'])->name('receipt');
});
