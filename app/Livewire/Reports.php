<?php

namespace App\Livewire;

use App\Services\ReportServices;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Reports extends Component
{
    public $reports;

    public string $activeTab = 'created';

    public $api;

    protected $client;

    public int $page = 0;

    public string $cacheKey;

    public bool $isLoading = false;

    public bool $isLoadingMore = false;

    public bool $hasMorePages = true;

    public int $perPage = 5;

    public function mount()
    {
        $this->init();
        // $this->requestCurrentLocation();
    }

    public function init()
    {
        //        $this->initLocation();
        $this->isLoading = true;
        $this->page = 0;
        $this->client = new ReportServices;
        $this->cacheKey = 'user_reports_'.$this->page;

        if (Cache::has($this->cacheKey)) {
            $this->reports = Cache::get($this->cacheKey);
        } else {
            $this->reports = $this->client->getReports(['page' => $this->page, 'per_page' => $this->perPage]);
            Cache::put($this->cacheKey, $this->reports, now()->addMinutes(10));
        }

        // Check if we have more pages
        $this->hasMorePages = count($this->reports) >= $this->perPage;

        $this->isLoading = false;
        //        $this->local_notes = Note::getUnsyncedForUser(auth()->user()->external_user_id);
        //        $this->local_worklogs = Worklog::getUnsynced();

        //        if($this->local_reports->count() > 0 || $this->local_notes->count() > 0 || $this->local_worklogs->count() > 0){
        //            $this->activeTab = 'draft';
        //        } else {
        //            $this->activeTab = 'created';
        //        }

        //        $this->uploadData();
    }

    public function flushReports()
    {
        $this->isLoading = true;

        // Clear all cached pages
        for ($i = 0; $i <= $this->page; $i++) {
            Cache::forget('user_reports_'.$i);
        }

        $this->reports = [];
        $this->page = 0;
        $this->hasMorePages = true;

        // Dispatch a browser event to trigger loadReports after render
        $this->dispatch('reports-flushed');
    }

    public function loadReports()
    {
        $this->init();
    }

    public function loadMore()
    {
        $this->isLoadingMore = true;

        // Increment page
        $this->page++;

        $this->client = new ReportServices;
        $this->cacheKey = 'user_reports_'.$this->page;

        // Check cache for this page
        if (Cache::has($this->cacheKey)) {
            $newReports = Cache::get($this->cacheKey);
        } else {
            $newReports = $this->client->getReports(['page' => $this->page, 'per_page' => $this->perPage]);
            Cache::put($this->cacheKey, $newReports, now()->addMinutes(10));
        }

        // Append new reports to existing array
        if (is_array($newReports) && count($newReports) > 0) {
            $this->reports = array_merge($this->reports, $newReports);
        }

        // Check if we have more pages
        $this->hasMorePages = count($newReports) >= $this->perPage;

        $this->isLoadingMore = false;
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
        return view('livewire.reports.index', ['reports' => $this->reports]);
    }
}
