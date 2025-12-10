<?php

namespace App\Livewire;

use App\Models\Report;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;

    public string $activeTab = 'created';

    public int $createdPerPage = 5;

    public int $assignedPerPage = 5;

    public function loadMore(string $type)
    {
        if ($type === 'created') {
            $this->createdPerPage += 5;
        } else {
            $this->assignedPerPage += 5;
        }
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
        $userId = User::currentUserId();

        $createdReports = Report::createdBy($userId)
            ->latestFirst()
            ->paginate($this->createdPerPage);
        $assignedReports = Report::assignedTo($userId)
            ->latestFirst()
            ->paginate($this->assignedPerPage);

        return view('livewire.reports.index', [
            'createdReports' => $createdReports,
            'assignedReports' => $assignedReports,
        ]);
    }
}
