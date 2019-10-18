<?php

Route::get('/concerts/{id}', 'concertController@show')->name('concerts.show');

Route::post('/concerts/{id}/orders', 'concertOrdersController@store');

Route::get('/orders/{confirmationNumber}', 'ordersController@show');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('auth.show-login');
Route::post('/login', 'Auth\LoginController@login')->name('auth.login');
Route::post('/logout', 'Auth\LoginController@logout')->name('auth.logout');

Route::get('/invitations/{code}', 'InvitationsController@show');
Route::post('/register', 'Auth\RegisterController@register')->name('auth.register');

Route::group(['middleware' => 'auth', 'prefix' => 'backstage', 'namespace' => 'Backstage'], function() {
    Route::get('/concerts/new', 'ConcertsController@create')->name('backstage.concerts.new');
    Route::get('/concerts', 'ConcertsController@index')->name('backstage.concerts.index');
    Route::post('/concerts', 'ConcertsController@store');
    Route::get('/concerts/{id}/edit', 'ConcertsController@edit')->name('backstage.concerts.edit');
    Route::patch('/concerts/{id}', 'ConcertsController@update')->name('backstage.concerts.update');
    Route::post('/published-concerts', 'PublishedConcertsController@store')->name('backstage.published-concerts.store');
    Route::get('/published-concerts/{id}/orders', 'PublishedConcertOrdersController@index')->name('backstage.published-concert-orders.index');
    Route::get('/concerts/{id}/messages/new', 'ConcertMessagesController@create')->name('backstage.concert-messages.new');
    Route::post('/concerts/{id}/messages', 'ConcertMessagesController@store')->name('backstage.concert-messages.store');

    Route::get('stripe-connect/authorize', 'StripeConnectController@authorizeRedirect');
    Route::get('stripe-connect/redirect', 'StripeConnectController@redirect');
});


