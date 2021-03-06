<?php

namespace App\Http\Controllers\Backstage;

use Illuminate\Http\Request;
use App\Jobs\SendAttendeeMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConcertMessagesController extends Controller
{
    public function create($concertId)
    {
        $concert = Auth::user()->concerts()->findOrFail($concertId);
        
        return view('backstage.concert-messages.new', ['concert' => $concert]);
    }

    public function store($concertId, Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);

        $concert = Auth::user()->concerts()->findOrFail($concertId);

        $message = $concert->attendeeMessages()->create(request(['subject', 'message']));

        SendAttendeeMessage::dispatch($message);

        return redirect()->route('backstage.concert-messages.new', $concert)
            ->with('flash', "Your message has been sent.");
    }
}
