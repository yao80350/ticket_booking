<?php

namespace Tests\Feature\Backstage;

use App\User;
use App\Concert;
use Tests\TestCase;
use App\AttendeeMessage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MessageAttendeesTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function a_promoter_can_view_the_message_form_for_their_own_concert() {
        $user = factory(User::class)->create();
        $concert = factory(Concert::class)->create([
            'user_id' => $user->id,
        ]);
        $concert->publish();

        $response = $this->actingAs($user)->get("/backstage/concerts/{$concert->id}/messages/new");

        $response->assertStatus(200);
        $response->assertViewIs("backstage.concert-messages.new");
        $this->assertTrue($response->original->getData()['concert']->is($concert));
    }

    /** @test */
    function a_promoter_cannot_view_the_message_form_for_another_concert() {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $concert = factory(Concert::class)->create([
            'user_id' => $otherUser->id,
        ]);
        $concert->publish();

        $response = $this->actingAs($user)->get("/backstage/concerts/{$concert->id}/messages/new");  

        $response->assertStatus(404);            
    }

    /** @test */
    function a_promoter_can_send_a_new_message() {
        $user = factory(User::class)->create();
        $concert = factory(Concert::class)->create([
            'user_id' => $user->id,
        ]);
        $concert->publish();

        $response = $this->actingAs($user)->post("/backstage/concerts/{$concert->id}/messages", [
            'subject' => 'My subject',
            'message' => 'My message'
        ]);  
        
        $response->assertRedirect("/backstage/concerts/{$concert->id}/messages/new");
        $response->assertSessionHas('flash');

        $message = AttendeeMessage::first();
        $this->assertEquals($concert->id, $message->concert_id);
        $this->assertEquals('My subject', $message->subject);
        $this->assertEquals('My message', $message->message);
    }
}
