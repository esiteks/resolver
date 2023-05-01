<?php
declare(strict_types=1);

namespace Esiteks\Resolver\Classes;

use Esiteks\Contracts\Resolver\PathInterface;

class Path implements PathInterface{
    protected string $uri;
    protected array $path; 

    public function __construct( string $uri ){
        self::cleanUri($uri);

        $this->uri = $uri;        
        $this->path = explode('/', $this->uri );
    }

    public function getUri() : string{
        return $this->uri;
    }
   
    public function getPath(bool $slashes = true ) : array{
        $_path = $this->path;
        if( $slashes )
            foreach( $_path as $key => $item )
                $_path[$key] = '/'.$item;

        return $_path;
    }
    
    public static function matchReplace( array $replace, PathInterface $path) : string{
        
        $_path = $path->getPath(false);

        foreach( $_path as $pathKey => $p ){   
            if( preg_match( self::PATTERN, $p ) ){   
                foreach( $replace as $key => $item ){                          
                    if( $key == $p )
                        $_path[ $pathKey ] = $item;
                }
            }
        }

        $_path = implode('/', $_path );
        return $_path;
    }

    public static function cleanUri(string &$uri) : void{
        $uri = str_replace( ["\n", "\r". "\f", "\t", " "], '', $uri ); 
        $uri = trim($uri, "/");
        $uri = strtolower("/$uri");
        $uri = parse_url($uri, PHP_URL_PATH );
    }
}