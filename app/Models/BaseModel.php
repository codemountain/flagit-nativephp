<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static function normalizeMorphType(string $type): string
    {
        // App\Actionit\Models\Report → report
        return match (true) {
            str_ends_with($type, '\\Report') => 'report',
            str_ends_with($type, '\\Note')   => 'note',
            str_ends_with($type, '\\Attachment')   => 'attachment',
            str_ends_with($type, '\\Worklog')   => 'worklog',
            default => $type,
        };
    }
}
