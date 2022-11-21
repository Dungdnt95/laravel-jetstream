<?php

namespace App\Repositories\User;

interface UserInterface
{
    public function get($request);

    public function getById($id);

    public function store($request);

    public function update($request, $id);

    public function destroy($id);

    public function saveLoginHistory();

    public function forgotPassword($request);

    public function getUserByEmail($request);

    public function getUserByToken($token);

    public function updatePasswordByToken($request, $token);

    public function checkEmail($request);
}
