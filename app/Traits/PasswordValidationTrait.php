<?php

namespace App\Traits;

trait PasswordValidationTrait
{
    public static function passwordValidate($pass): bool
    {
        if (strlen($pass) < 8) {
            return false;
        }

        if (!preg_match('/[A-Z]/', $pass)) {
            return false;
        }

        if (!preg_match('/[a-z]/', $pass)) {
            return false;
        }

        if (!preg_match('/[0-9]/', $pass)) {
            return false;
        }

        if (!preg_match('/[\W_]/', $pass)) {
            return false;
        }

        return true;
    }
}
