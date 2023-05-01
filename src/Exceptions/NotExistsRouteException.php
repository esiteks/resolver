<?php

namespace Esiteks\Resolver\Exceptions;

use Exception;

class NotExistsRouteException extends Exception{
    public function __construct(){
        parent::__construct("The route not exists");
    }
}