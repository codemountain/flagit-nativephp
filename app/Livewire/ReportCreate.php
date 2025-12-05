<?php

namespace App\Livewire;

use App\Helpers\NativeImages;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Attributes\OnNative;
use Native\Mobile\Events\Camera\PhotoTaken;
use Native\Mobile\Facades\Camera;

class ReportCreate extends Component
{
    public string $photoDataUrl = ''; // For display preview

    public string $photoFullDataUrl = ''; // Full base64 for API submission

    public bool $needsPreviewGeneration = false;

    public $photoPath = '';

    public $files;

    public $new_report = [
        'title' => '',
        'description' => '',
        'lat' => null,
        'long' => null,
        'image' => null,
        'is_urgent' => false,
        'type' => null,
    ];

    public function mount()
    {
        // dd(Storage::allFiles('photos'));

    }

    public function getImage()
    {
        Flux::modal('image-method')->show();
    }

    public function getImageFromCamera()
    {
        $this->photoDataUrl = '';
        $this->photoFullDataUrl = '';
        $this->needsPreviewGeneration = false;
        $this->new_report['image'] = null;
        Flux::modal('image-method')->close();
        Camera::getPhoto();
        $this->photoPath = 'waiting';
    }

    #[OnNative(PhotoTaken::class)]
    public function handleCamera($path)
    {
        // Process the image using the helper
        $result = NativeImages::process($path, 'photos');

        if ($result['success']) {
            // Store filename for the report
            $this->new_report['image'] = $result['filename'];

            // Store full data URL for API submission later
            $this->photoFullDataUrl = $result['full_data_url'];

            // If image is small enough, use directly
            if (! $result['needs_preview']) {
                $this->photoDataUrl = $result['display_url'];
                $this->needsPreviewGeneration = false;
            } else {
                // For larger images, we'll generate a preview client-side
                $this->photoDataUrl = '';
                $this->needsPreviewGeneration = true;
                // Dispatch browser event to trigger preview generation
                $this->dispatch('generate-image-preview', [
                    'fullDataUrl' => $result['full_data_url'],
                    'mimeType' => $result['mime_type'],
                ]);
            }

            // Debug information
            $this->photoPath = "Size: {$result['size_kb']} KB | Type: {$result['mime_type']}";
            $this->files = "Image saved: {$result['filename']}";
        } else {
            // Handle error
            $this->photoDataUrl = '';
            $this->photoFullDataUrl = '';
            $this->needsPreviewGeneration = false;
            $this->photoPath = 'Error: '.$result['error'];
            $this->files = '';
        }
    }

    public function updatePreview($previewDataUrl)
    {
        $this->photoDataUrl = $previewDataUrl;
        $this->needsPreviewGeneration = false;
    }

    #[Layout('components.layouts.app', ['title' => 'New report'])]
    public function render()
    {
        return view('livewire.reports.create');
    }
}
