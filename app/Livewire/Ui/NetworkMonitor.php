<?php

namespace App\Livewire\Ui;

use Livewire\Component;
use Native\Mobile\Facades\Device;
use Native\Mobile\Facades\Network;
use Native\Mobile\Facades\Network as NetworkFacade;
use Native\Mobile\Facades\System;

class NetworkMonitor extends Component
{
    public $status = '';

    public $connected = false;

    public function mount()
    {
        //$this->getNetwork();
    }

    public function getNetwork()
    {
        $this->reset();
        $status = NetworkFacade::status();
        sleep(1);

        if ($status && $status->connected) {
            $this->connected = true;
            $this->status = $status->type;

        } else {
            $this->status = 'Disconnected';
        }
    }
    public function render()
    {
        return view('livewire.ui.network-monitor');
    }
}
