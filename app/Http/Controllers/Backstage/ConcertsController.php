<?php
namespace App\Http\Controllers\Backstage;

use App\Concert;
use App\NullFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConcertsController extends Controller
{
    public function index()
    {
        $concerts = Auth::user()->concerts;

        return view('backstage/concerts.index', [
            'publishedConcerts' => $concerts->filter->isPublished(),
            'unpublishedConcerts' => $concerts->reject->isPublished(),
        ]);
    }

    public function create() 
    {
        return view('backstage.concerts.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'time' => 'required|date_format:g:ia',
            'venue' => 'required',
            'venue_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'ticket_price' => 'required|numeric|min:5',
            'ticket_quantity' => 'required|numeric|min:1',
            'poster_image' => ['nullable', 'image', Rule::dimensions()->minWidth(400)->ratio(8.5/11)]
        ]);

        $concert = Auth::user()->concerts()->create([
            'title' => request('title'),
            'subtitle' => request('subtitle'),
            'date' => Carbon::parse(vsprintf('%s %s', [
                request('date'),
                request('time')
            ])),
            'ticket_price' => request('ticket_price') * 100,
            'venue' => request('venue'),
            'venue_address' => request('venue_address'),
            'city' => request('city'),
            'state' => request('state'),
            'zip' => request('zip'),
            'additional_information' => request('additional_information'),
            'ticket_quantity' => (int) request('ticket_quantity'),
            'poster_image_path' => request('poster_image', new NullFile)->store('posters', 'public')
        ]);

        //return $concert;
        return redirect()->route('backstage.concerts.index', $concert);
    }

    public function edit($id) 
    {
        $concert = Auth::user()->concerts()->findOrFail($id);

        abort_if($concert->isPublished(), 403);

        return view('backstage.concerts.edit', [
            'concert' => $concert
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'time' => 'required|date_format:g:ia',
            'venue' => 'required',
            'venue_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'ticket_price' => 'required|numeric|min:5',
            'ticket_quantity' => 'required|numeric|min:1' 
        ]);

        $concert = Auth::user()->concerts()->findOrFail($id);

        abort_if($concert->isPublished(), 403); 

        $concert->update([
            'title' => request('title'),
            'subtitle' => request('subtitle'),
            'date' => Carbon::parse(vsprintf('%s %s', [
                request('date'),
                request('time')
            ])),
            'ticket_price' => request('ticket_price') * 100,
            'venue' => request('venue'),
            'venue_address' => request('venue_address'),
            'city' => request('city'),
            'state' => request('state'),
            'zip' => request('zip'),
            'additional_information' => request('additional_information'),
            'ticket_quantity' => (int) request('ticket_quantity')
        ]);

        return redirect()->route('backstage.concerts.index');
    }
}