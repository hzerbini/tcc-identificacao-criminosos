<?php

namespace App\Http\Controllers;

use App\Models\Suspect;
use Illuminate\Http\Request;
use App\Http\Resources\SuspectResource;

class SuspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SuspectResource::collection(Suspect::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|cpf|unique:suspects,cpf',
            'birth_date' => 'required|date'
        ]);

        $suspect = Suspect::create($request->only(['name', 'cpf', 'birth_date']));


        return SuspectResource::make($suspect);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Suspect  $suspect
     * @return \Illuminate\Http\Response
     */
    public function show(Suspect $suspect)
    {
        return SuspectResource::make($suspect);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Suspect  $suspect
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Suspect $suspect)
    {
        $request->validate([
            'name' => 'filled|string|max:255',
            'cpf' => 'filled|string|cpf' . (($suspect->cpf != $request->cpf)?"|unique:suspects,cpf":""),
            'birth_date' => 'filled|date'
        ]);

        $suspect->fill($request->only(['name', 'cpf', 'birth_date']))->save();

        return SuspectResource::make($suspect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Suspect  $suspect
     * @return \Illuminate\Http\Response
     */
    public function destroy(Suspect $suspect)
    {
        $suspect->delete();

        return response()->noContent();
    }
}
