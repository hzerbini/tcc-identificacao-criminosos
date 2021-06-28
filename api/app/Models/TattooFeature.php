<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TattooFeature extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot(){
        parent::boot();

        static::created(function ($feature){
            $feature->tattoos()->sync([]);
        });
    }

    public function tattoos()
    {
        return $this->belongsToMany(Tattoo::class);
    }
}
