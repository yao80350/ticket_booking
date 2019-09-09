@extends('layouts.master')

@section('body')
	<div class="container ticket">
		<div class="heading-primary mg-bottom-md">
			<h1 class="heading-primary--main">{{ $concert->title }}</h1>
			<h2 class="heading-primary--sub">{{ $concert->subtitle }}</h2>
		</div>
		<ul class="ticket_info">
			<li class="ticket_info_item">
				<img src='{{ asset("images/calendar.svg") }}' alt="calendar icon">
				<span>{{ $concert->formatted_date }}</span>
			</li>
			<li class="ticket_info_item">
				<img src='{{ asset("images/time.svg") }}' alt="time icon">
				<span>{{ $concert->formatted_start_time }}</span>
			</li>
			<li class="ticket_info_item">
				<img src='{{ asset("images/currency-dollar.svg") }}' alt="currency-dollar icon">
				<span>{{ $concert->ticket_price_in_dollars }}</span>
			</li>
			<li class="ticket_info_item">
				<img src='{{ asset("images/location.svg") }}' alt="location icon">
				<span>{{ $concert->venue }}</span>
				<p>{{ $concert->venue_address }} {{ $concert->city }}, {{ $concert->state }} {{ $concert->zip }}</p>
				
			</li>
			<li class="ticket_info_item">
				<img src='{{ asset("images/information-outline.svg") }}' alt="information icon">
				<span>Additonal Information</span>
				<p>{{ $concert->additional_information }}</p>				
			</li>
		</ul>
		<div class="cta">
			<form id="buy_tickets">
				<div class="price">
					<h5>Price</h5>
					<h5 class="num_wrapper">{{ $concert->ticket_price_in_dollars }}</h5>
				</div>
				<div class="num">
					<label for="quantity">Qty</label>
					<input class="num_wrapper" id="quantity" value="1" type="number">
				</div>
				<button type="submit" id="submit_btn" class="btn">
					Buy Tickets
					span.btn
				</button>
			</form>
		</div>
	</div>
@endsection

@push('beforeScripts')
<script>
    window.myApp.concert = @json($concert);
</script>
<script src="https://checkout.stripe.com/checkout.js"></script>
@endpush