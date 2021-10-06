<?php

use App\Models\Alert;
use Illuminate\Support\Facades\Broadcast;
use PhpParser\Node\Expr\FuncCall;
use Silber\Bouncer\BouncerFacade as Bouncer;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('alerts.{userId}', function ($user, $userId) {

    return (int) $user->id === (int) $userId || Bouncer::can('viewAll', Alert::class);
});

Broadcast::channel('alerts', function ($user) {

    return Bouncer::can('viewAll', Alert::class);
});