<?php

namespace App\Services;

include '../bootstrap/config.php';

class userService
{

    public function getUserById($id)
    {
        global $usersList;
        foreach ($usersList as $user)
            if ($user->id == $id)
                return $user;
        return null;
    }

    public function getUserByEmail($email)
    {
        global $usersList;
        foreach ($usersList as $user)
            if (strtolower($user->email) == strtolower($email))
                return $user;
        return null;
    }
}
