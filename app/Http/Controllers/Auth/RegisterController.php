<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Concert;
use App\Invitation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    public function register()
    {
        $invitation = Invitation::findByCode(request('invitation_code'));
        abort_if($invitation->user_id !== null, 404);

        request()->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = User::create([
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);

        $invitation->update(['user_id' => $user->id]);

        auth()->login($user);

        return redirect()->route('backstage.concerts.index');
    }
}
