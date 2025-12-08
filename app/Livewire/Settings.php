<?php

namespace App\Livewire;

use App\Services\ApiClient;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\SecureStorage;

class Settings extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    #[Rule('required')]
    public string $current_password = '';

    #[Rule('required|min:8')]
    public string $password = '';

    #[Rule('required|same:password')]
    public string $password_confirmation = '';

    public function resetPermissions(): void
    {
        SecureStorage::delete('current_latitude');
        SecureStorage::delete('current_longitude');
        SecureStorage::delete('current_accuracy');
        SecureStorage::delete('location_permission');
        Dialog::toast('Geo permissions reseted successfully!');
    }


    public function logout()
    {
        ApiClient::logout();

        $this->redirect(route('login'));
    }

    #[Layout('components.layouts.app', ['title' => 'Settings'])]
    public function render()
    {
        return view('livewire.settings.index');
    }
}
