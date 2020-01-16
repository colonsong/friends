<?php

namespace App\Http\Controllers\Jiaoyou;

use App\Http\Controllers\Controller;
use App\Profiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('profile/edit',[
            'title' => 'ssss',
            'name' => 'test',
            'age' => '1',
            'gender' => '1',
            'type' => 'create,'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #dd($request->all());
        $name = $request->input("name", "標題");
        $age = $request->input("age");
        $gender = $request->input("gender");

        $profile = new Profiles;
        $profile->name = $name;
        $profile->age = $age;
        $profile->gender = $gender;
        $profile->save();

        Log::info("Store New Profile : id = $profile->id");

        return redirect()->action('Jiaoyou\ProfilesController@index', [$profile->id]);
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
