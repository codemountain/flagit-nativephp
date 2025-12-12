<?php

namespace App\Enums;

enum ReportStatuses: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Received = 'received';
    case Searching = 'searching';
    case Linked = 'linked';
    case Done = 'done';
    case Cancelled = 'cancelled';

    public static function doNotEdit(): array
    {
        return [
            'linked',
            'done',
            'cancelled',
        ];
    }

    public function label()
    {
        return (string) str($this->name)->replace('_', ' ');
    }

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->label();
        }

        return $array;
    }

    public static function toObject(): \Illuminate\Support\Collection
    {
        $result = [];
        foreach (self::cases() as $case) {
            $obj = new \stdClass;
            $obj->name = $case->label();
            $obj->id = $case->value;
            $result[] = $obj;
        }

        return collect($result);
    }

    public function icon()
    {
        switch ($this->value) {
            case 'draft':
                $icon = 'o-pencil-square';
                break;

            case 'submitted':
                $icon = 'check-circle';
                break;

            case 'queued':
                $icon = 'custom.hourglass';
                break;

            default:
                $icon = 'o-pencil-square';

        }

        return $icon;
    }

    public function color()
    {
        switch ($this->value) {
            case 'draft':
                $color = 'grey';
                break;

            case 'submitted':
                $color = 'green';
                break;

            case 'queued':
                $color = 'yellow';
                break;

            default:
                $color = 'grey';

        }

        return $color;
    }
}
