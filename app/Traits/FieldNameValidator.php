<?php

namespace App\Traits;

trait FieldNameValidator
{
    protected static function validateFieldName(string $field)
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $field)) {
            throw new \Exception('Invalid field name');
        }
    }
}