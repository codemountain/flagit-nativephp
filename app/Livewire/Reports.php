<?php

namespace App\Livewire;

use App\Services\ReportServices;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Reports extends Component
{
    public string $activeTab = 'created';

    public int $perPage = 5;

    // State management for each report type
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
        $this->initReports('created');
        $this->initReports('assigned');
    }

    protected function getCacheKey(string $type, int $page): string
    {
        return "user_{$type}_reports_{$page}";
    }

    protected function getClient(): ReportServices
    {
        return new ReportServices;
    }

    public function initReports(string $type)
    {
        $this->reportStates[$type]['isLoading'] = true;
        $this->reportStates[$type]['page'] = 0;

        $cacheKey = $this->getCacheKey($type, 0);

        if (Cache::has($cacheKey)) {
            $this->reportStates[$type]['data'] = Cache::get($cacheKey);
        } else {
            $client = $this->getClient();
            // Call the appropriate API method based on type
            $reports = $type === 'created'
                ? $client->getReports(['page' => 0, 'per_page' => $this->perPage])
                : $client->getAssigned(['page' => 0, 'per_page' => $this->perPage]);

            $this->reportStates[$type]['data'] = $reports;
            Cache::put($cacheKey, $reports, now()->addMinutes(10));
        }

        // Check if we have more pages
        $this->reportStates[$type]['hasMore'] = count($this->reportStates[$type]['data']) >= $this->perPage;
        $this->reportStates[$type]['isLoading'] = false;
    }

    public function flushReports(string $type)
    {
        $this->reportStates[$type]['isLoading'] = true;

        // Clear all cached pages for this type
        for ($i = 0; $i <= $this->reportStates[$type]['page']; $i++) {
            Cache::forget($this->getCacheKey($type, $i));
        }

        $this->reportStates[$type]['data'] = [];
        $this->reportStates[$type]['page'] = 0;
        $this->reportStates[$type]['hasMore'] = true;

        // Dispatch a browser event to trigger loadReports after render
        $this->dispatch("reports-flushed-{$type}");
    }

    public function loadReports(string $type)
    {
        $this->initReports($type);
    }

    public function loadMore(string $type)
    {
        $this->reportStates[$type]['isLoadingMore'] = true;

        // Increment page
        $this->reportStates[$type]['page']++;
        $page = $this->reportStates[$type]['page'];

        $cacheKey = $this->getCacheKey($type, $page);

        // Check cache for this page
        if (Cache::has($cacheKey)) {
            $newReports = Cache::get($cacheKey);
        } else {
            $client = $this->getClient();
            // Call the appropriate API method based on type
            $newReports = $type === 'created'
                ? $client->getReports(['page' => $page, 'per_page' => $this->perPage])
                : $client->getAssigned(['page' => $page, 'per_page' => $this->perPage]);

            Cache::put($cacheKey, $newReports, now()->addMinutes(10));
        }

        // Append new reports to existing array
        if (is_array($newReports) && count($newReports) > 0) {
            $this->reportStates[$type]['data'] = array_merge(
                $this->reportStates[$type]['data'],
                $newReports
            );
        }

        // Check if we have more pages
        $this->reportStates[$type]['hasMore'] = count($newReports) >= $this->perPage;
        $this->reportStates[$type]['isLoadingMore'] = false;
    }

    public function placeholder()
    {
        return <<<'BLADE'
        <div class="w-full">
            <flux:skeleton.group animate="shimmer" class="flex items-center gap-4"><flux:skeleton class="size-14 rounded-lg" /><div class="flex-1"><flux:skeleton.line class="w-1/2" /><flux:skeleton.line /><flux:skeleton.line /><flux:skeleton.line /></div></flux:skeleton.group>
        </div>
        BLADE;
    }

    #[Layout('components.layouts.app', ['title' => 'Reports'])]
    public function render()
    {
        return view('livewire.reports.index', [
            'reportStates' => $this->reportStates,
        ]);
    }
}
