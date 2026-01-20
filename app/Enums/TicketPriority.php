<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TicketPriority: string implements HasColor, HasLabel
{
    case LOW = 'Low';
    case MEDIUM = 'Medium';
    case CRITICAL = 'Critical';
    case HIGH = 'High';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::CRITICAL => 'Critical',
            self::HIGH => 'High',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::LOW => 'info',
            self::MEDIUM => 'primary',
            self::CRITICAL => 'danger',
            self::HIGH => 'warning',
        };
    }

}
