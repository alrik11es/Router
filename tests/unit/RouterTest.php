<?php

class RouterTest extends PHPUnit_Framework_TestCase {
    
    const TEST_PASSED = 'TEST PASSED';
    
    public function response01(){
        return self::TEST_PASSED;
    }
    
    public function routesProvider()
    {
       return [
            ['/', 'GET','\/', 'RouterTest@response01', self::TEST_PASSED], // Basic home page
            ['/asd', 'GET','\/', 'RouterTest@response01', false], // 404 error
            ['/page', 'GET','\/page', 'RouterTest@response01', self::TEST_PASSED],
            ['/page/1', 'GET','\/page\/(.*)', function($a){ return $a; }, '1'], // Callback with params
        ];
    }
    
    public function getRouter($url)
    {
          // Create a stub for the SomeClass class.
        $stub = $this->getMockBuilder('\PHPico\Router')
                     ->setMethods(array('base'))
                     ->getMock();

        // Configure the stub.
        $stub->method('base')
             ->willReturn($url);

        return $stub;
    }
    
    /**
     * @dataProvider routesProvider
     */
    public function testRoutes($location, $method, $regexp, $callable, $expected_result)
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        
        $r = $this->getRouter($location);
        $result = $r->execute($regexp, $callable);
        $this->assertEquals($expected_result, $result);
    }
}