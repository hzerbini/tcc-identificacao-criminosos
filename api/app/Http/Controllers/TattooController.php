<?php

namespace App\Http\Controllers;

use App\Http\Resources\TattooResource;
use Illuminate\Http\Request;
use App\Models\Tattoo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use App\Models\TattooFeature;

class TattooController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'features' => 'array',
            'features.*' => 'string'
        ]);
        $features = Arr::wrap($request->features);
        if($features){
            $featureIds = TattooFeature::whereIn('name', $features)->pluck('id');
        
            $tattooIds = $tattooIds = Db::table('tattoo_tattoo_feature')
                ->whereIn('tattoo_feature_id', $featureIds)
                ->groupBy('tattoo_id')
                ->havingRaw('COUNT(*) >= ' . count($features))
                ->pluck('tattoo_id');

            $tattoos = Tattoo::orderBy('id')->whereIn('id', $tattooIds)->paginate(10);
        }

        return TattooResource::collection(Tattoo::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
