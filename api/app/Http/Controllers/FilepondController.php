<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class FilepondController extends Controller
{
    public function process(Request $request)
    {
        $key = collect($request->keys())->filter(function($key){
            return ! in_array($key, ['_method', '_token']); 
        })->first();

        if($request->hasFile($key)){
            $file = $request->file($key);
            $path = Storage::put(config('upload.temp_folder'), $file);
            $path = collect(explode('/', $path))->last();
            return $path;
        }

        return '';
    }

    public function revert(Request $request)
    {
        $request->validate([
            'id' => 'required|string'
        ]);

        $clearedFilename = str_replace('/', '', $request->id);
        Storage::delete(implode('/', [config('upload.temp_folder'), $clearedFilename]) );

        return response()->noContent();
    }

    public function restore(Request $request){
        $request->validate([
            'id' => 'required|string'
        ]);

        $clearedFilename = str_replace('/', '', $request->id);

        return Storage::download(implode('/', [config('upload.temp_folder'), $clearedFilename]));
    }
}
