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

    /** @test */
    public function email_must_be_a_valid_email_address()
    {
        $invitation = factory(Invitation::class)->create([
        	'user_id' 	=> null,
        	'code'		=> 'TESTCODE1234'
        ]);
        $response = $this->from('invitations/TESTCODE1234')->post('register', [
        	'email'				=> 'invalid.email.com',
        	'password'			=> 'secret',
        	'invitation_code'	=> 'TESTCODE1234'
        ]);
        $response->assertRedirect('invitations/TESTCODE1234');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function email_must_be_unique()
    {
        $existingUser = factory(User::class)->create(['email' => 'john@example.com']);

        $invitation = factory(Invitation::class)->create([
        	'user_id' 	=> null,
        	'code'		=> 'TESTCODE1234'
        ]);
        $this->assertEquals(1, User::count());

        $response = $this->from('invitations/TESTCODE1234')->post('register', [
        	'email'				=> 'john@example.com',
        	'password'			=> 'secret',
        	'invitation_code'	=> 'TESTCODE1234'
        ]);

        $response->assertRedirect('invitations/TESTCODE1234');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function password_is_required()
    {
        $invitation = factory(Invitation::class)->create([
        	'user_id'	=> null,
        	'code'		=> 'TESTCODE1234'
        ]);
        $response = $this->from('invitations/TESTCODE1234')->post('register', [
        	'email'				=> 'john@example.com',
        	'password'			=> '',
        	'invitation_code'	=> 'TESTCODE1234'
        ]);
        $response->assertRedirect('invitations/TESTCODE1234');
        $response->assertSessionHasErrors('password');
        $this->assertEquals(0, User::count());
    }
}
