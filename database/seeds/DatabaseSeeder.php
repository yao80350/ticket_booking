<?php

use App\Concert;
use App\Order;
use App\Ticket;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'email' => 'user@somewhere.com',
            'password' => bcrypt('secret')
        ]);

        $concert = factory(Concert::class)->states("published")->create([
            'user_id' => $user->id,
            'title' => "The Red Chord",
            'subtitle' => "with Animosity and Lethargy",
            'venue' => "The Mosh Pit",
            'venue_address' => "123 Example Lane",
            'city' => "Laraville",
            'state' => "ON",
            'zip' => "17916",
            'date' => Carbon::parse('2019-12-13 8:00pm'),
            'ticket_price' => 3250,
            'additional_information' => "This concert is 19+.",
            'ticket_quantity' => 10,
        ]);

        factory(App\Concert::class)->create([
            'user_id' => $user->id,
            'title' => "Slayer",
            'subtitle' => "with Forbidden and Testament",
            'venue' => "The Rock Pile",
            'venue_address' => "55 Sample Blvd",
            'city' => "Laraville",
            'state' => "ON",
            'zip' => "19276",
            'date' => Carbon::parse('2019-10-05 7:00pm'),
            'ticket_price' => 5500,
            'additional_information' => null,
            'ticket_quantity' => 10,
        ]);

        $order = factory(Order::class)->create([
			'confirmation_number' => 'ORDERCONFIRMATION1234',
        	'card_last_four' => '2666'
        ]);
        
		$ticket = factory(Ticket::class, 3)->create([
			'concert_id' => $concert->id,
			'order_id' => $order->id,
			'code' => 'ticketCode'
		]);
    }
}
