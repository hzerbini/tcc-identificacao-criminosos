<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Http\Resources\AlertResource;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;


class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AlertResource::collection(Alert::with('user')->orderBy('created_at', 'desc')->paginate(20));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Alert $alert)
    {
        if($alert->user_id === $request->user()->id && $alert->read_at == null){
            $alert->read_at = now();
            $alert->save();
        }

        return AlertResource::make($alert);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alert $alert)
    {
        $request->validate([
            'title' => 'string|max:64',
            'message' => 'string|max:1024',
            'isnt_read' => 'boolean'
        ]);
        
        if($request->hasAny('title', 'message')){
            Bouncer::authorize('update', $alert);
        }


        $data = $request->only('title', 'message');

        if($request->isnt_read){ 
            $data = data_set($data, 'read_at', null);
        }

        $alert->fill($data)->save();

        return AlertResource::make($alert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alert $alert)
    {
        $alert->delete();

        return response()->noContent();
    }
}
