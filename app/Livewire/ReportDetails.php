<?php

namespace App\Livewire;

use App\Services\ApiClient;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Facades\Device;
use Native\Mobile\Facades\SecureStorage;

class ReportDetails extends Component
{
    public $device_info = null;

    public function mount()
    {
        $this->device_info = Device::getInfo();
    }

    public function openMap()
    {
        Flux::modal('report-map')->show();
        $this->dispatch('open-map');
    }


    #[Layout('components.layouts.app', ['title' => 'Trail Report'])]
    public function render()
    {
        return view('livewire.reports.details');
    }
}
