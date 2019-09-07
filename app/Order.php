<?php

namespace App;

use App\Facades\OrderConfirmationNumber;
use App\Ticket;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public static function forTickets($tickets, $email, $charge)
    {
        $order = self::create([
            'confirmation_number' => OrderConfirmationNumber::generate(),
            'email' => $email, 
            'amount' => $charge->amount(),
            'card_last_four' => $charge->cardLastFour()
        ]);

        foreach($tickets as $ticket)
        {
            $order->tickets()->save($ticket);
        }
        return $order;
    }

    public static function findByConfirmationNumber($confirmationNumber)
    {
        return self::where("confirmation_number", $confirmationNumber)->firstOrFail();
    }

    public function tickets() 
    {
    	return $this->hasMany(Ticket::class);
    }

    public function ticketQuantity() 
    {
        return $this->tickets()->count();
    }

    public function toArray()
    {
        return [
            'confirmation_number' => $this->confirmation_number,
            'email' => $this->email,
            'amount' => $this->amount,
            'tickets' => $this->tickets->map(function($ticket) {
                return ['code' => $ticket->code];
            })->all()
        ];
    }
}
