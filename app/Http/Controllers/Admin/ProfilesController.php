<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use App\Profiles;
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
    public function edit()
    {
        $profiles = Profiles::paginate(10);
        return view("admin/profiles/edit", [
            'title' => 'ssss',
            'name' => 'test',
            'age' => '1',
            'gender' => '1',
            'type' => 'create,'
        ]);
    }


}
