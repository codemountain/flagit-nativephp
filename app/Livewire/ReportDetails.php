<?php

namespace App\Livewire;

use App\Livewire\Traits\Geo;
use App\Livewire\Traits\ReportApi;
use App\Models\Report;
use App\Services\ApiClient;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Edge\Edge;
use Native\Mobile\Facades\Device;
use Native\Mobile\Facades\SecureStorage;

class ReportDetails extends Component
{
    Use Geo, ReportApi;

    public $device_info = null;

    public $id;

    public $report;

    public $os;

    public function mount($id)
    {
        $edge = new Edge;
        $edge->clear();
        $this->id = $id;
        $this->os = SecureStorage::get('device_os');
        $this->init();
    }

    public function init()
    {
        $this->report = Report::whereReportId($this->id)->first();
        if(empty($this->report)){
            $this->redirect(route('home'));
        }
//        $this->openMap();
    }

    public function openMap()
    {
        Flux::modal('report-map')->show();
        $this->dispatch('open-map');
    }
    /**
     * Handle center-map-on-location event from HasGeo trait
     */
    #[On('center-map-on-location')]
    public function handleCenterMapOnLocation($data)
    {
        $this->js('console.log("PHP: handleCenterMapOnLocation called');
        // Extract data from the event payload
        $lat = $data['lat'] ?? null;
        $lng = $data['lng'] ?? null;
        $componentId = $data['componentId'] ?? null;
        // Dispatch JavaScript event to center the map
        $this->dispatch('move-map-to-location',
            componentId: $this->getId(),
            lat: $lat,
            lng: $lng
        );

    }

    #[Layout('components.layouts.app', ['title' => 'Trail Report', 'showEdgeComponents' => false])]
    public function render()
    {
        return view('livewire.reports.details');
    }
}
