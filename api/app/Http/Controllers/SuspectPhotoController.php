<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suspect;
use App\Http\Resources\SuspectResource;
use Illuminate\Support\Facades\Storage;

class SuspectPhotoController extends Controller
{
    public function store(Suspect $suspect, Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'filled|string' 
        ]);

        foreach($request->photos as $photo){
            $suspect->photos()->create([
                'path' => $photo
            ]);
        }

        return SuspectResource::make($suspect->fresh());
    }

    public function destroy(Suspect $suspect, $photoId)
    {
        $suspect->photos()->findOrFail($photoId)->delete();

        return SuspectResource::make($suspect->fresh());
    }
}
