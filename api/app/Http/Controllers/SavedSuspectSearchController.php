<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SavedSuspectSearch;
use App\Http\Resources\SavedSuspectSearchResource;

class SavedSuspectSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SavedSuspectSearchResource::collection(SavedSuspectSearch::with(['user', 'tattooFeatures'])->paginate(20));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SavedSuspectSearch $savedSuspectSearch)
    {
        $savedSuspectSearch->delete();

        return response()->noContent();
    }
}
