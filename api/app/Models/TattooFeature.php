<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TattooFeature extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot(){
        parent::boot();

        static::deleting(function ($feature){
            $feature->tattoos()->sync([]);
        });
    }

    public function tattoos()
    {
        return $this->belongsToMany(Tattoo::class);
    }

    public function savedSearches()
    {
        return $this->belongsToMany(SavedSuspectSearch::class)->withTimestamps();
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
    }

    public function setNameSlugAttribute($value)
    {
        $this->attributes['name_slug'] = Str::slug($value);
    }
}
