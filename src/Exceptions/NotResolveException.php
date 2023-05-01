<?php
declare(strict_types=1);

namespace Esiteks\Resolver\Exceptions;

use Exception;

class NotResolveException extends Exception{
    public function __construct(){
        parent::__construct("Not found");
    }
}