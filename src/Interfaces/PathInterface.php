<?php
declare(strict_types=1);

namespace Esiteks\Resolver\Interfaces;

interface PathInterface {

    public const PATTERN = '#^:{1}([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$#';    

    /**
     * Build a Path Interface from a url
     */
    public function __construct( string $uri );

    /**
     * Get the full uri
     */
    function getUri() : string;

    /**
     * Get path as an array indicating if you want it with slaches or not
     */
    function getPath(bool $slashes = true ) : array;

    /**
     * Static method that returns the uri replaced with an associative array   
     */
    static function matchReplace( array $replace, PathInterface $path);

    static function cleanUri(string &$uri) : void;
}