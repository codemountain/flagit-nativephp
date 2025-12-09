<?php

namespace App\Traits;

use App\Services\ReportServices;

trait ReportApi
{
    protected function getCacheKey(string $type, int $page): string
    {
        return "user_{$type}_reports_{$page}";
    }

    protected function getClient(): ReportServices
    {
        return new ReportServices;
    }
}
