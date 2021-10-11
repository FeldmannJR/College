<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //

    protected $fillable = ['key', 'value'];

    public static function has($key)
    {
        $setting = self::all()->where('key', $key)->first();
        if ($setting) {
            return true;
        }
    }

    public static function get($key, $defaultValue = null)
    {
        $setting = self::all()->where('key', $key)->first();
        if ($setting) {
            return @unserialize($setting->value);
        }
        return $defaultValue;
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => @serialize($value)]);
    }
}
