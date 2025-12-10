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

    public int $createdPerPage = 5;

    public int $assignedPerPage = 5;

    public function mount()
    {
        $userId = User::currentUserId();

        if (! $userId) {
            return;
        }

        // Get user's sync delay preference (default 1 day = 1440 minutes)
        $user = User::where('user_id', $userId)->first();
        $delayMinutes = $user?->sync_delay_minutes ?? 1440;

        // Check if either reports type should redirect (needs sync AND not already notified this cycle)
        $shouldRedirectMyReports = UserSync::shouldRedirectToSync($userId, SyncModel::MyReports, $delayMinutes);
        $shouldRedirectAssigned = UserSync::shouldRedirectToSync($userId, SyncModel::Assigned, $delayMinutes);

        if ($shouldRedirectMyReports || $shouldRedirectAssigned) {
            // Record that we notified the user
            if ($shouldRedirectMyReports) {
                UserSync::recordNotified($userId, SyncModel::MyReports);
            }
            if ($shouldRedirectAssigned) {
                UserSync::recordNotified($userId, SyncModel::Assigned);
            }

            $this->redirect(route('reports.refresh'));
        }
    }

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

        // Get last sync times
        $createdLastSync = UserSync::getLastSync($userId, SyncModel::MyReports);
        $assignedLastSync = UserSync::getLastSync($userId, SyncModel::Assigned);

        return view('livewire.reports.index', [
            'createdReports' => $createdReports,
            'assignedReports' => $assignedReports,
            'createdLastSyncedAt' => $createdLastSync?->last_synced_at,
            'assignedLastSyncedAt' => $assignedLastSync?->last_synced_at,
        ]);
    }
}
