<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ?string $role = null)
    {
        if (!session()->has('user_id') || !session()->has('user')) {
            return redirect('/login')->withErrors(['auth' => 'Please log in to continue.']);
        }

        if ($role !== null && session('user')->role !== $role) {
            abort(403);
        }

        // Track activity for the admin dashboard, at most one write every 5 minutes.
        $lastWrite = session('last_activity_write');
        if (!$lastWrite || now()->diffInMinutes($lastWrite) >= 5) {
            \App\Models\User::where('id', session('user_id'))->update(['last_activity_at' => now()]);
            session(['last_activity_write' => now()]);
        }

        $response = $next($request);

        // Only Illuminate responses have withHeaders(); streamed and file
        // downloads are plain Symfony responses, so set the headers directly.
        $noCache = [
            'Cache-Control' => 'no-cache, no-store, must-revalidate, private',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ];

        if (method_exists($response, 'withHeaders')) {
            return $response->withHeaders($noCache);
        }

        foreach ($noCache as $header => $value) {
            $response->headers->set($header, $value);
        }

        return $response;
    }
}
