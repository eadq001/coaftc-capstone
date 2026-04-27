<?php

namespace App\Enums;

enum EggSizes: string
{
    case SuperPewee = 'super_pewee';
    case Pewee = 'pewee';
    case Pullet = 'pullet';
    case Small = 'small';
    case Medium = 'medium';
    case Large = 'large';
    case XLarge = 'xLarge';
    case Jumbo = 'jumbo';
    case SuperJumbo = 'super_jumbo';

    public function label()
    {
        return match ($this) {
            self::SuperPewee => 'Super Pewee',
            self::Pewee => 'Pewee',
            self::Pullet => 'Pullet',
            self::Small => 'Small',
            self::Medium => 'Medium',
            self::Large => 'Large',
            self::XLarge => 'Extra Large',
            self::Jumbo => 'Jumbo',
            self::SuperJumbo => 'Super Jumbo',
        };
    }
}


