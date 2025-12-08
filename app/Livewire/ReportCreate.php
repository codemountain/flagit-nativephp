<?php

namespace App\Livewire;

use Flux\Flux;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Facades\Camera;

class ReportCreate extends Component
{
    public string $photoDataUrl = '';

    public $storagePath='';

    public $new_report = [
        'title' => '',
        'description' => '',
        'lat' => null,
        'long' => null,
        'image' => null,
        'is_urgent' => false,
        'type' => null,
    ];

    public function getImage()
    {
        Flux::modal('image-method')->show();
    }

    public function getImageFromCamera()
    {
        $this->photoDataUrl = '';
        $this->new_report['image'] = null;
        Flux::modal('image-method')->close();
        Camera::getPhoto();
    }

    #[OnNative(PhotoTaken::class)]
    public function handleCamera($path)
    {
        $filename = 'photos/photo_'.time().'.jpg';
        File::move($path, Storage::path($filename));
        //Log::info("Files ", print_r($this->folderFiles,true));
        $this->photoDataUrl = Storage::url($filename);
        $this->new_report['image'] = $filename;
    }

    #[Layout('components.layouts.app', ['title' => 'New report'])]
    public function render()
    {
        return view('livewire.reports.create');
    }
}
