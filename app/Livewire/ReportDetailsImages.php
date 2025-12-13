<?php

namespace App\Livewire;

use App\Livewire\Traits\Geo;
use App\Livewire\Traits\ReportApi;
use App\Models\Report;
use App\Services\ApiClient;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Edge\Edge;
use Native\Mobile\Facades\Device;
use Native\Mobile\Facades\SecureStorage;

class ReportDetailsImages extends Component
{
    Use Geo;

    public $device_info = null;

    public $id;

    public $report;

    public $os;

    public function mount($id)
    {
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
    }

    public function render()
    {
        return view('livewire.reports.images')
            ->layout('components.layouts.app',[
                'title' => Str::limit($this->report->title,20) ?? __('Report images'),
                'showEdgeComponents' => false,
                'link_back'=> url('/reports/'.$this->report->report_id)
                ]
            );
    }

}
