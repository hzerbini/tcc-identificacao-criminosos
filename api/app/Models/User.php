<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRolesAndAbilities;

    protected $appends = ['permissions'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPermissionsAttribute()
    {
        $abilities = $this->getAbilities()->merge($this->getForbiddenAbilities());

        $abilities->each(function ($ability) {
            $ability->forbidden = $this->getForbiddenAbilities()->contains($ability);
        });
    
        return $abilities;
    }
}
