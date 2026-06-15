<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $method = $request->method();
            $path = $request->path();
            
            // Log modifying requests that are not Livewire updates
            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']) && !str_contains($path, 'livewire/update')) {
                SystemLog::create([
                    'level' => 'info',
                    'message' => "ຮ້ອງຂໍການປ່ຽນແປງຜ່ານ HTTP: {$method} /{$path}",
                    'user_id' => Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'context' => [
                        'method' => $method,
                        'path' => $path,
                        'input' => $request->except(['password', '_token', 'password_confirmation']),
                    ]
                ]);
            }
        }

        return $response;
    }
}
