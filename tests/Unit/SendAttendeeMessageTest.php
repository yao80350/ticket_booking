<?php

namespace Tests\Unit;

use App\Order;
use App\Ticket;
use App\Concert;
use Tests\TestCase;
use App\AttendeeMessage;
use App\Jobs\SendAttendeeMessage;
use App\Mail\AttendeeMessageEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SendAttendeeMessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_sends_the_message_to_all_concert_attendees() {
        Mail::fake();
        $concert = factory(Concert::class)->create();
        $otherConcert = factory(Concert::class)->create();
        $concert->publish();
        $otherConcert->publish();

        $message = AttendeeMessage::create([
            'concert_id' => $concert->id,
            'subject' => 'My subject',
            'message' => 'My message'
        ]);

        $orderA = factory(Order::class)->create(['email' => 'people1@test.com']);
        $ticketsA = factory(Ticket::class, 3)->create(['concert_id' => $concert->id]);
        $orderA->tickets()->saveMany($ticketsA);

        $orderB = factory(Order::class)->create(['email' => 'people2@test.com']);
        $ticketsB = factory(Ticket::class, 3)->create(['concert_id' => $concert->id]);
        $orderB->tickets()->saveMany($ticketsB);

        $orderC = factory(Order::class)->create(['email' => 'people3@test.com']);
        $ticketsC = factory(Ticket::class, 3)->create(['concert_id' => $otherConcert->id]);
        $orderC->tickets()->saveMany($ticketsC);

        SendAttendeeMessage::dispatch($message);

        Mail::assertSent(AttendeeMessageEmail::class, function ($mail) use ($message) {
            return $mail->hasTo('people1@test.com')
                && $mail->attendeeMessage->is($message);
        });

        Mail::assertSent(AttendeeMessageEmail::class, function ($mail) use ($message) {
            return $mail->hasTo('people2@test.com')
                && $mail->attendeeMessage->is($message);
        });

        Mail::assertNotSent(AttendeeMessageEmail::class, function ($mail) {
            return $mail->hasTo('people3@test.com');
        });
    }
}
