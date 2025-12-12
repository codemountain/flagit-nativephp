<?php

namespace App\Livewire;

use App\Enums\SyncModel;
use App\Models\Report;
use App\Models\User;
use App\Models\UserSync;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;

    public string $activeTab = 'created';

    public int $createdPerPage = 10;

    public int $assignedPerPage = 10;

    public int $myTotal = 0;

    public int $assignedTotal = 0;

    public $userId;

    public function mount()
    {
        $this->userId = User::currentUserId();

        if (! $this->userId) {
           auth()->logout();
           return redirect(route('login'));
        }

        // Get user's sync delay preference (default 1 day = 1440 minutes)
        $user = User::where('user_id', $this->userId)->first();
        $delayMinutes = $user?->sync_delay_minutes ?? 1440;

        // Check if either reports type should redirect (needs sync AND not already notified this cycle)
        $shouldRedirectMyReports = UserSync::shouldRedirectToSync($this->userId, SyncModel::MyReports, $delayMinutes);
        $shouldRedirectAssigned = UserSync::shouldRedirectToSync($this->userId, SyncModel::Assigned, $delayMinutes);

        if ($shouldRedirectMyReports || $shouldRedirectAssigned) {
            // Record that we notified the user
            if ($shouldRedirectMyReports) {
                UserSync::recordNotified($this->userId, SyncModel::MyReports);
            }
            if ($shouldRedirectAssigned) {
                UserSync::recordNotified($this->userId, SyncModel::Assigned);
            }

            $this->redirect(route('reports.refresh'));
        }

        //calculate totals
        $this->myTotal = Report::createdBy($this->userId)->get()->count();
        $this->assignedTotal = Report::assignedTo($this->userId)->get()->count();
    }

    public function loadMore(string $type)
    {
        if ($type === 'created') {
            $this->createdPerPage += 10;
        } else {
            $this->assignedPerPage += 10;
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
        $createdReports = Report::createdBy($this->userId)
            ->with('notes')
            ->latestFirst()
            ->paginate($this->createdPerPage);
        $assignedReports = Report::assignedTo($this->userId)
            ->with('notes')
            ->latestFirst()
            ->paginate($this->assignedPerPage);

        // Get last sync times
        $createdLastSync = UserSync::getLastSync($this->userId, SyncModel::MyReports);
        $assignedLastSync = UserSync::getLastSync($this->userId, SyncModel::Assigned);

        return view('livewire.reports.index', [
            'createdReports' => $createdReports,
            'assignedReports' => $assignedReports,
            'createdLastSyncedAt' => $createdLastSync?->last_synced_at,
            'assignedLastSyncedAt' => $assignedLastSync?->last_synced_at,
        ]);
    }
}
