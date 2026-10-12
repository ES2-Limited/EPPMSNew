<?php

namespace App\Enums;

enum Status: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Done = 'done';
    case Archive = 'archive';
    case Cancelled = 'cancelled';
}
