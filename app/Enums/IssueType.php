<?php

namespace App\Enums;

enum IssueType: string
{
    case Damage = 'Damage';
    case Maintenance = 'Maintenance';
    case Lost = 'Lost';
    case StockDiscrepancy = 'Stock Discrepancy';
}
