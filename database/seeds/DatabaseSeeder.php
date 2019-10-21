<?php

use App\Order;
use App\Ticket;
use App\Concert;
use Carbon\Carbon;
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
        $faker = \Faker\Factory::create();
        $gateway = new \App\Billing\FakePaymentGateway;

        $user = factory(User::class)->create([
            'email' => 'user@somewhere.com',
            'password' => bcrypt('secret')
        ]);

        $concert = factory(Concert::class)->create([
            'user_id' => $user->id,
            'title' => "The Red Chord",
            'subtitle' => "with Animosity and Lethargy",
            'venue' => "The Mosh Pit",
            'venue_address' => "123 Example Lane",
            'city' => "Laraville",
            'state' => "ON",
            'zip' => "17916",
            'date' => Carbon::today()->addMonths(3)->hour(20),
            'ticket_price' => 3250,
            'additional_information' => "This concert is 19+.",
            'ticket_quantity' => 210,
        ]);
        $concert->publish();

        foreach(range(1-50) as $i) {
            Carbon::setTestNow(Carbon::instance($faker->dateTimebetween('-2 months')));

            $concert->reserveTickets(rand(1, 4), $faker->safeEmail)
                ->complete($geteway, $geteway->getValidTestToken($faker->creditCardNumber), 'test_account_1234');
        }
        Carbon::setTestNow();

        factory(App\Concert::class)->create([
            'user_id' => $user->id,
            'title' => "Slayer",
            'subtitle' => "with Forbidden and Testament",
            'venue' => "The Rock Pile",
            'venue_address' => "55 Sample Blvd",
            'city' => "Laraville",
            'state' => "ON",
            'zip' => "19276",
            'date' => Carbon::today()->addMonths(4)->hour(20),
            'ticket_price' => 5500,
            'additional_information' => null,
            'ticket_quantity' => 110,
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
