<?php

namespace App\Enums;

enum UserRoles: string
{
    case ADMIN = 'admin';
    case INVENTORY = 'inventory_clerk';
    case CASHIER = 'cashier';


    public function getUserRole(): string
    {
        return match ($this) {
            self::ADMIN => 'administrator',
            self::INVENTORY => 'inventory clerk',
            self::CASHIER => 'cashier',
        };
    }
}
