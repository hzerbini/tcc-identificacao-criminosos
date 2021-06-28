<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;

class UserPhotoController extends Controller
{
    public function store(User $user, Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'filled|string' 
        ]);

        foreach($request->photos as $photo){
            $user->photos()->create([
                'path' => $photo
            ]);
        }

        return UserResource::make($user->fresh());
    }

    public function destroy(User $user, $photoId)
    {
        $user->photos()->findOrFail($photoId)->delete();

        return UserResource::make($user->fresh());
    }
}
