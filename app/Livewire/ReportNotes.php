<?php

namespace App\Livewire;

use App\Livewire\Traits\Geo;
use App\Livewire\Traits\ReportApi;
use App\Models\Note;
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

class ReportNotes extends Component
{

    public $id;

    public $report;

    public $os;

    public $showImagesModal = false;

    public $currentNoteId = null;

    public function mount($id)
    {
        $this->id = $id;
        $this->os = SecureStorage::get('device_os');
        $this->init();
    }

    public function init()
    {
        $this->report = Report::whereReportId($this->id)->with('notes')->first();
        if(empty($this->report)){
            $this->redirect(route('home'));
        }
        //$this->notes = Note::whereNoteableId($this->report->report_id)->get();
    }


    public function render()
    {
        return view('livewire.reports.notes')
            ->layout('components.layouts.app',[
                    'title' => __('Notes:') . " ".Str::limit($this->report->title,19) ?? __('Report notes'),
                    'showEdgeComponents' => false,
                    'link_back'=> url('/reports/'.$this->report->report_id)
                ]
            );
    }

}
