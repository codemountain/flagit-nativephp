<?php

namespace App\Http\Middleware;

use App\Services\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Laravel\SerializableClosure\Serializers\Native;
use Native\Mobile\Facades\System;
use Native\Mobile\Facades\SecureStorage;
use Symfony\Component\HttpFoundation\Response;

class EnsureGeopermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        if(!System::isMobile() && config('app.env')=='local') {
//            return $next($request);
//        }
//            dd(SecureStorage::get('location_permission'));
        if (!SecureStorage::get('location_permission')) {
            return redirect()->route('geolocation');
        }

        return $next($request);
    }
}
