<?php

use App\Livewire\Auth\Check;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Geolocation;
use App\Livewire\Home;
use App\Livewire\News;
use App\Livewire\Profile;
use App\Livewire\PushNotification;
use App\Livewire\ReportCreate;
use App\Livewire\ReportDetails;
use App\Livewire\ReportDetailsMap;
use App\Livewire\ReportNotes;
use App\Livewire\Reports;
use App\Livewire\ReportsRefresh;
use App\Livewire\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login');
//Route::get('/register', Register::class)->name('register');
Route::get('/', Check::class)->name('auth-check');

Route::middleware(['mobile.auth'])->group(function () {
    Route::get('/settings', Settings\Index::class)->name('settings');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/geolocation', Geolocation::class)->name('geolocation');
    Route::get('/pushnotifications', PushNotification::class)->name('pushnotifications');

});

Route::middleware(['mobile.auth','mobile.geopermissions'])->group(function () {
    Route::get('/reports', Reports::class)->lazy()->name('home');
    Route::get('/reports/create', ReportCreate::class)->middleware('mobile.pushpermissions')->name('reports.create');
    Route::get('/reports/refresh', ReportsRefresh::class)->name('reports.refresh');
    Route::get('/reports/{id}', ReportDetails::class)->name('reports.details');
    Route::get('/reports/{id}/map', ReportDetailsMap::class)->name('reports.details.map');
    Route::get('/reports/{id}/notes', ReportNotes::class)->name('reports.details.notes');
    Route::get('/reports/{id}/fix', ReportDetailsMap::class)->name('reports.details.fix');
    Route::get('/reports/{id}/worklog', ReportDetailsMap::class)->name('reports.details.worklog');
    Route::get('/reports/{id}/edit', ReportDetailsMap::class)->name('reports.details.edit');
});
