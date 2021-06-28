<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;
    
    
    protected $guarded = [];
    
    public static function boot(){
        parent::boot();

        static::deleting(function ($photo){
            Storage::delete($photo->path);
        });
    }

    public function setPathAttribute($photo)
    {
        $path = implode('/',['photos', $photo]);
        if(Storage::move(implode('/',[config('upload.tmp_folder'), $photo]), $path)){
            $this->attributes['path'] = $path;
        }
    }

    public function photable()
    {
        return $this->morphTo();
    }
}
