<?php

namespace App\Http\Middleware;

use App\Enums\RoleType;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Factory;

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
        view()->share('isAdmin', Auth::guard('admin')->user()->type == RoleType::ADMIN);

        return $next($request);
    }
}
