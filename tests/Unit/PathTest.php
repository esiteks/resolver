<?php declare(strict_types=1);

use Esiteks\Router\Classes\Path;
use PHPUnit\Framework\TestCase;

final class PathTest extends TestCase{

    /** 
     * @test 
     * @testdox Get the correct full uri having start/end slashes or whithout them
    */
    public function getUriSlashes() : void{
        $expected = "/hello/testing";

        $path = new Path("////hello/testing/////");
        $path2 = new Path("hello/testing");

        $this->assertEquals($expected, $path->getUri());
        $this->assertEquals($expected, $path2->getUri());
    }

    /**
     * @test
     * @testdox Get the correct full uri having white spaces start or end
     */
    public function getUriWithSpaces() : void{
        $expected = "/hello/testing";
        $path = new Path(" /hello/testing/ ");

        $this->assertEquals($expected, $path->getUri());
    }

    /**
     * @test
     * @testdox Get the correct full uri having CR LF characters
     */
    public function getUriWithCRLF() : void{
        $expected = "/hello/testing";
        $path = new Path("/hello/
        testing/");

        $this->assertEquals($expected, $path->getUri());
    }

    /**
     * @test
     * @testdox Get the correct uri having query params
     */
    public function getUriWithQuery() : void{
        $expected = "/hello/testing";
        $path = new Path("/hello/testing?var=value&param=value");

        $this->assertEquals($expected, $path->getUri());
    }

    /**
     * @test
     * @testdox Get the correct uri having pattern
     */
    public function getUriWithPattern() : void{
        $expected = "/hello/:test/pattern-testing";
        $path = new Path("/hello/:test/pattern-testing");

        $this->assertEquals($expected, $path->getUri());
    }
}