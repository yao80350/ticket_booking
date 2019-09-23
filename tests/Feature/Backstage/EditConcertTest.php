<?php

namespace Tests\Feature\Backstage;

use App\User;
use App\Concert;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditConcertTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function edit_for_their_own_unpublished_concerts()
    {
        $user = factory(User::class)->create();
        $concert = factory(Concert::class)->create([
            'user_id' => $user->id,
            'title' => 'Old title',
            'subtitle' => 'Old subtitle',
            'additional_information' => 'Old additional information',
            'date' => Carbon::parse('2019-01-01 5:00pm'),
            'venue' => 'Old venue',
            'venue_address' => 'Old address',
            'city' => 'Old city',
            'state' => 'Old state',
            'zip' => '00000',
            'ticket_price' => 2000,
            'ticket_quantity' => 5,
        ]);
        $this->assertFalse($concert->isPublished());

        $response = $this->actingAs($user)->patch("backstage/concerts/{$concert->id}", [
            'title' => 'New title',
            'subtitle' => 'New subtitle',
            'additional_information' => 'New additional information',
            'date' => '2019-12-12',
            'time' => '8:00pm',
            'venue' => 'New venue',
            'venue_address' => 'New address',
            'city' => 'New city',
            'state' => 'New state',
            'zip' => '99999',
            'ticket_price' => '72.50',
            'ticket_quantity' => 6,
        ]);
        
        $response->assertRedirect('/backstage/concerts');
        
        $concert = $concert->fresh();
        $this->assertEquals('New title', $concert->title);
        $this->assertEquals('New subtitle', $concert->subtitle);
        $this->assertEquals('New additional information', $concert->additional_information);
        $this->assertEquals(Carbon::parse('2019-12-12 8:00pm'), $concert->date);
        $this->assertEquals('New venue', $concert->venue);
        $this->assertEquals('New address', $concert->venue_address);
        $this->assertEquals('New city', $concert->city);
        $this->assertEquals('New state', $concert->state);
        $this->assertEquals('99999', $concert->zip);
        $this->assertEquals(7250, $concert->ticket_price);
        $this->assertEquals(6, $concert->ticket_quantity);
    }
}