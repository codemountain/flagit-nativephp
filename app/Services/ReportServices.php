<?php

namespace App\Services;

class ReportServices
{
    public $client;

    public function __construct(){
        $this->client = new ApiAuthService();
    }

    public function getReports(array $query = ['page' => 0, 'per_page' => 10]): array
    {
        return $this->client->get('report', $query);
    }
}
