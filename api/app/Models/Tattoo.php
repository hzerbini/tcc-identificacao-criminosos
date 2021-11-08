<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\TattooFeature;
use Carbon\Carbon;
use App\Events\FeaturesOfTattooUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class Tattoo extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['features'];



    

    public static function boot(){
        parent::boot();

        static::created(function($tattoo){
            $user = request()->user();

            dispatch(function() use ($tattoo, $user) {
                Artisan::call('tattoo:elementRecognition', [
                    'tattooId' => $tattoo->id
                ]);

                $user->alerts()->create([
                    'title' => 'Tatuagem (' . $tattoo->id . ') foi avaliada com sucesso',
                    'message' => 'A tatuagem com id ' . $tattoo->id . 
                    ' foi avaliada com sucesso pelo nosso sistema, você já pode ver os resultados na página do suspeito com nome' .
                    $tattoo->suspect->name . ' (' .
                    $tattoo->suspect->id . ')'
                ]);
    
                $tattoo->checkSearchesAndSendAlerts();
            });
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

    public function checkSearchesAndSendAlerts()
    {
        $tattoo = $this->fresh();
        $searchIds = DB::table('saved_suspect_search_tattoo_feature')->whereIn('tattoo_feature_id', $tattoo->features->pluck('id'))
            ->groupBy('saved_suspect_search_id')
            ->selectRaw('saved_suspect_search_id, count(*) as count_features')
            ->get()
            ->filter(function($searchItems) use ($tattoo){
                return $searchItems->count_features <= $tattoo->features->count();
            })
            ->pluck('saved_suspect_search_id');

        SavedSuspectSearch::whereIn('id', $searchIds)->chunk(100, function($savedSuspectSearches) use ($tattoo){
            foreach($savedSuspectSearches as $savedSuspectSearch){
                User::find($savedSuspectSearch->user_id)
                    ->alerts()
                    ->create([
                        'title' => 
                            'Pesquisa Encontrada: "' . $savedSuspectSearch->name . '"',
                        'message' => 'Link para suspeito é: ' . config('app.url') . '/suspeitos/' . ((string) $tattoo->suspect_id )
                    ]);
            }

        });
    }
}
