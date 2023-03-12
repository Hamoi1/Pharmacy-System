<?php

namespace App\Http\Middleware;

use App\Models\Logs;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class DeleteUserLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $logs  = Logs::select('id', 'created_at')->get();
        $logs->each(function ($log) {
            if ($log->created_at <= now()->subMonth()) {
                $log->delete();
            }
        });
        return $next($request);
    }
}
