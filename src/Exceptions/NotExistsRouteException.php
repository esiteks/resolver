<?php

namespace Esiteks\Router\Exceptions;

use Exception;

class NotExistsRouteException extends Exception{
    public function __construct(){
        parent::__construct("The route not exists");
    }
}