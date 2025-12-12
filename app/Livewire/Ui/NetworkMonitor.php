<?php

namespace App\Livewire\Ui;

use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Facades\Device;
use Native\Mobile\Facades\Network;
use Native\Mobile\Facades\Network as NetworkFacade;
use Native\Mobile\Facades\SecureStorage;
use Native\Mobile\Facades\System;

class NetworkMonitor extends Component
{
    public $status = '';

    public $connected = false;

    public string $statusMessage = '';

    public function mount()
    {
        $this->statusMessage = __('Checking connection...');
        $this->getNetwork();
    }

    #[On('check-network')]
    public function getNetwork()
    {
//        dd('checking connection...');
//        $this->reset();
        $status = NetworkFacade::status();

        if ($status && $status->connected) {
            SecureStorage::set('device_online', true);
            $this->connected = true;
            $this->status = $status->type;
            $this->statusMessage = __('You are online');

        } else {
            SecureStorage::set('device_online', false);
            $this->status = 'Disconnected';
            $this->statusMessage = __('Offline. Some features may not work');
        }
    }
    public function render()
    {
        return view('livewire.ui.network-monitor');
    }
}
