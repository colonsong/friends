<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Profiles;
class TestCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Profiles = Profiles::all();
        var_dump($Profiles);

        echo '<p/>';
        $Profiles = Profiles::orderBy('id')->paginate(10);
        var_dump($Profiles);

        echo '<p/>';
        $largeDataRows = $this->getLargeData();
        //generator
        var_dump(get_class($largeDataRows));

        echo '<p/>';
        var_dump(get_class(Profiles::cursor()));

        echo '<p/>';
        $profiles = Profiles::cursor()->filter(function ($post) {
            return $profiles->id > 100;
        });

        //var_dump($profiles);

        /*
        foreach ($profiles as $profile) {
            
            var_dump($profile->id);
             break;
        }
        */
        
        // in C:\xampp\htdocs\friends\storage\logs\laravel.log
        //config/logging.php
        //Log::channel('posts')->info('錯誤用戶嘗試編輯', ['user' => $user]);
        Log::info('Showing user profile for user:');
        
    }


    function getLargeData()
    {
        for ($index = 0; $index <= 10000; $index++) {
            yield $index;
        }
    }
}
