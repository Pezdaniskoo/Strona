<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware(['auth', 'admin', 'set.locale'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');

        Route::post('/locale', function () {
            $locale = request('locale', 'pl');
            abort_unless(in_array($locale, ['pl', 'en'], true), 422);
            auth()->user()->update(['locale' => $locale]);
            session(['locale' => $locale]);

            return back();
        })->name('admin.locale');

        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('admin.users.toggle-active');

        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        Route::get('/tasks', [TaskController::class, 'index'])->name('admin.tasks.index');
        Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('admin.tasks.update');
        Route::patch('/tasks/{task}/quick-status', [TaskController::class, 'quickStatus'])->name('admin.tasks.quick-status');

        Route::get('/audit-logs', AuditLogController::class)->name('admin.audit-logs.index');

        Route::get('/mail', [MailController::class, 'index'])->name('admin.mail.index');
        Route::post('/mail', [MailController::class, 'send'])->name('admin.mail.send');
    });
});
