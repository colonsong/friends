<?php

namespace App\Http\Controllers\Jiaoyou;

use App\Http\Controllers\Controller;
use App\Profiles;
use App\Images;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $profiles = Profiles::paginate(3);

        return view("profile/index", [
            "profiles" => $profiles,
        ]);
    }


    public function get()
    {
        //
        $profiles = Profiles::orderBy('id')->paginate(10);

        return response()->json($profiles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Auth::check() 判斷用戶登入了沒
        //可以用 Auth::user() 取得用戶資料
        if (Auth::check()) {

            if (empty(Auth::user()->profile)) {
                return view('profile/edit',[
                    'title' => 'ssss',
                    'name' => 'test',
                    'age' => '1',
                    'gender' => '1',
                    'type' => 'create,'
                ]);
            }
            return redirect('profiles');
            
        } else {
            return redirect('login');
        }
        
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        $name = $request->input("name", "標題");
        $age = $request->input("age");
        $gender = $request->input("gender");

        
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $file_path = $image->store('public');

            $profile = new Profiles;
            $profile->name = $name;
            $profile->age = $age;
            $profile->gender = $gender;
            $profile->user_id = $request->user()->id;
            $profile->save();
    
            $images = new Images;
            $images->profiles_id = $profile->id;
            $images->images_path = $file_path;
            $images->save();
            
    
            Log::info("Store New Profile : id = $profile->id");
    
            return redirect()->action('Jiaoyou\ProfilesController@index', [$profile->id]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profiles  $profiles
     * @return \Illuminate\Http\Response
     */
    public function show(Profiles $profiles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profiles  $profiles
     * @return \Illuminate\Http\Response
     */
    public function edit(Profiles $profiles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profiles  $profiles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profiles $profiles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profiles  $profiles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profiles $profiles)
    {
        //
    }
}
