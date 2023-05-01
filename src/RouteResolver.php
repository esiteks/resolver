<?php 
declare(strict_types=1);

namespace Esiteks\Resolver;

use Esiteks\Resolver\Classes\Route;
use Esiteks\Resolver\Classes\Path;
use Esiteks\Resolver\Classes\Resolve;

use Esiteks\Resolver\Exceptions\NotExistsRouteException;
use Esiteks\Resolver\Exceptions\NotResolveException;

use Esiteks\Contracts\Resolver\ResolveInterface;
use Esiteks\Contracts\Resolver\RouteInterface;
use Esiteks\Contracts\Resolver\RouteResolverInterface;

class RouteResolver implements RouteResolverInterface{

    protected array $routes = [];    

    public function __construct(
        public readonly string $prefix = ""
    ){}

    public function add(string $uri, string $method, mixed $callback ) : RouteInterface{      

        $returnRoute = new Route($this->prefix, $uri, $method, $callback);
		$this->routes[] = $returnRoute;   

        return $returnRoute;
    }

    public function get($uri, $callback) : RouteInterface{
        return self::add( $uri, 'get', $callback );
    }

    public function post($uri, $callback) : RouteInterface{
        return self::add( $uri, 'post', $callback );
    }

    public function put($uri, $callback) : RouteInterface{
        return self::add($uri, 'put', $callback);
    }

    public function patch($uri, $callback) : RouteInterface{
        return self::add($uri, 'patch', $callback);
    }

    public function delete($uri, $callback) : RouteInterface{
        return self::add($uri, 'delete', $callback);
    }

    public function options($uri, $callback) : RouteInterface{
        return self::add($uri, 'options', $callback);
    }

    public function head($uri, $callback) : RouteInterface{
        return self::add($uri, 'head', $callback);
    }

    public function getUri($name, $params = [] ) : string{        
        if( empty( $this->routes ) ) return '';
        foreach( $this->routes as $route ){
            $_name = $route->getName();
            if( $_name == $name ){
                if( !empty( $params ) )
                    return Path::matchReplace( $params, $route->getRoutePath() );
                else
                    return $route->getUri();
            }
        }
        return '';
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
        if( empty( $this->routes ) ) 
            return throw new NotExistsRouteException;

        foreach( $this->routes as $route ){
            if( $route->getName() == $name )
                return $route;            
        }

        return throw new NotExistsRouteException;
    }

    public function getRoutes() : array{
        return $this->routes;
    }

    public function resolve( string $requestUri, string $method) : ResolveInterface {        
        if( empty( $this->routes ) ) throw new NotResolveException();      

        $method = trim( strtoupper( $method ) );   
            
        $path = new Path( $requestUri );        
        
        foreach( $this->routes as $route ){ 
            if( $route->getMethod() != $method )
                continue;
            
            if( $route->getUri() == $path->getUri() ){   
                return new Resolve($route->getCallback());             
            }else{
                $matchArgs = $route->matchAndGetArgs( $path );
                if( !is_array( $matchArgs ) ) continue; 

                return new Resolve( $route->getCallback(), $matchArgs);                
            }
            
        }

        throw new NotREsolveException();
    }
}