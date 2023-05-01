<?php

namespace Esiteks\Resolver\Classes;

use Esiteks\Contracts\Resolver\ResolveInterface;

class Resolve implements ResolveInterface{

    public function __construct(
        protected mixed $callback,
        protected array $args = []
    ){}

    public function getCallback() : mixed{
        return $this->callback;
    }

    public function getArgs() : array{
        return $this->args;
    }

}