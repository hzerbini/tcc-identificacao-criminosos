<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Alert;
use App\Models\Suspect;
use App\Models\User;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;

class BouncerController extends Controller
{
    public function __construct()
    {
        $this->possiblePermissions = collect([
            'manageUsers' => function($bouncerInstance){
                $bouncerInstance->toManage(User::class);
            },
            'managePermissions' => function($bouncerInstance){
                $bouncerInstance->to('managePermissions');
            },
            'manageSuspects' => function($bouncerInstance){
                $bouncerInstance->toManage(Suspect::class);
            },
            'manageAlerts' => function($bouncerInstance){
                $bouncerInstance->toManage(Alert::class);
            }
        ]);
    }

    public function store(User $user, Request $request)
    {
        $request->validate([
            'permission' => 'required|string|in:' . $this->possiblePermissions->keys()->join(','),
        ]);

        $this->possiblePermissions->get($request->permission)(Bouncer::allow($user));

        return UserResource::make($user);
    }

    public function destroy(User $user, Request $request)
    {
        $request->validate([
            'permission' => 'required|string|in:' . $this->possiblePermissions->keys()->join(','),
        ]);

        $this->possiblePermissions->get($request->permission)(Bouncer::disallow($user));

        return UserResource::make($user);
    }
}
