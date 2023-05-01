<?php declare(strict_types=1);

require_once __DIR__ . "/../Foo.php";

use Esiteks\Resolver\Classes\Route;
use Esiteks\Resolver\Classes\Collection;

use Esiteks\Resolver\Exceptions\NotExistsRouteException;

use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase{

    protected Collection $collection;

    protected function setUp() : void{
        $this->collection = new Collection();
    }

    /**
     * @test
     * @testdox Get a Valid Route Instance in add Method
     */
    public function getValidRouteObject() : void {
        $r = $this->collection->add('/','get', function(){});
        $this->assertInstanceOf(Route::class, $r);
    }

    /**
     * @test
     * @testdox Get a valid Route Instance in GET|POST|PUT|PATHC|DELETE|OPTION|HEAD methods
     */
    public function getValidRouteObjectMethods() : void {
        $this->assertInstanceOf(
            Route::class,
            $this->collection->get('/get',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->collection->post('/post',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->collection->put('/put',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->collection->patch('/patch',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->collection->delete('/delete',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->collection->options('/options',function(){} )
        );

        $this->assertInstanceOf(
            Route::class,
            $this->collection->head('/head',function(){} )
        );
    }

    /**
     * @test
     * @testdox Get a correct uri using Name
     */
    public function getCorrectUri() : void {
        $this->collection->get('/foo', function(){})->name('bar');

        $this->assertEquals('/foo', $this->collection->getUri('bar'));
    }

    /**
     * @test
     * @testdox Get correct uri value replaced using name
     */
    public function getCorrectUriReplaced() : void {
        $this->collection->get('/foo/:var', function(){})->name('bar');
        $this->assertEquals('/foo/bar', $this->collection->getUri('bar', [':var' => 'bar']));
    }

    /**
     * @test
     * @testdox Get correct values ​​of replaced
     */
    public function GetCorrectUrisValues() : void {
        $callback = function(){};
        $this->collection->get('/foo/:var', $callback);
        $this->collection->get('/foo2/:var', $callback);
        $this->collection->get('/foo3/:var2', $callback);

        $this->assertEquals(
            [
                '/foo/bar',
                '/foo2/bar',
                '/foo3/bar2'
            ], 
            $this->collection->getUris([
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
        $this->collection->get('/', function(){})->name('foo');        
        $r = $this->collection->getRoute('foo');

        $this->assertInstanceOf(Route::class, $r, );
        $this->assertEquals('foo', $r->getName());
    }

    /**
     * @test
     * @testdox Get NotExistsRouteException when search by name
     */
    public function GetNotExistsRouteException() : void {
        $this->expectException(NotExistsRouteException::class);

        $this->collection->get('/', function(){})->name('index');
        $this->collection->getRoute('foo');
    }

    /**
     * @test
     * @testdox Get the correct array routes
     */
    public function GetCorrectArrayRoutes() : void {
        $callback = function(){};

        $this->collection->get('/', $callback)->name('home');
        $this->collection->get('/foo', $callback)->name('foo');

        $_routes = $this->collection->getRoutes();
        $this->assertIsArray($_routes);
        $this->assertCount(2, $_routes);

        $this->assertEquals('home', $_routes[0]->getName());
        $this->assertEquals('foo', $_routes[1]->getName());
    }

    

}