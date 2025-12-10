<?php

namespace App\Livewire;

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

    public int $assignedPage = 0;

    public int $assignedTotal = 0;

    public int $assignedProgress = 0;

    public bool $assignedSyncing = false;

    public bool $assignedComplete = false;

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

        $this->syncCreatedPage();
    }

    public function syncCreatedPage()
    {
        $client = new ReportServices;
        $response = $client->getReports(['page' => $this->createdPage, 'per_page' => 10]);

        $count = count($response['data'] ?? []);
        $this->createdTotal = $response['total'] ?? 0;
        $this->createdProgress = min(($this->createdPage + 1) * 10, $this->createdTotal);

        if ($count >= 10) {
            $this->createdPage++;
            $this->dispatch('continue-created-sync');
        } else {
            $this->createdSyncing = false;
            $this->createdComplete = true;
            $this->createdProgress = $this->createdTotal;
        }
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

        $this->syncAssignedPage();
    }

    public function syncAssignedPage()
    {
        $client = new ReportServices;
        $response = $client->getAssigned(['page' => $this->assignedPage, 'per_page' => 10]);

        $count = count($response['data'] ?? []);
        $this->assignedTotal = $response['total'] ?? 0;
        $this->assignedProgress = min(($this->assignedPage + 1) * 10, $this->assignedTotal);

        if ($count >= 10) {
            $this->assignedPage++;
            $this->dispatch('continue-assigned-sync');
        } else {
            $this->assignedSyncing = false;
            $this->assignedComplete = true;
            $this->assignedProgress = $this->assignedTotal;
        }
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
