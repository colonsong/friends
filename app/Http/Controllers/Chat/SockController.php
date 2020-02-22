<?php

namespace App\Http\Controllers\Chat;

use App\Services\SockService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SockController extends Controller
{
    private $sockService;

    public function __construct(SockService $sockService)
    {
        $this->sockService =  $sockService;
    }


    public function run () {
        return $this->sockService->run();
    }

}
