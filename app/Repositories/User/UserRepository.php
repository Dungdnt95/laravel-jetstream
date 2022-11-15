<?php

namespace App\Repositories\User;

use App\Http\Controllers\BaseController;
use App\Mail\ForgotPassComplete;
use App\Mail\ForgotPassword;
use App\Models\User;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserRepository extends BaseController implements UserInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
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
        // TODO: Implement store() method.
    }

    public function update($request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function saveLoginHistory()
    {
        $userInfo = $this->user->where('id', Auth::guard('admin')->user()->id)->first();
        $userInfo->last_login_at = Carbon::now();

        return $userInfo->save();
    }

    public function forgotPassword($request)
    {
        $account = $this->getUserByEmail($request);
        if (! $account) {
            return false;
        }
        $account->reset_password_token = md5($request->email.random_bytes(25).Carbon::now());
        $account->reset_password_token_expire = Carbon::now()->addMinutes(env('FORGOT_PASS_EXPIRED', 30));
        if (! $account->save()) {
            return false;
        }
        $mailContents = [
            'data' => [
                'name' => $account->name,
                'link' => route('init-password.show', $account->reset_password_token),
            ],
        ];
        Mail::to($account->email)->send(new ForgotPassword($mailContents));

        return true;
    }

    public function getUserByEmail($request)
    {
        return $this->user->where('email', $request->email)->first();
    }

    public function getUserByToken($token)
    {
        return $this->user->where([
            ['reset_password_token', $token],
            ['reset_password_token_expire', '>=', Carbon::now()],
        ])->first();
    }

    public function updatePasswordByToken($request, $token)
    {
        $account = $this->getUserByToken($token);
        if (! $account) {
            return false;
        }
        $account->password = Hash::make($request->password);
        $account->reset_password_token = null;
        $account->reset_password_token_expire = null;
        if (! $account->save()) {
            return false;
        }
        $mailContents = [
            'name' => $account->name,
            'mail' => $account->email,
        ];
        Mail::to($account->email)->send(new ForgotPassComplete($mailContents));

        return true;
    }
}
