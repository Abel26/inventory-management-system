<?php

namespace App\Enums;

enum WorkType: string
{
    case PRODUCTION = 'production';
    case MAINTENANCE = 'maintenance';
    case QUALITY_CONTROL = 'quality_control';
    case DOCUMENTATION = 'documentation';
    case OTHER = 'other';

    /**
     * Get label for work type.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::PRODUCTION => 'Production',
            self::MAINTENANCE => 'Maintenance',
            self::QUALITY_CONTROL => 'Quality Control',
            self::DOCUMENTATION => 'Documentation',
            self::OTHER => 'Other',
        };
    }

    /**
     * Get color class for work type badge.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::PRODUCTION => 'bg-blue-100 text-blue-800',
            self::MAINTENANCE => 'bg-orange-100 text-orange-800',
            self::QUALITY_CONTROL => 'bg-green-100 text-green-800',
            self::DOCUMENTATION => 'bg-purple-100 text-purple-800',
            self::OTHER => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get icon for work type.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::PRODUCTION => 'factory',
            self::MAINTENANCE => 'wrench',
            self::QUALITY_CONTROL => 'check-square',
            self::DOCUMENTATION => 'file-text',
            self::OTHER => 'dots-three',
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
