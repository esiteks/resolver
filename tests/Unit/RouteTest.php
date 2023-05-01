<?php declare(strict_types=1);

require_once __DIR__ . "/../Foo.php";

use Esiteks\Resolver\Classes\Path;
use Esiteks\Resolver\Classes\Route;
use Esiteks\Resolver\Exceptions\NotValidCallbackException;
use Esiteks\Resolver\Exceptions\NotValidMethodException;

use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase{
    /**
     * @test
     * @testdox Get the correct string on toString method
     */
    public function getToString() : void{
        
        $expectedNameBeforeChange = '/hello/world';

        $route = new Route('',' /hello/world','get',function(){});

        $this->assertEquals($expectedNameBeforeChange, (string)$route);

        $expectedName = 'hello';
        $route->name('hello');

        $this->assertEquals($expectedName, (string)$route);
    }

    /**
     * @test
     * @testdox Get the correct name url 
     */
    public function getName() : void{
        $expectedNameBeforeChange = '/hello/world';

        $route = new Route('',' /hello/world','get',function(){});

        $this->assertEquals($expectedNameBeforeChange, $route->getName());

        $expectedName = 'hello';
        $route->name('hello');

        $this->assertEquals($expectedName, $route->getName());
    }

    /**
     * @test 
     * @testdox Get the correct uri
     */
    public function getUri() : void {
        $expected = '/hello/world';

        $route = new Route('', '/hello/world','get', function(){});

        $this->assertEquals($expected, $route->getUri());
    }

    /**
     * @test
     * @testdox Get the correct method
     */
    public function getMethod() : void{
        $expected = 'GET';

        $route = new Route('', '/hello/world','get', function(){});

        $this->assertEquals($expected, $route->getMethod());
    }

    /**
     * @test
     * @testdox Get a callback
     */
    public function getCallback() : void{
        $route = new Route('', '/hello/world','get', function(){});
        $this->assertNotNull( $route->getCallback() );
    }   

    /**
     * @test
     * @testdox Get a executable callback
     */
    public function getExecutableCallback() : void{        
        $route = new Route('', '/hello/world','get', function(){});
        $this->assertIsCallable($route->GetCallback());        
        
        $route = new Route('', '/hello/world','get', [Foo::class, 'foo']);
        
        $expected =false;
        $callback = $route->getCallback();                        
        $expected = method_exists( $callback[0], $callback[1] );        
       
        $this->assertTrue($expected);

        $expected =false;
        $callback = $route->getCallback();                        
        $expected = method_exists( $callback[0], $callback[1] );        

        $route = new Route('','/hello/world', 'get', 'Foo@foo');
        $this->assertTrue($expected);
    }

    /**
     * @test
     * @testdox Match uri and get arguments array
     */
    public function matchAndGetArgs() : void {
        $route = new Route('', '/hello/:var','get', function(){});

        $this->assertNull($route->matchAndGetArgs( new Path('/') ) );
        $path = new Path('/hello/world');
        $this->assertIsArray( $route->matchAndGetArgs( $path ) );
        $this->assertArrayHasKey( 'var', $route->matchAndGetArgs( $path ) );
        $this->assertEquals( [
            'var' => 'world'
        ], $route->matchAndGetArgs( $path ) );        
    }

    /**
     * @test
     * @testdox Get a correct uri with prefix
     */
    public function GetPrefixUri() : void {
        $route = new Route('prefix', '/uri','get', function(){});
        $this->assertEquals('/prefix/uri', $route->getUri());

        $route =  new Route('prefix', '/','get', function(){});
        $this->assertEquals('/prefix', $route->getUri());

        $route = new Route("   ///prefix///   \n", '/uri', 'get', function(){});
        $this->assertEquals('/prefix/uri', $route->getUri());
    }

    /**
     * @test
     * @testdox Except NotValidCallbackException::class expection in __construct
     */
    public function exceptionNotValidCallback() : void{
        $this->expectException( NotValidCallbackException::class );

        new Route('','/','get',null);
    }

    /**
     * @test
     * @testdox Except NotValidMethodException::class expection in __construct
     */
    public function exceptionNotValidMethod() : void{
        $this->expectException( NotValidMethodException::class );

        $route = new Route('','/','FAKE_METHOD',function(){});
    }
}