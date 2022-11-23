<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\View\Factory;

class Lp
{
    public function __construct(Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        view()->share('isLp', 1);

        return $next($request);
    }
}
