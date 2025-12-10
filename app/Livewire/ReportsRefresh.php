<?php

namespace App\Livewire;

use App\Enums\SyncModel;
use App\Models\Report;
use App\Models\User;
use App\Models\UserSync;
use App\Services\ReportServices;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Native\Mobile\Edge\Edge;

class ReportsRefresh extends Component
{
    public int $createdPage = 0;

    public int $createdTotal = 0;

    public int $createdProgress = 0;

    public bool $createdSyncing = false;

    public bool $createdComplete = false;

    public array $createdSyncedIds = [];

    public int $assignedPage = 0;

    public int $assignedTotal = 0;

    public int $assignedProgress = 0;

    public bool $assignedSyncing = false;

    public bool $assignedComplete = false;

    public array $assignedSyncedIds = [];

    public function mount()
    {
        $edge = new Edge;
        $edge->clear();
    }

    public function startCreatedSync()
    {
        $this->createdPage = 0;
        $this->createdTotal = 0;
        $this->createdProgress = 0;
        $this->createdSyncing = true;
        $this->createdComplete = false;
        $this->createdSyncedIds = [];

        $this->syncCreatedPage();
    }

    public function syncCreatedPage()
    {
        $client = new ReportServices;
        $response = $client->getReports(['page' => $this->createdPage, 'per_page' => 10]);

        $count = count($response['data'] ?? []);
        $this->createdTotal = $response['total'] ?? 0;
        $this->createdProgress = min(($this->createdPage + 1) * 10, $this->createdTotal);

        // Collect synced report IDs
        foreach ($response['data'] ?? [] as $report) {
            $this->createdSyncedIds[] = $report['report_id'];
        }

        if ($count >= 10) {
            $this->createdPage++;
            $this->dispatch('continue-created-sync');
        } else {
            $this->finishCreatedSync();
        }
    }

    protected function finishCreatedSync()
    {
        // Delete local reports that weren't returned from API
        $userId = User::currentUserId();
        if ($userId && count($this->createdSyncedIds) > 0) {
            Report::createdBy($userId)
                ->whereNotIn('report_id', $this->createdSyncedIds)
                ->delete();
        } elseif ($userId && $this->createdTotal === 0) {
            // API returned 0 reports, delete all local created reports
            Report::createdBy($userId)->delete();
        }

        // Record sync time
        if ($userId) {
            UserSync::recordSync($userId, SyncModel::MyReports);
        }

        $this->createdSyncing = false;
        $this->createdComplete = true;
        $this->createdProgress = $this->createdTotal;
    }

    #[On('continue-created-sync')]
    public function continueCreatedSync()
    {
        $this->syncCreatedPage();
    }

    public function startAssignedSync()
    {
        $this->assignedPage = 0;
        $this->assignedTotal = 0;
        $this->assignedProgress = 0;
        $this->assignedSyncing = true;
        $this->assignedComplete = false;
        $this->assignedSyncedIds = [];

        $this->syncAssignedPage();
    }

    public function syncAssignedPage()
    {
        $client = new ReportServices;
        $response = $client->getAssigned(['page' => $this->assignedPage, 'per_page' => 10]);

        $count = count($response['data'] ?? []);
        $this->assignedTotal = $response['total'] ?? 0;
        $this->assignedProgress = min(($this->assignedPage + 1) * 10, $this->assignedTotal);

        // Collect synced report IDs
        foreach ($response['data'] ?? [] as $report) {
            $this->assignedSyncedIds[] = $report['report_id'];
        }

        if ($count >= 10) {
            $this->assignedPage++;
            $this->dispatch('continue-assigned-sync');
        } else {
            $this->finishAssignedSync();
        }
    }

    protected function finishAssignedSync()
    {
        // Delete local assigned reports that weren't returned from API
        $userId = User::currentUserId();
        if ($userId && count($this->assignedSyncedIds) > 0) {
            Report::assignedTo($userId)
                ->whereNotIn('report_id', $this->assignedSyncedIds)
                ->delete();
        } elseif ($userId && $this->assignedTotal === 0) {
            // API returned 0 assigned reports, delete all local assigned reports for this user
            Report::assignedTo($userId)->delete();
        }

        // Record sync time
        if ($userId) {
            UserSync::recordSync($userId, SyncModel::Assigned);
        }

        $this->assignedSyncing = false;
        $this->assignedComplete = true;
        $this->assignedProgress = $this->assignedTotal;
    }

    #[On('continue-assigned-sync')]
    public function continueAssignedSync()
    {
        $this->syncAssignedPage();
    }

    #[Layout('components.layouts.app', ['title' => 'Syncing data', 'showEdgeComponents' => false])]
    public function render()
    {
        return view('livewire.reports.refresh');
    }
}
