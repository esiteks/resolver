<?php
declare(strict_types=1);

namespace Esiteks\Router\Classes;

use Esiteks\Router\Classes\Path;
use Esiteks\Router\Exceptions\NotValidCallbackException;
use Esiteks\Router\Exceptions\NotValidMethodException;
use Esiteks\Router\Interfaces\PathInterface;
use Esiteks\Router\Interfaces\RouteInterface;

final class Route implements RouteInterface  {
    protected $name;
    protected Path $path;
    protected $method;
    protected mixed $callback;  
    
    private const METHODS = [
        "GET",
        "POST",
        "PUT",
        "DELETE",
        "OPTIONS",
        "PATCH",
        "HEAD"
    ];

    public function __construct(string $prefix, string $uri, string $method , mixed $callback){                
        if( !$this->checkCallback( $callback ) ) throw new NotValidCallbackException();

        if( !in_array( strtoupper( trim( $method ) ), Route::METHODS ) )
            throw new NotValidMethodException($method);

        Path::cleanUri($uri);
        Path::cleanUri($prefix);

        $this->method = strtoupper( trim( $method ) ); 
        $this->path = new Path( $prefix.$uri );
        $this->name = $this->path->getUri();

        $this->callback =  $callback;
    }

    public function __toString() : string{
        return $this->getName();
    }
    
    public function name(string $name) : RouteInterface{
        $this->name = trim($name);
        return $this;
    }

    public function getName() : string{
        return $this->name;
    }

    public function getUri() : string{
        return $this->path->getUri();
    }

    public function getMethod() : string{
        return $this->method;
    }

    public function getCallback() : mixed{
        return $this->callback;
    }

    public function getRoutePath() : PathInterface{
        return $this->path;
    }

    public function matchAndGetArgs( PathInterface $uri ) : ?array {
        $exp_route = explode('/', $this->getRoutePath()->getUri() );	
		$exp_request = explode('/', $uri->getUri() );   

        $variables = [];
        $match = false;

		if (count($exp_route) == count($exp_request) ) {	                       
			foreach ($exp_route as $key => $value) {  
				if ($value == $exp_request[$key]){
                    $match = true;
					continue;  				                 
                }else {                     
                    if( preg_match( Path::PATTERN, $value ) )
                        $variables[ str_replace(':','', $value )] = $exp_request[$key];
                    else{
                        $match = false;
                        break;
                    }
                }       
			}

            if( $match )
                return $variables;  
		}

        return null;        
	} 

    protected function checkCallback($callback) : bool{
        if( is_callable( $callback ) || is_a( $callback, Closure::class ) )
            return true;

        if( is_string( $callback ) ){
            $callback = trim($callback);

            if( preg_match( '#^([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*){1}@([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$#', $callback ) )
                $callback = explode( "@", $callback );
        }
        
        if( is_array($callback) && 
            count( $callback ) == 2 && 
            ( 
                is_string($callback[0]) && 
                is_string( $callback[1] ) 
            ) && 
            new \ReflectionMethod( $callback[0], $callback[1] )
        )
            return true;           
            

        return false;
    }
    
}