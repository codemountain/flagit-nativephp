<?php

namespace App\Livewire;

use App\Services\ReportServices;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ReportsRefresh extends Component
{

    public array $reportStates = [
        'created' => [
            'data' => [],
            'page' => 0,
            'hasMore' => true,
            'isLoading' => false,
            'isLoadingMore' => false,
        ],
        'assigned' => [
            'data' => [],
            'page' => 0,
            'hasMore' => true,
            'isLoading' => false,
            'isLoadingMore' => false,
        ],
    ];

    public function mount()
    {
        $this->flushReports('created');
        $this->flushReports('assigned');
        $this->redirect(route('home'));
    }

    protected function getCacheKey(string $type, int $page): string
    {
        return "user_{$type}_reports_{$page}";
    }

    public function flushReports(string $type)
    {
        // Clear all cached pages for this type
        for ($i = 0; $i <= $this->reportStates[$type]['page']; $i++) {
            Cache::forget($this->getCacheKey($type, $i));
        }

    }

    #[Layout('components.layouts.app', ['title' => 'Reports'])]
    public function render()
    {
        return view('livewire.reports.refresh');
    }
}
