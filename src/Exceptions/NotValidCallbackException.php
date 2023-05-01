<?php
declare(strict_types=1);

namespace Esiteks\Resolver\Exceptions;

use Exception;

class NotValidCallbackException extends Exception{
    public function __construct(){
        parent::__construct("The callback is not a valid callable function or method");
    }
}