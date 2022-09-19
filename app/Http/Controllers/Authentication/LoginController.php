<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Authentication\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index() {
        // $num = 4444;
        // $num_padded = sprintf("%05d", $num);
        // return $num_padded;

        if(isset(Auth::user()->id)) return redirect()->route('notes.index');

        return view('contents.authentication.login');
    }

    public function signIn(LoginRequest $request) {

        // $remember = false;
        // $request->remember? $remember=true : $remember=false;

        if( Auth::attempt(['email' => $request->email, 'password'=>$request->password]) ) {
            info($request->header('User-Agent'));
            Auth::logoutOtherDevices($request->password);

            return redirect()->route('notes.index');

        }

        return redirect()->back()->with('error', 'Invalid Credentials.');

    }
}
