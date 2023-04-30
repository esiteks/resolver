<?php

class Foo{
    public function foo(){
        return 'foo';
    }
    public static function bar(){
        return 'bar';
    }

    public function home(){
        return 'index';
    }

    public static function fooBar($var){
        return $var;
    }
}