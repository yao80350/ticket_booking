<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public static function forTickets($email, $tickets, $amount)
    {
        $order = self::create([
            'email' => $email, 
            'amount' => $amount
        ]);

        foreach($tickets as $ticket)
        {
            $order->tickets()->save($ticket);
        }
        return $order;
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
            'email' => $this->email,
            'ticket_quantity' => $this->ticketQuantity(),
            'amount' => $this->amount
        ];
    }

    public function cancel()
    {
    	foreach($this->tickets as $ticket) {
    		$ticket->release();
    	}

    	$this->delete();
    }
}
