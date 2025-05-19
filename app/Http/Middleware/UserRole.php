<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Redirect based on user role, only if not already on designated route
            $role = session('role', auth()->user()->user_role);

            // if (!auth()->check()) {
            //     Log::error('User is not authenticated.');
            //     return redirect()->route('homepage-homepage');
            // }

            // // Debugging for production
            // if (empty(auth()->user())) {
            //     Log::error('auth()->user() is empty.');
            // } else {
            //     Log::info('Authenticated user:', ['user' => auth()->user()]);
            // }

            // Log the role for debugging purposes

            // Redirect based on the role
            if ($role === 'Owner' && !$request->is('owner/*')) {
                return redirect()->route('owner-dashboard');
            } elseif ($role === 'Admin' && !$request->is('admin/*')) {
                return redirect()->route('admin-dashboard');
            } elseif ($role === 'Front Desk - Hotel' && !$request->is('hotel/*')) {
                return redirect()->route('hotel-dashboard');
            } elseif ($role === 'Front Desk - Resort' && !$request->is('resort/*')) {
                return redirect()->route('resort-dashboard');
            }
            // elseif ($role === 'Customer' && !$request->is('guest/*')) {
            //     return redirect()->route('homepage-homepage');
            // }

            // Allow request to proceed if already on the correct route
            return $next($request);
        }

        return redirect()->route('homepage-homepage');
    }
}
