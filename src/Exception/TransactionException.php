<?php

namespace App\Exception;

use Exception;
use Throwable;

class TransactionException extends Exception implements Throwable
{
    private $status;

    public function __construct($message, $status = 0)
    {
      $this->message = $message;
      $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
