<?php
declare(strict_types=1);

namespace Esiteks\Router\Interfaces;

interface RouteInterface{
     /**
     * Get the route name
     */
    public function __toString() : string;

    public function __construct(string $prefix, string $uri, string $method, mixed $callback);

    /**
     * Set the route name
     */
    public function name(string $name) : RouteInterface;

    /**
     * Get the route name
     */
    public function getName() : string;

    /**
     * Get the full uri
     */
    public function getUri() : string;

    /**
     * Get the HTTP method
     */
    public function getMethod() : string;

    /**
     * Get the callback
     */
    public function getCallback() : mixed;

    /**
     * Get the Path as PathInterface object
     */
    public function getRoutePath() : PathInterface;

    /**
     * Matches a PathInterface with the URL of the route, returning the parameters of the variables to build the url.
     * Returns an array with the replaced variables or an empty array if there are no matches with the variables, on success. 
     * Returns null if the entered PathInterface does not match
     */
    public function matchAndGetArgs( PathInterface $uri ) : ?array;
}