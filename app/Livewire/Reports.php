<?php

namespace App\Livewire;

use App\Services\ApiClient;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Facades\SecureStorage;

class Reports extends Component
{
    public string $userName = '';
    public string $userEmail = '';
    public string $activeTab = 'created';

    public function mount(): void
    {
        $this->userName = SecureStorage::get('user_name', 'User') ?? "MartyTester";
        $this->userEmail = SecureStorage::get('user_email', '') ?? "marty@email.com";
    }

    public function logout(): void
    {
        ApiClient::logout();
        $this->redirect(route('login'));

    }

    #[Layout('components.layouts.app', ['title' => 'Reports'])]
    public function render()
    {
        return view('livewire.reports.index');
    }
}
