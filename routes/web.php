<?php

use App\Livewire\Auth\Check;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Geolocation;
use App\Livewire\Home;
use App\Livewire\News;
use App\Livewire\Profile;
use App\Livewire\ReportCreate;
use App\Livewire\ReportDetails;
use App\Livewire\Reports;
use App\Livewire\ReportsRefresh;
use App\Livewire\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/', Check::class)->name('auth-check');

Route::middleware(['mobile.auth'])->group(function () {
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/geolocation', Geolocation::class)->name('geolocation');
});

Route::middleware(['mobile.auth','mobile.geopermissions'])->group(function () {
    Route::get('/reports', Reports::class)->lazy()->name('home');
    Route::get('/reports/create', ReportCreate::class)->name('reports.create');
    Route::get('/reports/refresh', ReportsRefresh::class)->name('reports.refresh');
    Route::get('/reports/{id}', ReportDetails::class)->name('reports.details');
});
