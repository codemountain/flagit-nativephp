<?php

namespace App\Services;

use App\Models\Note;
use App\Models\Report;

class ReportServices
{
    public $client;

    public function __construct()
    {
        $this->client = new ApiAuthService;
    }

    public function getReports(array $query = ['page' => 0, 'per_page' => 10]): array
    {
        $data = $this->client->get('report', $query);
        return Report::saveListFromApi($data);
    }

    public function getAssigned(array $query = ['page' => 0, 'per_page' => 10]): array
    {
        $data = $this->client->get('report/assigned', $query);
        return Report::saveListFromApi($data);
    }

    public function getReport($id): array
    {
        //need to store report in Report table Report->saveSingleFromApi();
        return $this->client->get('report/'.$id);
    }

    public function postReport(array $data) : Report
    {
        $resp = $this->client->post('report', $data);
        //store report locally and send back to api request
        return Report::saveSingleFromApi($resp['report']);
    }

    public function postNote(array $data)
    {
        $resp = $this->client->post('note', $data);
        if($resp && !empty($resp['note'])){
            return Note::saveSingleFromApi($resp['note']);
        }
        return null;
    }

//    public function getNotes(array $query = ['page' => 0, 'per_page' => 10]): array
//    {
//        $data = $this->client->get('report', $query);
//        return Report::saveListFromApi($data);
//    }
}
