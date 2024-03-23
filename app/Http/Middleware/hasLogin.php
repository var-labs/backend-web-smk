<?php

namespace App\Http\Middleware;

use App\Models\tb_admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class hasLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = session("user")??null;
        $token = $request->route("token");
        Log::info($email);
        $user = tb_admin::where('token', $token)
            ->first();
        if(!$email == null) {
            if($email->email != $user->email) {
                return redirect()->route("guest.token");
            }
        }
        if(session("status") != "true"){
            return redirect()->route("guest.token");
        }
        return $next($request);
    }
}
