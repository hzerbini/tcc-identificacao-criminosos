<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedSuspectSearch extends Model
{
    use HasFactory;

    protected $guarded = [];

        /**
     * model life cycle event listeners
     */
    public static function boot(){
        parent::boot();

        static::deleting(function ($savedSuspectSearch){
            $savedSuspectSearch->tattooFeatures()->sync([]);
        });
    }

    public function tattooFeatures()
    {
        return $this->belongsToMany(TattooFeature::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithTattooFeatures($query, $features)
    {
        $query = $query->with('tattooFeatures')->where(function($query) use ($features){
            foreach($features as $feature)
            {
                $query = $query->whereHas('tattooFeatures', function($query) use ($feature){
                    return $query->where('name', $feature->name);
                });
            }

            return $query;
        });
        
        return $query;
    }
}
