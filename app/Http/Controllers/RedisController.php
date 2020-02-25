<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;

class RedisController extends Controller
{
    /**
     * 顯示給定使用者的個人資料。
     *
     * @param  int  $id
     * @return Response
     */
    public function test()
    {
        $user = Redis::set('aaaaaaaaaaaaaaaaa', '111');

        $user = Redis::get('hhh');
        echo $user;

    }
}
