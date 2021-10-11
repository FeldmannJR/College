<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRole extends Enum
{
    const Screen = 0;
    const Statistics = 1;
    const Admin = 2;
    const Root = 3;


    public static function checkPermission(UserRole $pagePermission, UserRole $userPermissions)
    {
        if ($pagePermission->value <= $userPermissions->value) {
            return true;
        }
        return false;

    }

}
