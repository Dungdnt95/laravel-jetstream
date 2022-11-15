<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Factory;
use App\Enums\RoleType;

class Admin
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
        if (! Auth::guard('admin')->check() || Auth::guard('admin')->user()->type != RoleType::ADMIN) {
            return redirect(route('login.index', ['url_redirect' => url()->full()]));
        }
        return $next($request);
    }
}
