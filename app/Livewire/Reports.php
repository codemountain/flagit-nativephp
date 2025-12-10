<?php

namespace App\Livewire;

use App\Models\Report;
use App\Models\User;
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

    public function initReports(string $type)
    {
        $this->reportStates[$type]['isLoading'] = true;
        $this->reportStates[$type]['page'] = 0;

        $userId = User::currentUserId();

        if ($type === 'created') {
            $reports = Report::createdBy($userId)
                ->latestFirst()
                ->take($this->perPage)
                ->get()
                ->toArray();
        } else {
            // For assigned: filter in PHP using explode/in_array
            $reports = Report::assignedTo()
                ->latestFirst()
                ->get()
                ->filter(fn ($r) => $r->isAssignedTo($userId))
                ->take($this->perPage)
                ->values()
                ->toArray();
        }

        $this->reportStates[$type]['data'] = $reports;
        $this->reportStates[$type]['hasMore'] = count($reports) >= $this->perPage;
        $this->reportStates[$type]['isLoading'] = false;
    }

    public function loadMore(string $type)
    {
        $this->reportStates[$type]['isLoadingMore'] = true;
        $this->reportStates[$type]['page']++;

        $offset = $this->reportStates[$type]['page'] * $this->perPage;
        $userId = User::currentUserId();

        if ($type === 'created') {
            $newReports = Report::createdBy($userId)
                ->latestFirst()
                ->skip($offset)
                ->take($this->perPage)
                ->get()
                ->toArray();
        } else {
            // For assigned: filter in PHP, then paginate
            $newReports = Report::assignedTo()
                ->latestFirst()
                ->get()
                ->filter(fn ($r) => $r->isAssignedTo($userId))
                ->skip($offset)
                ->take($this->perPage)
                ->values()
                ->toArray();
        }

        if (count($newReports) > 0) {
            $this->reportStates[$type]['data'] = array_merge(
                $this->reportStates[$type]['data'],
                $newReports
            );
        }

        $this->reportStates[$type]['hasMore'] = count($newReports) >= $this->perPage;
        $this->reportStates[$type]['isLoadingMore'] = false;
    }

    public function placeholder()
    {
        return <<<'BLADE'
        <div class="w-full flex flex-col gap-4">
            <flux:skeleton.group animate="shimmer" class="flex items-center gap-4"><flux:skeleton class="size-14 rounded-lg" /><div class="flex-1"><flux:skeleton.line class="w-1/2" /><flux:skeleton.line /><flux:skeleton.line /><flux:skeleton.line /></div></flux:skeleton.group>
            <flux:skeleton.group animate="shimmer" class="flex items-center gap-4"><flux:skeleton class="size-14 rounded-lg" /><div class="flex-1"><flux:skeleton.line class="w-1/2" /><flux:skeleton.line /><flux:skeleton.line /><flux:skeleton.line /></div></flux:skeleton.group>
            <flux:skeleton.group animate="shimmer" class="flex items-center gap-4"><flux:skeleton class="size-14 rounded-lg" /><div class="flex-1"><flux:skeleton.line class="w-1/2" /><flux:skeleton.line /><flux:skeleton.line /><flux:skeleton.line /></div></flux:skeleton.group>
            <flux:skeleton.group animate="shimmer" class="flex items-center gap-4"><flux:skeleton class="size-14 rounded-lg" /><div class="flex-1"><flux:skeleton.line class="w-1/2" /><flux:skeleton.line /><flux:skeleton.line /><flux:skeleton.line /></div></flux:skeleton.group>
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
