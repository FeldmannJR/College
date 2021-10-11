<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleAccount extends Model
{

    protected $casts = ['token' => 'json'];
    protected $fillable = ['token', 'refresh_token', 'file_id', 'google_id', 'name', 'email'];

    protected $hidden = ['token', 'refresh_token'];
    //

}
