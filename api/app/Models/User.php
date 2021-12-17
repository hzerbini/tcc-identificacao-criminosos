<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;


use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRolesAndAbilities, HasApiTokens;

    protected $appends = ['permissions'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $with = ['photos'];

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

    /**
     * model life cycle event listeners
     */
    public static function boot(){
        parent::boot();

        static::creating(function ($instance){
            //
        });

        static::created(function ($user){
            Bouncer::allow($user)->toOwn(User::class);
            Bouncer::allow($user)->toOwn(Alert::class)->to([
                'view', 'delete'
            ]);
            Bouncer::allow($user)->toOwn(SavedSuspectSearch::class)->to([
                'view', 'delete'
            ]);
        });

        static::deleting(function ($user){
            $user->photos->each->delete();
            $user->alerts->each->delete();
            $user->savedSuspectSearches->each->delete();
        });
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function savedSuspectSearches()
    {
        return $this->hasMany(SavedSuspectSearch::class);
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photable');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getPermissionsAttribute()
    {
        $abilities = $this->getAbilities()->merge($this->getForbiddenAbilities());

        $abilities->each(function ($ability) {
            $ability->forbidden = $this->getForbiddenAbilities()->contains($ability);
        });
    
        return $abilities;
    }

}
