<?php

namespace App\Livewire;

use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Facades\Camera as CameraFacade;
use Native\Mobile\Facades\File;

class ReportCreate extends Component
{


    public string $photoDataUrl = '';


    public $new_report = [
        'title' => '',
        'description' => '',
        'lat' => null,
        'long' => null,
        'image' => null,
        'is_urgent' => false,
        'type' => null
    ];

    public function camera()
    {
        CameraFacade::getPhoto();
    }

    #[OnNative(PhotoTaken::class)]
    public function handleCamera($path)
    {
        $filename = 'photos/photo_'.time().'.jpg';

        File::move($path, Storage::path($filename));

        $this->photoDataUrl = Storage::url($filename);
    }


    #[Layout('components.layouts.app', ['title' => 'New report'])]
    public function render()
    {
        return view('livewire.reports.create');
    }
}
