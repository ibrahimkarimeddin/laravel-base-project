<?php

namespace App\Traits\Model;

trait Active
{
    public function __construct() {
        dd($this->tranlation);
    }
    public function checkPassword()
    {
        return $this->where('is_active', 1);
    }
}
