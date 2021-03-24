<?php

namespace App\Exception;

use Exception;
use Throwable;
use Symfony\Component\Validator\ConstraintViolation;

class ValidationException extends Exception implements Throwable
{
    private $errors;

    public function __construct($errors)
    {
        /** @var ConstraintViolation[] $errors */
        $errors = $errors;
        foreach ($errors as $value) {
            $this->errors[$value->getPropertyPath()][] = $value->getMessage();
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
