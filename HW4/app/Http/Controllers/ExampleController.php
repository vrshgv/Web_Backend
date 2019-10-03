<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function show()
    {
    return view('welcome')->with('vername', 'Vera');
    }
    public function act(Request $request){

        $personname = $request->input('firstname');
        $personsur = $request->input('lastname');

        return view('welcome', ['firstname' => $personname, 'lastname'=>$personsur]);
    }
}
