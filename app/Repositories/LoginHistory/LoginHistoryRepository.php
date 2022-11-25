<?php

namespace App\Repositories\LoginHistory;

use App\Models\LoginHistory;
use App\Repositories\LoginHistory\LoginHistoryInterface;
use Illuminate\Support\Facades\Auth;

class LoginHistoryRepository implements LoginHistoryInterface
{
    private LoginHistory $loginHistory;

    public function __construct(LoginHistory $loginHistory)
    {
        $this->loginHistory = $loginHistory;
    }

    public function get($request)
    {
        // TODO: Implement get() method.
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function store($request)
    {
        $loginHistory = new $this->loginHistory();
        if (Auth::guard('admin')->check()) {
            $loginHistory->admin_id = Auth::guard('admin')->user()->id;
        } else {
            $loginHistory->user_id = Auth::guard('user')->user()->id;
        }
        $loginHistory->login_ip = $request->ip();
        $loginHistory->user_agent = $request->userAgent();

        return $loginHistory->save();
    }

    public function update($request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
