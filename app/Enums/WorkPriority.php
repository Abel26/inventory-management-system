<?php

namespace App\Enums;

enum WorkPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';

    /**
     * Get label for priority.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::URGENT => 'Urgent',
        };
    }

    /**
     * Get color class for priority badge.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::LOW => 'bg-gray-100 text-gray-800',
            self::MEDIUM => 'bg-blue-100 text-blue-800',
            self::HIGH => 'bg-orange-100 text-orange-800',
            self::URGENT => 'bg-red-100 text-red-800',
        };
    }

    /**
     * Get icon for priority.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::LOW => 'arrow-down',
            self::MEDIUM => 'minus',
            self::HIGH => 'arrow-up',
            self::URGENT => 'warning',
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
