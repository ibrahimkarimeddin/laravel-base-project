<?php

namespace App\Traits\Auth;

use Illuminate\Support\Facades\Hash;

trait CheckPassword
{
    public function checkPassword($password)
    {
        return Hash::check($password, $this->password);
    }
}
