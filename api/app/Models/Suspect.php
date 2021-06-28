<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Suspect extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = [
        'photos'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'birth_date'
    ];

    public static function boot(){
        parent::boot();

        static::deleting(function ($suspect){
            $suspect->photos->each->delete();
        });
    }


    public function photos()
    {
        return $this->morphMany(Photo::class, 'photable');
    }
}
