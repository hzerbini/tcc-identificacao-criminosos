<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TattooFeature;
use App\Http\Resources\SavedSuspectSearchResource;
use Illuminate\Support\Str;

class UserSavedSuspectSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return SavedSuspectSearchResource::collection($user->savedSuspectSearches()->with('tattooFeatures')->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $featureIds = collect([]);
        $request->validate([
            'name' => 'required|string|max:64',
            'description' => 'required|string|max:255',
            'tattooFeatures' => 'required|array',
            'tattooFeatures.*' => 'string|max:255'
        ]);

        $suspectSearch = $user->savedSuspectSearches()->create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        foreach($request->tattooFeatures as $featureName){
            $tattooFeature = TattooFeature::firstOrCreate([
                'name_slug' => Str::slug($featureName)
            ],[
                'name' => $featureName
            ]);

            $featureIds->add($tattooFeature->id);
        }

        $suspectSearch->tattooFeatures()->sync($featureIds);

        $suspectSearch->tattooFeatures;

        return SavedSuspectSearchResource::make($suspectSearch);
    }
}
