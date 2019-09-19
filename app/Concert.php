<?php

namespace App;

use App\User;
use App\Order;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotEnoughTicketsException;

class Concert extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedDateAttribute() 
    {
    	return $this->date->format('F j, Y');
    }

    public function getFormattedStartTimeAttribute() 
    {
    	return $this->date->format('g:ia');
    }

    public function getticketPriceInDollarsAttribute() 
    {
    	return number_format($this->ticket_price/100, 2);
    }

    public function scopePublished($query) 
    {
        return $query->whereNotNull('published_at');
    }

    public function isPublished() 
    {
        return $this->published_at !== null;
    }

    public function publish()
    {
        $this->update(['published_at' => strftime('%Y-%m-%d %H:%M:%S',time())]);
    }

    public function orders() 
    {
        return $this->belongsToMany(Order::class, 'tickets');
    }

    public function tickets() 
    {
        return $this->hasMany(Ticket::class);
    } 

    public function hasOrderFor($customerEmail)
    {
        return $this->orders()->where('email', $customerEmail)->count() > 0;
    }   

    public function ordersFor($customerEmail)
    {
        return $this->orders()->where('email', $customerEmail)->get();
    }

    public function orderTickets($email, $ticketQuantity) 
    {        
        $tickets = $this->findTickets($ticketQuantity);        

        return Order::forTickets($tickets, $email, $tickets->sum('price'));
    }

    public function reserveTickets($quantity, $email)
    {
        $tickets = $this->findTickets($quantity)->each(function($ticket) {
            $ticket->reserve();
        });

        return new Reservation($tickets, $email);
    }

    public function findTickets($quantity)
    {
        $tickets = $this->tickets()->available()->take($quantity)->get();

        if ($tickets->count() < $quantity) {
            throw new NotEnoughTicketsException;
        } 

        return $tickets;
    }

    public function addTickets($quantity) 
    {   
        for($i=0; $i<$quantity; $i++)
        {
            $this->tickets()->create();
        }

        return $this;   
    }

    public function ticketsRemaining() 
    {
        return $this->tickets()->available()->count();
    }
}
