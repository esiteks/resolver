<?php 
declare(strict_types=1);

namespace Esiteks\Router;

use Closure;

use Esiteks\Router\Classes\Route;
use Esiteks\Router\Classes\Path;
use Esiteks\Router\Exceptions\NotExistsRouteException;
use Esiteks\Router\Exceptions\NotFoundException;

class Router{

    protected array $routes = [];    

    public function __construct(
        public readonly string $prefix = ""
    ){}

    public function add(string $uri, string $method, mixed $callback ) : Route{      

        $returnRoute = new Route($this->prefix, $uri, $method, $callback);
		$this->routes[] = $returnRoute;   

        return $returnRoute;
    }

    public function get($uri, $callback) : Route{
        return self::add( $uri, 'get', $callback );
    }

    public function post($uri, $callback) : Route{
        return self::add( $uri, 'post', $callback );
    }

    public function put($uri, $callback) : Route{
        return self::add($uri, 'put', $callback);
    }

    public function patch($uri, $callback) : Route{
        return self::add($uri, 'patch', $callback);
    }

    public function delete($uri, $callback) : Route{
        return self::add($uri, 'delete', $callback);
    }

    public function options($uri, $callback) : Route{
        return self::add($uri, 'options', $callback);
    }

    public function head($uri, $callback) : Route{
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

    public function getRoute( $name ) : Route{        
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

    public function resolve( string $requestUri, string $method) : mixed {        
        if( empty( $this->routes ) ) throw new NotFoundException();      

        $method = trim( strtoupper( $method ) );   
            
        $path = new Path( $requestUri );        
        
        foreach( $this->routes as $route ){ 
            if( $route->getMethod() != $method )
                continue;
            
            if( $route->getUri() == $path->getUri() ){                
                return self::getContentFromCallback( $route->getCallback() );                                
            }else{
                $matchArgs = $route->matchAndGetArgs( $path );
                if( !is_array( $matchArgs ) ) continue; 

                return self::getContentFromCallback( $route->getCallback(), $matchArgs );
            }
            
        }

        throw new NotFoundException();
    }

    protected function getContentFromCallback( $callback,  $matchArgs = [] ) : mixed{
        $content = null;

        if( is_a( $callback, Closure::class ) ){
            $content = call_user_func_array($callback, $matchArgs );
        }else if( is_array( $callback ) && count( $callback ) == 2 ){
            $rf = new \ReflectionMethod( $callback[0], $callback[1] );
            $content = $rf->invokeArgs(new $callback[0], $matchArgs ); 
        }

        return $content;
    }
}