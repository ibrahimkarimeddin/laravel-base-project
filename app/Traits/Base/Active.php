<?php

namespace App\Traits\Base;

trait Active
{
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
