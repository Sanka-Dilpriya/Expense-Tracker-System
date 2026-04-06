<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLoginForTesting
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we're in a GitHub Codespaces environment
        $host = $request->getHost();
        if (str_contains($host, 'app.github.dev') && !Auth::check()) {
            // Auto-login as admin user for testing
            $user = User::where('email', 'admin@example.com')->first();
            if ($user) {
                Auth::login($user);
            }
        }

        return $next($request);
    }
}
