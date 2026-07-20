<?php

namespace App\Scaffolds;

use Wpwwhimself\Shipyard\Scaffolds\Role as ShipyardRole;

class Role extends ShipyardRole
{
    protected static function items(): array
    {
        return [
            [
                "name" => "teacher",
                "icon" => "human-male-board",
                "description" => "Ma dostęp do kartotek uczniów i sesji",
            ],
        ];
    }
}
