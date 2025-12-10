<?php

namespace App\Livewire;

use App\Services\ReportServices;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Edge\Edge;

class ReportsRefresh extends Component
{
    public $reportCount = 0;
    public $assignedCount = 0;

    public function mount()
    {
        $edge = new Edge();
        $edge->clear();
        $this->init();
    }

    public function init()
    {
        $client = new ReportServices;

        // Sync created reports (API saves to DB via Report::saveListFromApi)
        $page = 0;
        do {
            $reports = $client->getReports(['page' => $page, 'per_page' => 10]);
            $page++;
            $this->reportCount = count($reports['data']);
        } while (count($reports) >= $reports['total'] ?? 9999);

        // Sync assigned reports
        $page = 0;
        do {
            $reports = $client->getAssigned(['page' => $page, 'per_page' => 10]);
            $page++;
            $this->assignedCount = count($reports['data']);;
        } while (count($reports) >= $reports['total'] ?? 9999);

       // $this->redirect(route('home'));
    }



    #[Layout('components.layouts.app', ['title' => 'Synching data', 'showEdgeComponents' => false])]
    public function render()
    {
        return view('livewire.reports.refresh');
    }
}
