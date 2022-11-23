<?php

namespace App\Repositories\User;

use App\Components\CommonComponent;
use App\Mail\ForgotPassComplete;
use App\Mail\ForgotPassword;
use App\Models\User;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserRepository implements UserInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function get($request)
    {
        $newSizeLimit = CommonComponent::newListLimit($request);
        $userBuilder = $this->user;
        if (isset($request['free_word']) && $request['free_word'] != '') {
            $userBuilder = $userBuilder->where(function ($q) use ($request) {
                $q->orWhere(CommonComponent::escapeLikeSentence('name', $request['free_word']));
                $q->orWhere(CommonComponent::escapeLikeSentence('email', $request['free_word']));
            });
        }
        $users = $userBuilder->sortable(['updated_at' => 'desc'])->paginate($newSizeLimit);
        if (CommonComponent::checkPaginatorList($users)) {
            Paginator::currentPageResolver(function () {
                return 1;
            });
            $users = $userBuilder->paginate($newSizeLimit);
        }

        return $users;
    }

    public function store($request)
    {
        $this->user->name = $request->name;
        $this->user->email = $request->email;
        $this->user->password = Hash::make($request->password);

        return $this->user->save();
    }

    public function getById($id)
    {
        return $this->user->where('id', $id)->first();
    }

    public function update($request, $id)
    {
        $userInfo = $this->user->where('id', $id)->first();
        if (! $userInfo) {
            return false;
        }
        $userInfo->name = $request->name;
        $userInfo->email = $request->email;
        if ($request->password) {
            $userInfo->password = Hash::make($request->password);
        }

        return $userInfo->save();
    }

    public function destroy($id)
    {
        $user = $this->user->where('id', $id)->first();
        if (! $user) {
            return false;
        }

        return $user->delete();
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

    public function checkEmail($request)
    {
        return ! $this->user->where(function ($query) use ($request) {
            if (isset($request['id'])) {
                $query->where('id', '!=', $request['id']);
            }
            $query->where(['email' => $request['value']]);
        })->exists();
    }
}
