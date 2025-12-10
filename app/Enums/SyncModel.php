<?php

namespace App\Enums;

enum SyncModel: string
{
    case MyReports = 'my_reports';
    case Assigned = 'assigned';
    case Notes = 'notes';
    case Attachments = 'attachments';

    public function label(): string
    {
        return match ($this) {
            self::MyReports => 'My Reports',
            self::Assigned => 'Assigned Reports',
            self::Notes => 'Notes',
            self::Attachments => 'Attachments',
        };
    }
}
