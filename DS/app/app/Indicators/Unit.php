<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Unit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Unit query()
 * @mixin \Eloquent
 */
class Unit extends Model
{


    public $timestamps = false;

    private static $allUnits = null;

    public static $displayUnits = [4, 3, 9, 7, 11, 8, 15, 19, 20, 14];

    /**
     * @return array retorna uma array com todas as unidades, sendo a chave da array o id da unidade
     */
    public static function getAllUnits()
    {
        if (self::$allUnits === null) {
            self::$allUnits = [];
            $units = Unit::all();
            foreach ($units as $unit) {
                self::$allUnits[$unit->id] = $unit;
            }
        }
        return self::$allUnits;
    }

    public static function getDisplayUnits()
    {
        return Unit::all()->whereIn('id', self::$displayUnits);
    }

    public static function getById(int $id): Unit
    {
        return self::getAllUnits()[$id];
    }
}
