<?php

namespace App\Livewire;

use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Camera as CameraFacade;
use Native\Mobile\Facades\File;

class ReportCreate extends Component
{


    public string $photoDataUrl = '';
    public $photoPath = '';

    public $new_report = [
        'title' => '',
        'description' => '',
        'lat' => null,
        'long' => null,
        'image' => null,
        'is_urgent' => false,
        'type' => null
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
        $this->photoPath = 'waiting';
    }

    #[OnNative(PhotoTaken::class)]
    public function handleCamera($path)
    {
        $filename = 'photos/photo_'.time().'.jpg';
        $this->photoPath = $path;
//        $data   = base64_encode(file_get_contents($path));
//        $mime   = mime_content_type($path);
//        dd($data);

        //dd(Storage::disk('public')->path($filename));
        File::move($path, Storage::path($filename));
        $this->photoDataUrl = Storage::url($filename);
        //$this->photoDataUrl = "data:$mime;base64,$data";
//        $this->new_report['image'] = $path;
//        $this->dispatch('exif-new-image');
    }


    #[Layout('components.layouts.app', ['title' => 'New report'])]
    public function render()
    {
        return view('livewire.reports.create');
    }
}
