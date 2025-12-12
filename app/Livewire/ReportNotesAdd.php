<?php

namespace App\Livewire;

use App\Actions\NewNote;
use App\Livewire\Traits\Geo;
use App\Livewire\Traits\ImagePicker;
use App\Livewire\Traits\ReportApi;
use App\Models\Note;
use App\Models\Report;
use App\Services\ApiClient;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Edge\Edge;
use Native\Mobile\Facades\Device;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\SecureStorage;

class ReportNotesAdd extends Component
{
    use ImagePicker, ReportApi;

    public $id;

    public $report;

    public $os;

    public string $photoDataUrl = '';

    public $storagePath='';

    public $new_note = [
        'content' => '',
        'image' => null,
    ];

    public $showMethods = false;

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
        //$this->notes = Note::whereNoteableId($this->report->report_id)->get();
    }

    public function saveNote()
    {
        $this->validate([
            'new_note.content' => 'required',
        ]);
        // Check if photoDataUrl contains compressed base64 image
        if (str_starts_with($this->photoDataUrl, 'data:image')) {
            // Use the already compressed image from client-side
            $imageValue = $this->photoDataUrl;
        } else {
            // Fallback: read from file if not already compressed
            $image = Storage::path($this->newImage);
            $data = base64_encode(file_get_contents($image));
            $mime = mime_content_type($image);
            $imageValue = "data:$mime;base64,$data";

            // Note: We no longer use MediaHelper::compressBase64Image here
            // as compression should happen client-side
        }

        $data = [
            'external_id' => null,
            'from_user_id' => auth()->user()->user_id,
            'from_name' => auth()->user()->name,
            'app_key' => 'actionit',
            'description' => $this->new_note['content'],
            'image' => (!empty($imageValue)) ? $imageValue: null,
            'noteable_type' => 'App\Models\Report',
            'noteable_id' => $this->report->report_id,
            'is_internal' => false,
        ];

        $client = $this->getClient();
        $response_report = $client->postNote($data);
        if (!empty($response_report)) {
            if(!empty($this->newImage)) Storage::delete($this->newImage);
            Dialog::toast(__('Note saved'));
            $this->redirect(route('reports.details.notes', $this->report->report_id));
            //add report to reports array prepend and cache?
        } else {
            Dialog::alert(__('Error saving note'), __('Something went wrong!'));
        }
    }

    public function render()
    {
        return view('livewire.reports.notes-add')
            ->layout('components.layouts.app',[
                    'title' => __('Notes:') . " ". __('create'),
                    'showEdgeComponents' => false,
                    'link_back'=> url('/reports/'.$this->report->report_id.'/notes'),
                ]
            );
    }

}
