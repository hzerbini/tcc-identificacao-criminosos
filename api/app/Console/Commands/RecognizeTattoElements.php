<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tattoo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\TattooFeature;
use Illuminate\Support\Str;

class RecognizeTattoElements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tattoo:elementRecognition {tattooId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para fazer reconhecimento dos elementos presentes na tatuagem';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $featureIds = [];
        $tattoo = Tattoo::findOrFail($this->argument('tattooId'));
        
        $url = str_replace('//','http://',Storage::temporaryUrl($tattoo->path, now()->addSeconds(120)));

        $response = Http::get('python:5000/', [
            "url" => $url
        ]);

        $tattooElements = collect($response->json()['data'])->unique()->map(function($element){
            return collect([
                "aeroplane" => "avião",
                "bicycle" => "bicicleta",
                "bird" => "pássaro",
                "boat" => "barco",
                "bottle" => "garrafa",
                "bus" => "ônibus",
                "car" => "carro",
                "cat" => "gato",
                "chair" => "cadeira",
                "cow" => "vaca",
                "diningtable" => "mesa de jantar",
                "dog" => "cachorro",
                "horse" => "cavalo",
                "motorbike" => "moto",
                "person" => "pessoa",
                "pottedplant" => "vaso de planta",
                "sheep" => "ovelha",
                "sofa" => "sofa",
                "train" => "trem",
                "tvmonitor" => "monitor de tv"
            ])->get($element, $element);
        });
        
        foreach($tattooElements as $tattooElement){
                $feature = TattooFeature::firstOrCreate([
                    'name_slug' => Str::slug($tattooElement)
                ],[
                    'name' => $tattooElement
                ]);
    
                $featureIds[] = $feature->id;
        }

        $tattoo->features()->sync($featureIds ?? []);

        return 0;
    }


}
