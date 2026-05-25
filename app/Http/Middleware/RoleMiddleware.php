<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response 
        { 
                 // Ih check of ang user kung naka log in ba
                 if (!auth()->check()) {
                      return redirect('/login');
                 }

                 // Ih check if ang user role kung ga match kung asa ra kutob iyang allowed roles
                 if (!in_array(auth()->user()->role, $roles)) {
                      abort(403, 'Unauthorized. You do not have access to this page.');
                  }
                  
                 return $next($request); } 

}
