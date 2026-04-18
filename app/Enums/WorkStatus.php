<?php

namespace App\Enums;

enum WorkStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case ON_HOLD = 'on_hold';
    case CANCELLED = 'cancelled';

    /**
     * Get label for status.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::ON_HOLD => 'On Hold',
            self::CANCELLED => 'Cancelled',
        };
    }

    /**
     * Get color class for status badge.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'bg-gray-100 text-gray-800',
            self::IN_PROGRESS => 'bg-blue-100 text-blue-800',
            self::COMPLETED => 'bg-green-100 text-green-800',
            self::ON_HOLD => 'bg-yellow-100 text-yellow-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
        };
    }

    /**
     * Get icon for status.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::PENDING => 'clock',
            self::IN_PROGRESS => 'spinner',
            self::COMPLETED => 'check-circle',
            self::ON_HOLD => 'pause-circle',
            self::CANCELLED => 'x-circle',
        };
    }

    /**
     * Get all available values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
