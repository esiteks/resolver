<?php declare(strict_types=1);

require_once __DIR__ . "/../Foo.php";

use Esiteks\Resolver\Resolver;
use Esiteks\Resolver\Classes\Route;
use Esiteks\Resolver\Exceptions\NotExistsRouteException;
use Esiteks\Resolver\Exceptions\NotFoundException;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase{

    protected Resolver $router;

    protected function setUp() : void{
        $this->router = new Resolver;
    }

    /**
     * @test
     * @testdox Get a Valid Route Instance in add Method
     */
    public function getValidRouteObject() : void {
        $r = $this->router->add('/','get', function(){});
        $this->assertInstanceOf(Route::class, $r);
    }

    /**
     * @test
     * @testdox Get a valid Route Instance in GET|POST|PUT|PATHC|DELETE|OPTION|HEAD methods
     */
    public function getValidRouteObjectMethods() : void {
        $this->assertInstanceOf(
            Route::class,
            $this->router->get('/get',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->router->post('/post',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->router->put('/put',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->router->patch('/patch',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->router->delete('/delete',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->router->options('/options',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->router->head('/head',function(){} )
        );
    }

    /**
     * @test
     * @testdox Get a correct uri using Name
     */
    public function getCorrectUri() : void {
        $this->router->get('/foo', function(){})->name('bar');

        $this->assertEquals('/foo', $this->router->getUri('bar'));
    }

    /**
     * @test
     * @testdox Get correct uri value replaced using name
     */
    public function getCorrectUriReplaced() : void {
        $this->router->get('/foo/:var', function(){})->name('bar');
        $this->assertEquals('/foo/bar', $this->router->getUri('bar', [':var' => 'bar']));
    }

    /**
     * @test
     * @testdox Get correct values ​​of replaced
     */
    public function GetCorrectUrisValues() : void {
        $callback = function(){};
        $this->router->get('/foo/:var', $callback);
        $this->router->get('/foo2/:var', $callback);
        $this->router->get('/foo3/:var2', $callback);

        $this->assertEquals(
            [
                '/foo/bar',
                '/foo2/bar',
                '/foo3/bar2'
            ], 
            $this->router->getUris([
                ':var' => 'bar',
                ':var2' => 'bar2'
            ])
        );
    }

    /**
     * @test
     * @testdox Get correct route intance using name
     */
    public function GetCorrectRouteFromName() : void {
        $this->router->get('/', function(){})->name('foo');        
        $r = $this->router->getRoute('foo');

        $this->assertInstanceOf(Route::class, $r, );
        $this->assertEquals('foo', $r->getName());
    }

    /**
     * @test
     * @testdox Get NotExistsRouteException when search by name
     */
    public function GetNotExistsRouteException() : void {
        $this->expectException(NotExistsRouteException::class);

        $this->router->get('/', function(){})->name('index');
        $this->router->getRoute('foo');
    }

    /**
     * @test
     * @testdox Get the correct array routes
     */
    public function GetCorrectArrayRoutes() : void {
        $callback = function(){};

        $this->router->get('/', $callback)->name('home');
        $this->router->get('/foo', $callback)->name('foo');

        $_routes = $this->router->getRoutes();
        $this->assertIsArray($_routes);
        $this->assertCount(2, $_routes);

        $this->assertEquals('home', $_routes[0]->getName());
        $this->assertEquals('foo', $_routes[1]->getName());
    }

    private function createRoutes(string $method) : mixed{
        $r = new Resolver();

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

        $r->add('/', $method, [Foo::class, 'home']);

        $r->add('foo/:var', $method, [Foo::class, 'fooBar']);

        return $r;
    }

    /**
     * @test
     * @testdox Resolve get uri
     */
    public function ResolveGetUri() : void {
        $m = 'get';
        $r = $this->createRoutes($m);

        $this->assertEquals('index', $r->resolve('/',$m));
        $this->assertEquals('bar', $r->resolve('/foo/bar', $m));        
    }

    /**
     * @test
     * @testdox Resolve post uri
     */
    public function ResolvePostUri() : void {
        $m = 'post';
        $r = $this->createRoutes($m);

        $this->assertEquals('index', $r->resolve('/',$m));
        $this->assertEquals('bar', $r->resolve('/foo/bar', $m));         
    }

    /**
     * @test
     * @testdox Resolve put uri
     */
    public function ResolvePutUri() : void {
        $m = 'put';
        $r = $this->createRoutes($m);

        $this->assertEquals('index', $r->resolve('/',$m));
        $this->assertEquals('bar', $r->resolve('/foo/bar', $m));       
    }

    /**
     * @test
     * @testdox Resolve patch uri
     */
    public function ResolvePatchUri() : void {
        $m = 'patch';
        $r = $this->createRoutes($m);

        $this->assertEquals('index', $r->resolve('/',$m));
        $this->assertEquals('bar', $r->resolve('/foo/bar', $m));       
    }

    /**
     * @test
     * @testdox Resolve delete uri
     */
    public function ResolveDeleteUri() : void {
        $m = 'delete';
        $r = $this->createRoutes($m);

        $this->assertEquals('index', $r->resolve('/',$m));
        $this->assertEquals('bar', $r->resolve('/foo/bar', $m));         
    }

    /**
     * @test
     * @testdox Resolve options uri
     */
    public function ResolveOptionsUri() : void {
        $m = 'options';
        $r = $this->createRoutes($m);

        $this->assertEquals('index', $r->resolve('/',$m));
        $this->assertEquals('bar', $r->resolve('/foo/bar', $m));         
    }

    /**
     * @test
     * @testdox Resolve head uri
     */
    public function ResolveHeadUri() : void {
        $m = 'head';
        $r = $this->createRoutes($m);

        $this->assertEquals('index', $r->resolve('/',$m));
        $this->assertEquals('bar', $r->resolve('/foo/bar', $m));       
    }

    /**
     * @test
     * @testdox Get NoFoundException
     */
    public function GetNotFound() : void{
        $this->expectException(NotFoundException::class);
        $this->router->post('/', function(){});

        $this->router->resolve('/', 'get');
    }

}