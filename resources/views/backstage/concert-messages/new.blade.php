@extends('layouts.backstage')

@section('backstageContent')

<div class="flex-fit">
    <div class="backstage-bar">
        <div class="container flex-sb-center">
            <h1>
                <strong>{{ $concert->title }}</strong>
                <span class="slash">/</span>
                <span>{{ $concert->formatted_date }}</span>
            </h1>
            <div class="btns">
                <a href="{{ route('backstage.published-concert-orders.index', $concert) }}" class="btn-inline bold mg-right-sm">Orders</a>
                <a href="{{ route('backstage.concert-messages.new', $concert) }}" class="btn-inline">Message Attendees</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="message-box">
            <div class="message-card">
                <h1 class="list-title text-center">New Message</h1>
                
                @if(session('flash'))
                <div class="alert alert--success">{{ session('flash') }}</div>
                @endif
                <form class="form block" action="{{ route('backstage.concert-messages.store', ['id'=> $concert->id]) }}" method="POST">
                {{ csrf_field() }}
                    <div class="form-group mg-bottom-sm {{ $errors->first('subject', 'has-error') }}">
                        <label class="form__label">Subject</label>
                        <input type="text" class="form__control" name="subject" value="{{ old('subject') }}">
                        @if($errors->has('subject'))
                            <p class="help-block">
                                {{ $errors->first('subject') }}
                            </p>
                        @endif
                    </div>
                    <div class="form-group mg-bottom-sm {{ $errors->first('message', 'has-error') }}">
                        <label class="form__label">Message</label>
                        <textarea name="message" rows="10" class="form__control">{{ old('message') }}</textarea>
                        @if($errors->has('message'))
                            <p class="help-block">
                                {{ $errors->first('message') }}
                            </p>
                        @endif
                    </div>
                    <div class="text-center">
                        <button class="btn btn--normal" type="submit">Send Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection