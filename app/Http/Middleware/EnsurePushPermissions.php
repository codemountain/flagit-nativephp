<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Native\Mobile\Facades\SecureStorage;
use Native\Mobile\Facades\System;
use Symfony\Component\HttpFoundation\Response;

class EnsurePushPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! System::isMobile() && config('app.env') == 'local') {
            return $next($request);
        }


        if ( empty(SecureStorage::get('push_requested')) || ! SecureStorage::get('push_requested')) {
            return redirect()->route('pushnotifications');
        }
        // we already requestd the push notification, user can reset in the settings
        return $next($request);
    }
}
