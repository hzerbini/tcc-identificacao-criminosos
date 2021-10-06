<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Http\Resources\AlertResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return AlertResource::collection($user->alerts()->with('user')->orderBy('read_at', 'desc')->orderBy('created_at', 'desc')->paginate(20));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'title' => 'required|string|max:64',
            'message' => 'required|string|max:1024'
        ]);

        return AlertResource::make($user->alerts()->create($request->only('title', 'message')));
    }
}
