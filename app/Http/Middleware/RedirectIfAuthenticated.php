<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                $user = auth()->user();

                if (!$user->active) {
                    // Encrypt the email before adding it to the URL
                    $encryptedEmail = Crypt::encryptString($user->email);

                    $signedUrl = URL::temporarySignedRoute(
                        'homepage-verifyEmail',
                        now()->addMinutes(10),
                        ['email' => $encryptedEmail] // Store encrypted email
                    );

                    return redirect($signedUrl);
                }
                // return redirect()->route('homepage-homepage');
            }
        }

        return $next($request);
    }
}
