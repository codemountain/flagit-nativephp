<?php

namespace App\Livewire\Auth;

use App\Services\ApiClient;
use Livewire\Component;
use Native\Mobile\Facades\SecureStorage;

class Logout extends Component
{

    public function mount()
    {
        SecureStorage::delete('api_token');
        SecureStorage::delete('user_id');
        SecureStorage::delete('device_os');
        SecureStorage::delete('device_platform');
        ApiClient::logout();
        auth()->logout();
        $this->redirect(route('login'));
    }
}
