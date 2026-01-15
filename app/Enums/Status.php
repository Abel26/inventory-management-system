<?php

namespace App\Enums;

enum Status: string
{
    case Pending = 'Pending';
    case InProgress = 'In Progress';
    case Resolved = 'Resolved';
    case Rejected = 'Rejected';
}
