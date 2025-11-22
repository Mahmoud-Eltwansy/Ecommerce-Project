<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CheckApiKey
{

    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('x-api-key');
        if ($token !== config('app.api_key')) {
            return Response::json([
                'message' => 'Invalid Api Key'
            ], 400);
        }
        return $next($request);
    }
}
