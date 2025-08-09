<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebugController;
use App\Livewire\AllMataKuliahPage;
use App\Livewire\CreateSoalPage;
use App\Livewire\DashboardPage;
use App\Livewire\DataUserPage;
use App\Livewire\EditSoalPage;
use App\Livewire\LoginPage;
use App\Livewire\ManageMkPage;
use App\Livewire\MataKuliahPage;
use App\Livewire\PeminatanPage;
use App\Livewire\PencarianSoalPage;
use App\Livewire\PengampuPage;
use App\Livewire\PeriodePage;
use App\Livewire\ProfilePage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
})->name('admin.dashboard');



Route::middleware('guest')->group(function () {
    Route::get('login', LoginPage::class)->name('login');
});
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    Route::get('dashboard', DashboardPage::class)->name('dashboard');
    Route::middleware('role:admin')->group(function () {
        Route::get('peminatan', PeminatanPage::class)->name('peminatan');
        Route::get('periode', PeriodePage::class)->name('periode');
        Route::get('mata-kuliah', MataKuliahPage::class)->name('mata-kuliah');
        Route::get('pencarian-soal', PencarianSoalPage::class)->name('pencarian-soal');
        Route::get('pengampu', PengampuPage::class)->name('pengampu');
        Route::get('data-user', DataUserPage::class)->name('data-user');
    });
    Route::get('all-mata-kuliah', AllMataKuliahPage::class)->name('all-mata-kuliah');
    Route::get('manage-mata-kuliah/{id}', ManageMkPage::class)->name('manage-mata-kuliah');

    Route::get('soal/create', CreateSoalPage::class)->name('soal.create');
    Route::get('soal/edit/{soal}', EditSoalPage::class)->name('soal.edit');


    Route::get('profile', ProfilePage::class)->name('profile');
});


Route::get('debug', [DebugController::class, 'index'])->name('debug');
