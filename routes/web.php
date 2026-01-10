<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\CollectionPlanController;
use App\Http\Controllers\CollectionPlanItemController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\LanguageController;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : view('welcome');
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
            'password' => 'required'
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $user = \Illuminate\Support\Facades\Auth::user();
            
            // Ensure standard roles exist
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'collector']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user']);

            // Auto-assign roles based on user attributes if not already assigned
            if ($user->collector && !$user->hasRole('collector')) {
                $user->assignRole('collector');
            }
            if (strtolower($user->email) === 'admin@admin.com' && !$user->hasRole('admin')) {
                $user->assignRole('admin');
            }

            session()->flash('success', 'Welcome ' . $user->name . '! You have logged in successfully.');
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
        
        return redirect('/')->with('success', 'You have logged out successfully. See you soon, ' . $name . '!');
    })->name('logout');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        // User Role Management
        Route::put('/users/{user}/roles', [UserController::class, 'assignRoles'])->name('users.assign-roles');
        // Generic admin table viewer (shows recent rows for any mapped table)
        Route::get('/admin/tables/{table}', [\App\Http\Controllers\AdminTableController::class, 'show'])
            ->name('admin.tables.show');
        // Full Users CRUD for admin
        Route::resource('users', \App\Http\Controllers\AdminUsersController::class);
    });

    // Public authenticated resource routes (controllers will enforce role permissions)
    Route::resource('customers', CustomerController::class);
    Route::resource('collectors', CollectorController::class);
    Route::resource('collections', CollectionController::class);
    Route::resource('cheques', ChequeController::class);
    Route::resource('collection-plans', CollectionPlanController::class);
    Route::resource('collection-plan-items', CollectionPlanItemController::class);

    // Customer Accounts (Ledger) - read routes
    Route::get('/customer-accounts', [CustomerAccountController::class, 'index'])->name('customer-accounts.index');
    Route::get('/customers/{customer}/ledger', [CustomerAccountController::class, 'customerLedger'])->name('customer-accounts.ledger');
    Route::get('/customer-accounts/{customerAccount}', [CustomerAccountController::class, 'show'])->name('customer-accounts.show');
});
