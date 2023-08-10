<?php
// app/Guards/UserGuard.php

namespace App\Guards;

use Illuminate\Auth\SessionGuard;

class UserGuard extends SessionGuard
{
    /**
     * Determine if the current user has the "user" role.
     *
     * @return bool
     */
    public function isUser()
    {
        return $this->user() && $this->user()->role === 'user';
    }

    // ... Ajoutez d'autres méthodes ou surchargez des méthodes nécessaires
}