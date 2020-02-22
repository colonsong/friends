<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\SockService;
use App\Http\Controllers\Chat\SockController;

class SockControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRun()
    {
       $mock = \Mockery::mock(SockService::class);
       $mock->shouldReceive('run')->andReturn('名言');

       $SockController = new SockController($mock);
       self::assertEquals('名言', $SockController->run());

    }
}
