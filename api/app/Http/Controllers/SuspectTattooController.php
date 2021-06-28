<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuspectResource;
use App\Models\Suspect;
use Illuminate\Http\Request;

class SuspectTattooController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Suspect $suspect, Request $request)
    {
        $request->validate([
            'tattoos' => 'required|array',
            'tattoos.*' => 'filled|string' 
        ]);

        foreach($request->tattoos as $tattoo){
            $suspect->tattoos()->create([
                'path' => $tattoo
            ]);
        }

        return SuspectResource::make($suspect->fresh());
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Suspect $suspect, $tattooId)
    {
        $request->validate([
            'tattoo_features' => 'array',
            'tattoo_features.*' => 'filled|string|max:255'
        ]);

        $tattoo = $suspect->tattoos()->findOrFail($tattooId);
        $features = [];
        dd($features);
        foreach($request->tattoo_features as $feature){
            $feature = $tattoo->features()->firstOrCreate([
                'name' => $feature
            ]);

            $features[] = $feature->id;
        }
        $tattoo->features()->sync($features);

        return SuspectResource::make($tattoo->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Suspect $suspect, $tattooId)
    {
        $suspect->tattoos()->findOrFail($tattooId)->delete();

        return SuspectResource::make($suspect->fresh());
    }
}
