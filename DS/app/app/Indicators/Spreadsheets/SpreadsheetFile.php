<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


/**
 * Class SpreadsheetFile
 * @package App
 * @mixin \Eloquent
 */
class SpreadsheetFile extends Model
{


    protected $fillable = ['full_path', 'file_name', 'original_file_name', 'file_size'];


    public static function getLast()
    {
        return SpreadsheetFile::orderBy('created_at', 'desc')->limit(1)->first();
    }

    public static function getLastPath(): ?string
    {
        $prefix = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        $last = SpreadsheetFile::getLast();
        return $last === null ? null : $prefix . $last->full_path;
    }
}
