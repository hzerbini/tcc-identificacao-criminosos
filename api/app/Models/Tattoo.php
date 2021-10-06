<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\TattooFeature;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class Tattoo extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['features'];

    public static function boot(){
        parent::boot();

        static::created(function($tattoo){
            Artisan::call('tattoo:elementRecognition', [
                'tattooId' => $tattoo->id
            ]);
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
