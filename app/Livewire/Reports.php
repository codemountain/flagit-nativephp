<?php

namespace App\Livewire;

use App\Services\ApiAuthService;
use App\Services\ApiClient;
use App\Services\Reports\GetReports;
use App\Services\ReportServices;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Facades\SecureStorage;

class Reports extends Component
{
    public $reports;

    public string $activeTab = 'created';

    public $api;

    protected $client;

    public int $page = 0;

    public string $cacheKey;

    public function mount()
    {
        $this->init();
        // $this->requestCurrentLocation();
    }

    public function init()
    {
//        $this->initLocation();
        $this->client = new ReportServices();
        $this->cacheKey = "user_reports_".$this->page;
        if (Cache::has($this->cacheKey)) {
            $this->reports = Cache::get($this->cacheKey);
        } else {
            $this->reports = $this->client->getReports(['page' => 0, 'per_page' => 10]);
            Cache::put($this->cacheKey, $this->reports , now()->addMinutes(10));
        }
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
        Cache::forget($this->cacheKey);
        $this->reports = [];
        $this->init();
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
        return view('livewire.reports.index',['reports' => $this->reports]);
    }
}
