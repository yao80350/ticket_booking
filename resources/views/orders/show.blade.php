@extends('layouts.master')
@section('body')
	<div class="container order-box">
	    <div class="order">
	    	<header class="heading-primary order__header">
				<h1 class="heading-primary--main order__summary">Order Summary</h1>
				<span class="heading-primary--sub order__confirmation-num">{{ $order->confirmation_number }}</span>
			</header>
			<div class="order__desc">
				<p class="order__total">Order Total: ${{ number_format($order->amount / 100, 2) }}</p>
				<p class="order__card">Billed to Card #: **** **** **** {{ $order->card_last_four }}</p>
			</div>
		</div>
		<div class="ticket-cards">
			<h4 class="ticket-cards__title">Your tickets</h4>

			@foreach($order->tickets as $ticket)
			<div class="ticket-card">
				<div class="ticket-card__title-box">
					<h4 class="ticket-card__title">
						<span class="ticket-card__title--main">{{ $ticket->concert->title }}</span>
						<span class="ticket-card__title--sub">{{ $ticket->concert->subtitle }}</span>
					</h4>
					<p class="ticket-card__admission">
						<span class="ticket-card__admission--main">General Admission</span>
						<span class="ticket-card__admission--sub">Admit one</span>
					</p>
				</div>
				<div class="ticket-card__detail-box">
					<div class="ticket-card__detail">
						<img src='{{ asset("images/calendar_active.svg") }}' alt="calendar icon">
						<p class="ticket-time">
							<time datetime="{{ $ticket->concert->date->format('Y-m-d H:i') }}">
								<span>{{ $ticket->concert->date->format('l, F jS, Y') }}</span>
							</time>
							<span>{{ $ticket->concert->date->format('g:ia') }}</span>
						</p>
					</div>
					<div class="ticket-card__detail">
						<img src='{{ asset("images/location_active.svg") }}' alt="map icon">
						<p class="ticket-address">
							<span class="bold">{{ $ticket->concert->venue }}</span>
							<span>{{ $ticket->concert->venue_address }}</span>
							<span>{{ $ticket->concert->city }}, {{ $ticket->concert->state }} {{ $ticket->concert->zip }}</span>
						</p>
					</div>
				</div>
				<div class="ticket-card__note-box">
					<span class="ticket-code">{{ $ticket->code }}</span>
					<span class="ticket-email">{{ $order->email }}</span>
				</div>
			</div>
			@endforeach
		</div>										
	</div>	
@endsection
