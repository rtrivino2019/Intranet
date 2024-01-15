<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PercentageType: string implements HasLabel {

    case Food = 'Food';
    case Alcohol = 'Alcohol';
    case Others = 'Others';
    case Payroll = 'Payroll';
    case Utilities = 'Utilities';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Food => 'Food',
            self::Alcohol => 'Alcohol',
            self::Others => 'Others',
            self::Payroll => 'Payroll',
            self::Utilities => 'Utilities',
        };
    }
}
