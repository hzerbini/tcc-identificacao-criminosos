<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\TattooFeature;

class Tattoo extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['features'];

    public static function boot(){
        parent::boot();

        static::created(function($tattoo){
            $features = collect([
                "Old school", "New School", "Bold Line", "Neo Tradicional", "Tribal", "Oriental", 
                "Pontilhismo e Geométrico", "Biomecânico", "Aquarela", "Blackwork", "Lettering",
                "Realismo e Portrait", "Preto e Cinza"
            ]);
            $featureIds = [];
            $newFeatures = [];
            $num = rand(0,5);
            for($i = 0; $i < $num; $i++){
                $newFeatures[] = $features->get(rand(0, $features->count() - 1), "Old school");
            };

            foreach($newFeatures as $feature){
                $feature = TattooFeature::firstOrCreate([
                    'name' => $feature
                ]);
    
                $featureIds[] = $feature->id;
            }

            $tattoo->features()->sync($featureIds ?? []);
        });

        static::deleting(function ($photo){
            Storage::delete($photo->path);
            $photo->features()->sync([]);
        });
    }

    public function setPathAttribute($photo)
    {
        $path = implode('/',['tattoos', $photo]);
        if(Storage::move(implode('/',[config('upload.tmp_folder'), $photo]), $path)){
            $this->attributes['path'] = $path;
        }
    }

    public function suspect()
    {
        return $this->belongsTo(Suspect::class);
    }

    public function features()
    {
        return $this->belongsToMany(TattooFeature::class);
    }
}
