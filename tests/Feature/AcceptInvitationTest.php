<?php

namespace Tests\Feature;

use App\User;
use App\Invitation;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcceptInvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function viewing_an_unused_invitation()
    {
        $invitation = factory(Invitation::class)->create([
            'code' => 'TESTCODE1234',
            'user_id' => null
        ]);

        $response = $this->get('invitations/TESTCODE1234');

        $response->assertStatus(200);
        $response->assertViewIs('invitations.show');
        $this->assertTrue($response->original->getData()['invitation']->is($invitation));
    }

    /** @test */
    public function viewing_a_used_invitation()
    {
        $invitation = factory(Invitation::class)->create([
            'code' => 'TESTCODE1234',
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->get('invitations/TESTCODE1234');

        $response->assertStatus(404);
    }

    /** @test */
    public function viewing_a_invitation_that_does_not_exist()
    {
        $response = $this->get('invitations/TESTCODE1234');

        $response->assertStatus(404);
    }

    /** @test */
    public function registering_with_a_valid_invitation_code()
    {
        $invitation = factory(Invitation::class)->create([
            'code' => 'TESTCODE1234',
            'user_id' => null
        ]);

        $response = $this->post('register', [
            'email' => 'test@example.com',
            'password' => 'secret',
            'invitation_code' => 'TESTCODE1234'
        ]);

        $response->assertRedirect('backstage/concerts');
        $this->assertEquals(1, User::count());
        $user = User::first();
        $this->assertAuthenticatedAs($user);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue(Hash::check('secret', $user->password));
        $this->assertTrue($invitation->fresh()->user->is($user));
    }
}
