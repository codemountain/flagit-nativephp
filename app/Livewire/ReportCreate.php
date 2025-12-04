<?php

namespace App\Livewire;

use App\Services\ApiClient;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Events\Gallery\MediaSelected;
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Device;
use Native\Mobile\Facades\SecureStorage;

class ReportCreate extends Component
{
    public $device_info = null;

    public $previewImage;

    public $new_report = [
        'title' => '',
        'description' => '',
        'lat' => null,
        'long' => null,
        'image' => null,
        'is_urgent' => false,
        'type' => null
    ];

    public function mount()
    {
        $this->device_info = Device::getInfo();
    }

    public function openMap()
    {
        Flux::modal('report-map')->show();
        $this->dispatch('open-map');
    }

    public function handleImageGPS($data)
    {
        if(!empty($data['latitude']) && !empty($data['longitude'])) {
            $this->handleLocationUpdate($data['latitude'], $data['longitude']);
            Flux::toast(__('📍Got GPS lat,long data from image ✅'), variant: 'success');
        } else {
            $this->previewImage = null;
            $this->checkAndRequestPermissions();
            Flux::toast(__('❌ No GPS data found in image OR Location permissions not available.'), variant: 'danger');
        }

    }

    public function getImageFromCamera()
    {
        $this->previewImage = null;
        $this->new_report['image'] = null;
        Flux::modal('image-method')->close();
        Camera::getPhoto();
    }

    public function getImageFromLibrary()
    {
        $this->previewImage = null;
        $this->new_report['image'] = null;
        Flux::modal('image-method')->close();
        Camera::pickImages();
    }

    public function getImage()
    {
        Flux::modal('image-method')->show();
    }

    #[On('native:'.PhotoTaken::class)]
    public function handlePhotoTaken(string $path)
    {
//        ray('MediaSelected: Received photo: ' , $path);
        $data   = base64_encode(file_get_contents($path));
        $mime   = mime_content_type($path);
        $this->previewImage = "data:$mime;base64,$data";
//        ray('handlePhotoTaken: Received photo: ' . $path);
        $this->new_report['image'] = $path;
        $this->dispatch('exif-new-image');
    }

    #[On('native:'.MediaSelected::class)]
    public function handleMediaSelected($success, $files, $count)
    {
        foreach ($files as $file) {
            // Process each selected media item
//            ray('MediaSelected: Received photo: ' , $file);
            $data   = base64_encode(file_get_contents($file['path']));
            $mime   = mime_content_type($file['path']);
            $this->previewImage = "data:$mime;base64,$data";
            $this->new_report['image'] = $file['path'];
            $this->dispatch('exif-new-image');
        }
    }

    #[Layout('components.layouts.app', ['title' => 'New report'])]
    public function render()
    {
        return view('livewire.reports.create');
    }
}
