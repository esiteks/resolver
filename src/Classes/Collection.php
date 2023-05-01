<?php

namespace Esiteks\Resolver\Classes;

use Esiteks\Contracts\Resolver\RouteCollectionInterface;
use Esiteks\Contracts\Resolver\RouteInterface;
use Esiteks\Resolver\Exceptions\NotExistsRouteException;

class Collection implements RouteCollectionInterface{    
    protected array $routes;

    public function __construct(  
        Collection $routes = null,
        public readonly string $prefix = "",        
    ){
        if( is_null( $routes ) )
            $this->routes = [];
        else{
            $this->routes = [...$this->routes, ...$routes->getRoutes()];
        }
    }

    public function insertRoute(RouteInterface $route) : RouteInterface{   
        $this->routes[] = $route;
        return $route;
    }

    public function add(string $uri, string $method, mixed $callback ) : RouteInterface{      
        return $this->insertRoute( new Route($this->prefix, $uri, $method, $callback) );        
    }

    public function get($uri, $callback) : RouteInterface{
        return $this->add( $uri, 'get', $callback );
    }

    public function post($uri, $callback) : RouteInterface{
        return $this->add( $uri, 'post', $callback );
    }

    public function put($uri, $callback) : RouteInterface{
        return $this->add($uri, 'put', $callback);
    }

    public function patch($uri, $callback) : RouteInterface{
        return $this->add($uri, 'patch', $callback);
    }

    public function delete($uri, $callback) : RouteInterface{
        return $this->add($uri, 'delete', $callback);
    }

    public function options($uri, $callback) : RouteInterface{
        return $this->add($uri, 'options', $callback);
    }

    public function head($uri, $callback) : RouteInterface{
        return $this->add($uri, 'head', $callback);
    }

    public function getUri($name, $params = [] ) : string{    
        foreach( $this->routes as $route ){
            $_name = $route->getName();
            if( $_name == $name ){
                if( !empty( $params ) )
                    return Path::matchReplace( $params, $route->getRoutePath() );
                else
                    return $route->getUri();
            }
        }
        throw new NotExistsRouteException();
    }

    public function getUris($params = []) : array{
        $urls = [];
        if( empty( $this->routes ) ) return $urls;
        foreach( $this->routes as $route ){
            if( !empty( $params ) )
                $urls[] = Path::matchReplace($params, $route->getRoutePath() );

        }
        return $urls;
    }

    public function getNames() : array{
        $names = [];
        if( empty( $this->routes ) ) return $names;
        foreach( $this->routes as $route ){
            $names[] = $route->getName();
        }
        return $names;
    }

    public function getRoute( $name ) : RouteInterface{        
        foreach( $this->routes as $route ){
            if( $route->getName() == $name )
                return $route;            
        }

        return throw new NotExistsRouteException;
    }

    public function getRoutes() : array{
        return $this->routes;
    }
}