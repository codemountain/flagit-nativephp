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

class ReportDetailsImagesAdd extends Component
{
    use ImagePicker, ReportApi;

    public $id;

    public $report;

    public $os;

    public string $photoDataUrl = '';

    public $storagePath='';

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
//        if(empty($this->report)){
//            $this->redirect(route('home'));
//        }
    }

    public function saveImage()
    {
        $this->validate([
            'photoDataUrl' => 'required',
        ]);
        // Check if photoDataUrl contains compressed base64 image
        if (str_starts_with($this->photoDataUrl, 'data:image')) {
            // Use the already compressed image from client-side
            $imageValue = $this->photoDataUrl;
        } elseif(!empty($this->newImage)) {
            // Fallback: read from file if not already compressed
            $image = Storage::path($this->newImage);
            $data = base64_encode(file_get_contents($image));
            $mime = mime_content_type($image);
            $imageValue = "data:$mime;base64,$data";
        }

        $data = [
            'image' => (!empty($imageValue)) ? $imageValue: null,
        ];

        $client = $this->getClient();
        $response_report = $client->postReportImage($this->report->report_id,$data);
        if (!empty($response_report)) {
            if(!empty($this->newImage)) Storage::delete($this->newImage);
            Dialog::toast(__('Image saved'));
            $this->redirect(route('reports.details', $this->report->report_id));
            //add report to reports array prepend and cache?
        } else {
            Dialog::alert(__('Error saving image'), __('Something went wrong!'));
        }
    }

    public function render()
    {

        return view('livewire.reports.imageadd')
            ->layout('components.layouts.app',[
                    'title' => __('Img:') . " ".Str::limit($this->report->title,19) ?? __('Images'),
                    'showEdgeComponents' => false,
                    'link_back'=> url('/reports/'.$this->report->report_id)
                ]
            );
    }

}
