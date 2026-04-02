<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\IssueController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PasswordController as AdminPasswordController;

use App\Http\Controllers\Tenant\TenantDashboardController;
use App\Http\Controllers\Tenant\ProfileController;
use App\Http\Controllers\Tenant\PasswordController;
use App\Http\Controllers\Tenant\TenantContractController;
use App\Http\Controllers\Tenant\TenantBillController;
use App\Http\Controllers\Tenant\TenantPaymentController;
use App\Http\Controllers\Tenant\TenantIssueController;

use App\Http\Controllers\Tenant\TenantRoomController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| FORGOT / RESET PASSWORD ROUTES (NEW)
|--------------------------------------------------------------------------
| - /forgot-password: nhập email -> gửi link reset
| - /reset-password/{token}: đặt mật khẩu mới
| FORGOT / RESET PASSWORD
|--------------------------------------------------------------------------
>>>>>>> feb1f02 (first commit)
*/
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'update'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::resource('users', UserController::class)->only(['index', 'store', 'destroy']);

        // Buildings
        Route::resource('buildings', BuildingController::class)->only(['index', 'store', 'destroy']);
        /**
         * ROOMS
         * ✅ Cần có edit + update để trang sửa chạy được
         * Bạn đang dùng modal để thêm => không cần create/show
         */
        Route::resource('rooms', RoomController::class)->except(['create', 'show']); // index, store, edit, update, destroy

        // Rooms
        Route::resource('rooms', RoomController::class)->except(['create', 'show']);

        // Tenants
        Route::resource('tenants', TenantController::class)->except(['create', 'show']);
        /**
         * CONTRACTS
         * (Bạn đang dùng modal tạo) => không cần create/show
         */
        Route::resource('contracts', ContractController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

        // Xem PDF inline (không tải)
        Route::get('contracts/{contract}/view', [ContractController::class, 'view'])->name('contracts.view');

        /**
         * BILLS / INVOICES
         */
        Route::resource('bills', BillController::class)
            ->parameters(['bills' => 'invoice']) // 🔥 QUAN TRỌNG
            ->only(['index', 'store', 'edit', 'update', 'destroy']);

        Route::get('bills/{invoice}/view', [BillController::class, 'view'])->name('bills.view');

        /**
         * PAYMENTS (tenant upload proof -> admin approve)
         */
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');

        Route::get('payments/{payment}/view', [PaymentController::class, 'viewFile'])->name('payments.view');

        Route::get('payments/{payment}/download', [PaymentController::class, 'downloadFile'])->name('payments.download');
        // Contracts

        Route::resource('contracts', ContractController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
        Route::get('contracts/{contract}/view', [ContractController::class, 'view'])->name('contracts.view');
        Route::resource('bills', BillController::class)
            ->parameters(['bills' => 'invoice'])
            ->only(['index', 'store', 'edit', 'update', 'destroy']);

        Route::get('bills/{invoice}/view', [BillController::class, 'view'])->name('bills.view');
        Route::get('bills/{invoice}/download', [BillController::class, 'download'])->name('bills.download');

        // Payments
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}/view', [PaymentController::class, 'viewFile'])->name('payments.view');
        Route::get('payments/{payment}/download', [PaymentController::class, 'downloadFile'])->name('payments.download');
        Route::post('payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
        Route::post('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

        // Issues
        Route::resource('issues', IssueController::class)->only(['index']);
        Route::post('issues/{issue}/fixing', [IssueController::class, 'markFixing'])->name('issues.fixing');
        Route::post('issues/{issue}/resolved', [IssueController::class, 'markResolved'])->name('issues.resolved');

        // password (admin change password inside admin)
        // Password
        Route::get('/password', [AdminPasswordController::class, 'index'])->name('password.index');
        Route::post('/password', [AdminPasswordController::class, 'update'])->name('password.update');
    });

/*
|--------------------------------------------------------------------------
| TENANT ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:tenant'])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {
        // ✅ Contracts (Tenant)
        Route::get('/contracts', [TenantContractController::class, 'index'])->name('contracts.index');
        Route::get('/contracts/{contract}/view', [TenantContractController::class, 'view'])->name('contracts.view');

        // Dashboard
        Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        // Dashboard
        Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');

        Route::get('/rooms', [TenantRoomController::class, 'index'])
        ->name('rooms.index');
        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        // Password
        Route::get('/password', [PasswordController::class, 'index'])->name('password.index');
        Route::post('/password', [PasswordController::class, 'update'])->name('password.update.index');

        // ✅ Bills (Tenant)
        // Contracts
        Route::get('/contracts', [TenantContractController::class, 'index'])->name('contracts.index');
        Route::get('/contracts/{contract}/view', [TenantContractController::class, 'view'])->name('contracts.view');
        Route::post('/contracts/{contract}/renew', [TenantContractController::class, 'renew'])
        ->name('contracts.renew');

        // Bills
        Route::get('/bills', [TenantBillController::class, 'index'])->name('bills.index');
        Route::get('/bills/{invoice}/view', [TenantBillController::class, 'view'])->name('bills.view');
        Route::get('/bills/{invoice}/download', [TenantBillController::class, 'download'])->name('bills.download');

        Route::post('/bills/{invoice}/payment', [TenantPaymentController::class, 'store'])
            ->name('bills.payment.store');

        // Issues
        Route::get('/issues', [TenantIssueController::class, 'index'])->name('issues.index');
        Route::post('/issues', [TenantIssueController::class, 'store'])->name('issues.store');
    });