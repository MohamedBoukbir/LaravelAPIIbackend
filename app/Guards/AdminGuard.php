<?php
// app/Guards/AdminGuard.php

namespace App\Guards;

use Illuminate\Auth\SessionGuard;

class AdminGuard extends SessionGuard
{
    /**
     * Determine if the current user has the "admin" role.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->user() && $this->user()->role === 'admin';
    }
}