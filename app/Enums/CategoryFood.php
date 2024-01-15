<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CategoryFood: string implements HasLabel {

    case Vegetales = 'Vegetales';
    case Carnes = 'Carnes';
    case Papeleria = 'Papeleria';
    case Cleaners = 'Cleaners';
    case Nevera = 'Nevera';
    case Despensa = 'Despensa';
    case Sodas = 'Sodas/Otros';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Vegetales => 'Vegetales',
            self::Carnes => 'Carnes',
            self::Papeleria => 'Papeleria',
            self::Cleaners => 'Cleaners',
            self::Nevera => 'Nevera',
            self::Despensa => 'Despensa',
            self::Sodas => 'Sodas/Otros',
        };
    }
}
