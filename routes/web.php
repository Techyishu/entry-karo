<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EntryController as AdminEntryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Guard\DashboardController as GuardDashboardController;
use App\Http\Controllers\Guard\EntryController as GuardEntryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [\App\Http\Controllers\Auth\RegistrationController::class, 'create'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegistrationController::class, 'store'])->name('register.post');
});

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Super Admin Routes
    |--------------------------------------------------------------------------
    | Access: Super Admin only
    | Permissions: Full system access
    */
    Route::prefix('admin')->middleware('super_admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Visitors Management
        Route::get('/visitors', [AdminDashboardController::class, 'visitors'])->name('admin.visitors');
        Route::delete('/visitors/{visitor}', [AdminDashboardController::class, 'deleteVisitor'])->name('admin.visitors.delete');

        // Customers Management
        Route::get('/customers', [AdminDashboardController::class, 'customers'])->name('admin.customers');
        Route::delete('/customers/{user}', [AdminDashboardController::class, 'deleteCustomer'])->name('admin.customers.delete');

        // Guards Management
        Route::get('/guards', [AdminDashboardController::class, 'guards'])->name('admin.guards');
        Route::delete('/guards/{user}', [AdminDashboardController::class, 'deleteGuard'])->name('admin.guards.delete');

        // Entries Management
        Route::get('/entries', [AdminDashboardController::class, 'entries'])->name('admin.entries');

        // User Management
        Route::prefix('users')->group(function () {
            Route::get('/', fn() => 'Users Index')->name('admin.users.index');
            Route::get('/create', fn() => 'Users Create')->name('admin.users.create');
            Route::post('/', fn() => 'Users Store')->name('admin.users.store');
            Route::get('/{user}', fn() => 'Users Show')->name('admin.users.show');
            Route::put('/{user}', fn() => 'Users Update')->name('admin.users.update');
            Route::delete('/{user}', fn() => 'Users Delete')->name('admin.users.delete');
        });

        // Visitor Management
        Route::prefix('visitors')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'visitors'])->name('admin.visitors.index');
            Route::get('/create', fn() => 'Visitors Create')->name('admin.visitors.create');
            Route::post('/', fn() => 'Visitors Store')->name('admin.visitors.store');
            Route::get('/{visitor}', fn() => 'Visitors Show')->name('admin.visitors.show');
            Route::put('/{visitor}', fn() => 'Visitors Update')->name('admin.visitors.update');
        });

        // Entry Management - Delete requires super admin only
        Route::prefix('entries')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'entries'])->name('admin.entries.index');
            Route::get('/{entry}', [AdminEntryController::class, 'show'])->name('admin.entries.show');
            Route::get('/{entry}/confirm-delete', [AdminEntryController::class, 'confirmDelete'])->name('admin.entries.confirm-delete');
            Route::delete('/{entry}', [AdminEntryController::class, 'destroy'])->name('admin.entries.destroy');
        });

        // Subscription Management
        Route::prefix('customer')->group(function () {
            Route::get('/{customer}/details', [\App\Http\Controllers\Admin\SubscriptionController::class, 'show'])->name('admin.customer.show');
            Route::post('/{customer}/subscription/assign', [\App\Http\Controllers\Admin\SubscriptionController::class, 'assign'])->name('admin.subscription.assign');
            Route::patch('/subscription/{subscription}/status', [\App\Http\Controllers\Admin\SubscriptionController::class, 'updateStatus'])->name('admin.subscription.update-status');
            Route::delete('/subscription/{subscription}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'destroy'])->name('admin.subscription.destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    | Access: Customer only
    | Permissions: Can view entries, add guards, cannot delete entries
    */
    Route::prefix('customer')->middleware('customer')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

        // Guard Management (Customer can add/manage their guards)
        Route::prefix('guards')->group(function () {
            Route::get('/', [\App\Http\Controllers\Customer\GuardController::class, 'index'])->name('customer.guards.index');
            Route::get('/create', [\App\Http\Controllers\Customer\GuardController::class, 'create'])->name('customer.guards.create');
            Route::post('/', [\App\Http\Controllers\Customer\GuardController::class, 'store'])->name('customer.guards.store');
            Route::get('/{guard}', [\App\Http\Controllers\Customer\GuardController::class, 'show'])->name('customer.guards.show');
            Route::get('/{guard}/edit', [\App\Http\Controllers\Customer\GuardController::class, 'edit'])->name('customer.guards.edit');
            Route::put('/{guard}', [\App\Http\Controllers\Customer\GuardController::class, 'update'])->name('customer.guards.update');
            Route::delete('/{guard}', [\App\Http\Controllers\Customer\GuardController::class, 'destroy'])->name('customer.guards.delete');
        });

        // Entry Viewing (Customer can only view, not delete)
        Route::prefix('entries')->group(function () {
            Route::get('/', [\App\Http\Controllers\Customer\EntryController::class, 'index'])->name('customer.entries.index');
            Route::get('/{entry}', [\App\Http\Controllers\Customer\EntryController::class, 'show'])->name('customer.entries.show');
            // NOTE: Delete route intentionally omitted - customers cannot delete entries
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Guard Routes
    |--------------------------------------------------------------------------
    | Access: Guard only
    | Permissions: Can ONLY access guard screen
    */
    Route::prefix('guard')->middleware('guard')->group(function () {
        Route::get('/dashboard', [GuardDashboardController::class, 'index'])->name('guard.dashboard');

        // Entry Management (Check-in/Check-out)
        Route::prefix('entries')->group(function () {
            Route::get('/', [GuardEntryController::class, 'index'])->name('guard.entries.index');
            Route::get('/list', [GuardEntryController::class, 'list'])->name('guard.entries.list');

            // Search visitor by mobile number
            Route::post('/search', [GuardEntryController::class, 'search'])->name('guard.entries.search');

            // Register new visitor
            Route::post('/visitor/register', [GuardEntryController::class, 'registerVisitor'])->name('guard.entries.visitor.register');

            // Check-in visitor
            Route::post('/check-in', [GuardEntryController::class, 'checkIn'])->name('guard.entries.check-in');

            // Check-out visitor (MUST be before /{entry} route)
            Route::post('/check-out', [GuardEntryController::class, 'checkOut'])->name('guard.entries.check-out');

            // Entry details - MUST BE LAST because it uses wildcard {entry}
            // Constrain to numeric IDs only to prevent matching routes like /check-out
            Route::get('/{entry}', [GuardEntryController::class, 'showEntryDetails'])
                ->where('entry', '[[0-9]+')
                ->name('guard.entries.show');
        });

        // Carry Items Management
        Route::prefix('carry-items')->group(function () {
            Route::post('/store', [GuardEntryController::class, 'storeCarryItem'])->name('guard.carry-items.store');
            Route::put('/{item}', [GuardEntryController::class, 'updateCarryItem'])->name('guard.carry-items.update');
        });
    });
});

// --- HOSTINGER DEPLOYMENT HELPER ---
// Uncomment the route below to set up storage links and clear cache on shared hosting
// Route::get('/setup-hostinger', function () {
//     \Illuminate\Support\Facades\Artisan::call('storage:link');
//     \Illuminate\Support\Facades\Artisan::call('optimize:clear');
//     return 'Storage Linked and Cache Cleared! You can now delete this route.';
// });
