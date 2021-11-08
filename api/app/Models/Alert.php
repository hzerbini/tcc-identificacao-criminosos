<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\NewAlertSent;
use Illuminate\Support\Str;

class Alert extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot(){
        parent::boot();

        static::created(function($alert){
            dispatch(function() use ($alert){
                NewAlertSent::dispatch($alert);
            });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
