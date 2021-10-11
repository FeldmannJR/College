<?php

namespace App;

use App\Enums\UserRole;
use BenSampo\Enum\Traits\CastsEnums;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use CastsEnums;
    use Notifiable;

    protected $enumCasts = [
        'user_role' => UserRole::class,
    ];
    protected $with = 'googleAccount';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'user_role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function googleAccount()
    {
        return $this->belongsTo(GoogleAccount::class, 'google_account');
    }

    /**
     * Verifica se o usuario tem uma conta do google linkada
     * @param User $user user to verify
     * @return bool if user has account linked
     */

    public function hasGoogleAccount()
    {
        return $this->googleAccount !== null;
    }

}
