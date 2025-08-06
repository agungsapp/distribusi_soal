<?php

use App\Http\Controllers\AuthController;
use App\Livewire\AllMataKuliahPage;
use App\Livewire\DashboardPage;
use App\Livewire\LoginPage;
use App\Livewire\ManageMkPage;
use App\Livewire\MataKuliahPage;
use App\Livewire\PeminatanPage;
use App\Livewire\PengampuPage;
use App\Livewire\PeriodePage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
})->name('admin.dashboard');



Route::middleware('guest')->group(function () {
    Route::get('login', LoginPage::class)->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});


Route::middleware(['auth'])->group(function () {

    Route::get('dashboard', DashboardPage::class)->name('dashboard');
    Route::middleware('role:admin')->group(function () {
        Route::get('peminatan', PeminatanPage::class)->name('peminatan');
        Route::get('periode', PeriodePage::class)->name('periode');
        Route::get('mata-kuliah', MataKuliahPage::class)->name('mata-kuliah');
    });
    Route::get('pengampu', PengampuPage::class)->name('pengampu');
    Route::get('all-mata-kuliah', AllMataKuliahPage::class)->name('all-mata-kuliah');
    Route::get('manage-mata-kuliah/{id}', ManageMkPage::class)->name('manage-mata-kuliah');
});
