<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\SecureStorage;
use Native\Mobile\Facades\System;

class Index extends Component
{
    public bool $autoGenerate = false;

    public string $contentCountThreshold = '5';

    public string $userName = '';

    public string $userEmail = '';

    public function mount(): void
    {
        // Ensure user is authenticated
        if (! Auth::check()) {
            redirect()->route('login');
        }

        $this->loadUserData();
    }

    public function boot(): void
    {
        // This runs on every request, including wire:navigate
        $this->loadUserData();
    }

    public function loadUserData(): void
    {

        $user = User::find(Auth::id());

        $this->userName = $user->name ?? "incognito";
        $this->userEmail = $user->email ?? "mystery@email.com";

    }

    public function viewAppSettings()
    {
        System::appSettings();
    }

    public function resetPermissions()
    {
        SecureStorage::set('location_permission', false);
        SecureStorage::set('current_latitude', null);
        SecureStorage::set('current_longitude', null);
        SecureStorage::set('current_accuracy', null);
        Dialog::toast('Geo locations permissions reset');
        $this->viewAppSettings();
    }

    public function resetPushPermissions()
    {
        SecureStorage::set('push_requested', false);
        SecureStorage::set('push_notification_token', null);
        Dialog::toast('Push notification permissions reset');
        $this->viewAppSettings();
    }


    public function updatedAutoGenerate(): void
    {
//        $user = User::find(Auth::id());
//        $user->setSetting(SettingType::AutoGenerate, $this->autoGenerate ? '1' : '0');
    }

    public function updatedContentCountThreshold(): void
    {
//        $user = User::find(Auth::id());
//        $user->setSetting(SettingType::ContentCountThreshold, $this->contentCountThreshold);
    }

    public function render()
    {
        return view('livewire.settings.index')
            ->layout('components.layouts.app', ['title' => 'Settings']);
    }
}
