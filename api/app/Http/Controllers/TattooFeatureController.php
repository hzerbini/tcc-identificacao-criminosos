<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TattooFeature;
use App\Http\Resources\TattooFeatureResource;
use Illuminate\Support\Str;

class TattooFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string'
        ]);
        $search = Str::slug($request->search);

        $tattoos = TattooFeature::where('name_slug', 'ILIKE', "%$search%")->paginate(10);

        return TattooFeatureResource::collection($tattoos);
    }
    
}
