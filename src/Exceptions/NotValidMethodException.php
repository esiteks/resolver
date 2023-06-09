<?php
declare(strict_types=1);

namespace Esiteks\Resolver\Exceptions;

use Exception;

class NotValidMethodException extends Exception{
    public function __construct($method){
        parent::__construct("Method \"$method\" not allowed");
    }
}