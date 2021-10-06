<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;


class IfNotUserAuthorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $user, $ability, $model = null)
    {
        $user = $request->route($user);
        if($user->id != $request->user->id){
            Bouncer::authorize($ability, $model);
        }

        return $next($request);
    }
}
