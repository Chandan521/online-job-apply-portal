<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\BannedIp;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockBannedIPs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        if (BannedIp::where('ip_address', $ip)->exists()) {
            abort(403, "Access denied. Your IP has been banned.");
        }
        return $next($request);
    }
}
