<?php declare(strict_types=1);

require_once __DIR__ . "/../Foo.php";

use Esiteks\Resolver\Resolver;
use Esiteks\Resolver\Classes\ResolverResult;
use Esiteks\Resolver\Classes\Collection;

use Esiteks\Resolver\Exceptions\NotResolveException;

use PHPUnit\Framework\TestCase;

final class ResolverTest extends TestCase{
    
    protected Resolver $router;
    protected Collection $collection;

    public function setUp() : void{
        $this->router = new Resolver();
        $this->collection = new Collection();
    }

    /**
     * @test
     * @testdox Get a ResolveResult Intance
     */
    public function ResolveIntance() : void {
        $this->collection->get('/', function(){});        

        $this->assertInstanceOf(ResolverResult::class, $this->router->resolve('/', 'get', $this->collection));
    }

    private function createRoutes(string $method) : void{
        /*
        ---FOO CLASS---
        ...
        public function home(){
            return 'index';
        }

        public static function fooBar($var){
            return $var;
        }
        ...
        */

        $this->collection->add('/', $method, [Foo::class, 'home']);

        $this->collection->add('foo/:var', $method, [Foo::class, 'fooBar']);
    }

    

    private function getContentFromCallback( $callback,  $matchArgs = [] ) : mixed{
        $content = null;

        if( is_a( $callback, Closure::class ) ){
            $content = call_user_func_array($callback, $matchArgs );
        }else if( is_array( $callback ) && count( $callback ) == 2 ){
            $rf = new \ReflectionMethod( $callback[0], $callback[1] );
            $content = $rf->invokeArgs(new $callback[0], $matchArgs ); 
        }

        return $content;
    }

    /**
     * @test
     * @testdox Resolve get uri
     */
    public function ResolveGetUri() : void {
        $m = 'get';
        $this->createRoutes($m);

        $u1 = $this->router->resolve('/',$m, $this->collection);
        $u2 = $this->router->resolve('/foo/bar', $m, $this->collection);

        $callback1 = $this->getContentFromCallback($u1->getCallback());
        $callback2 = $this->getContentFromCallback($u2->getCallback(), $u2->getArgs());

        $this->assertEquals('index', $callback1);
        $this->assertEquals('bar', $callback2);        
    }

    /**
     * @test
     * @testdox Resolve post uri
     */
    public function ResolvePostUri() : void {
        $m = 'post';
        $this->createRoutes($m);

        $u1 = $this->router->resolve('/',$m, $this->collection);
        $u2 = $this->router->resolve('/foo/bar', $m, $this->collection);

        $callback1 = $this->getContentFromCallback($u1->getCallback());
        $callback2 = $this->getContentFromCallback($u2->getCallback(), $u2->getArgs());

        $this->assertEquals('index', $callback1);
        $this->assertEquals('bar', $callback2);    
    }

    /**
     * @test
     * @testdox Resolve put uri
     */
    public function ResolvePutUri() : void {
        $m = 'put';
        $this->createRoutes($m);

        $u1 = $this->router->resolve('/',$m, $this->collection);
        $u2 = $this->router->resolve('/foo/bar', $m, $this->collection);

        $callback1 = $this->getContentFromCallback($u1->getCallback());
        $callback2 = $this->getContentFromCallback($u2->getCallback(), $u2->getArgs());

        $this->assertEquals('index', $callback1);
        $this->assertEquals('bar', $callback2);        
    }

    /**
     * @test
     * @testdox Resolve patch uri
     */
    public function ResolvePatchUri() : void {
        $m = 'patch';
        $this->createRoutes($m);

        $u1 = $this->router->resolve('/',$m, $this->collection);
        $u2 = $this->router->resolve('/foo/bar', $m, $this->collection);

        $callback1 = $this->getContentFromCallback($u1->getCallback());
        $callback2 = $this->getContentFromCallback($u2->getCallback(), $u2->getArgs());

        $this->assertEquals('index', $callback1);
        $this->assertEquals('bar', $callback2);    
    }

    /**
     * @test
     * @testdox Resolve delete uri
     */
    public function ResolveDeleteUri() : void {
        $m = 'delete';
        $this->createRoutes($m);

        $u1 = $this->router->resolve('/',$m, $this->collection);
        $u2 = $this->router->resolve('/foo/bar', $m, $this->collection);

        $callback1 = $this->getContentFromCallback($u1->getCallback());
        $callback2 = $this->getContentFromCallback($u2->getCallback(), $u2->getArgs());

        $this->assertEquals('index', $callback1);
        $this->assertEquals('bar', $callback2);        
    }

    /**
     * @test
     * @testdox Resolve options uri
     */
    public function ResolveOptionsUri() : void {
        $m = 'options';
        $this->createRoutes($m);

        $u1 = $this->router->resolve('/',$m, $this->collection);
        $u2 = $this->router->resolve('/foo/bar', $m, $this->collection);

        $callback1 = $this->getContentFromCallback($u1->getCallback());
        $callback2 = $this->getContentFromCallback($u2->getCallback(), $u2->getArgs());

        $this->assertEquals('index', $callback1);
        $this->assertEquals('bar', $callback2);        
    }

    /**
     * @test
     * @testdox Resolve head uri
     */
    public function ResolveHeadUri() : void {
        $m = 'head';
        $this->createRoutes($m);

        $u1 = $this->router->resolve('/',$m, $this->collection);
        $u2 = $this->router->resolve('/foo/bar', $m, $this->collection);

        $callback1 = $this->getContentFromCallback($u1->getCallback());
        $callback2 = $this->getContentFromCallback($u2->getCallback(), $u2->getArgs());

        $this->assertEquals('index', $callback1);
        $this->assertEquals('bar', $callback2);     
    }

    /**
     * @test
     * @testdox Get NoResolveException
     */
    public function GetNotFound() : void{
        $this->expectException(NotResolveException::class);
        $this->router->resolve('/', 'get', $this->collection);
    }
}