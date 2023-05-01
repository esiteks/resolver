<?php 
declare(strict_types=1);

namespace Esiteks\Resolver;

use Esiteks\Resolver\Classes\Path;
use Esiteks\Resolver\Classes\Resolve;

use Esiteks\Resolver\Exceptions\NotResolveException;

use Esiteks\Contracts\Resolver\ResolveInterface;
use Esiteks\Contracts\Resolver\RouteResolverInterface;
use Esiteks\Contracts\Resolver\RouteCollectionInterface;
use Esiteks\Resolver\Classes\ResolverResult;

class Resolver implements RouteResolverInterface{
    public function resolve( string $requestUri, string $method, RouteCollectionInterface $collection) : ResolveInterface {   
        $routes = $collection->getRoutes();
        if( empty( $routes ) ) throw new NotResolveException();      

        $method = trim( strtoupper( $method ) );               
        $path = new Path( $requestUri );        
        
        foreach( $routes as $route ){ 
            if( $route->getMethod() != $method )
                continue;
            
            if( $route->getUri() == $path->getUri() ){   
                return new ResolverResult($route->getCallback());             
            }else{
                $matchArgs = $route->matchAndGetArgs( $path );
                if( !is_array( $matchArgs ) ) continue; 

                return new ResolverResult( $route->getCallback(), $matchArgs);                
            }
            
        }

        throw new NotResolveException();
    }
}