<?php

namespace App\Http\Middleware;

use Closure;

class RealIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //访客IP
        $client_ip = $request->getClientIp();
        //
        $user_agent = $request->header("user-agent");
        //
        $uri = $request->getUri();

        $file_path = storage_path("logs/visit_log/" . date("Y-m-d") . '.log');

        $dir = dirname($file_path);

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $log = "[" . date("YmdHis") . "] - " . $client_ip . " - " . $uri . " - " . $user_agent . "\n";

        file_put_contents($file_path, $log, FILE_APPEND);

        return $next($request);
    }
}
