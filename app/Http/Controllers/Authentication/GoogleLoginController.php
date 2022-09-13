<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Carbon;

use App\Models\User;

class GoogleLoginController extends Controller
{
    public function googleRedirect() {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback() {
        
        try {
            
            $user = Socialite::driver('google')->user();
 
            $this->_registerOrLoginUser($user);

            return redirect()->route('notes.index');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

    }

    private function _registerOrLoginUser($data) {

        $user = User::where('email', $data->email)->first();

        if(!$user) {

            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'provider_id' => $data->id,
                'email_verified_at' => now()
            ]);

        }

        Auth::login($user);

    }
}
