<?php

namespace App;


use App\Concert;
use Carbon\Carbon;
use App\Facades\TicketCode;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = [];
    
    public function claimFor($order) {
        $this->code = TicketCode::generateFor($this);
        $order->tickets()->save($this);
    }

    public function concert() 
    {
        return $this->belongsTo(Concert::class);
    }
	
    public function scopeAvailable($query) 
    {
    	return $query->WhereNull('order_id')->WhereNull('reserved_at');
    }

    public function reserve()
    {
        $this->update(['reserved_at' => Carbon::now()]);
    }

    public function release()
    {
    	$this->update(['reserved_at' => null]);
    }

    public function getPriceAttribute()
    {
        return $this->concert->ticket_price; 
    }
}
