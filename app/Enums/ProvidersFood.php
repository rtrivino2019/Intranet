<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProvidersFood: string implements HasLabel {

    case PFG = 'PFG';
    case AllAmerican = 'All American';
    case Pomares = 'The Produce Group';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::PFG => 'PFG',
            self::AllAmerican => 'All American',
            self::Pomares => 'The Produce Group',

        };
    }
}
