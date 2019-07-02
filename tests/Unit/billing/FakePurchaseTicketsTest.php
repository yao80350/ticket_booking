<?php

namespace Tests\Unit\Billing;

use App\Billing\FakePaymentGateway;
use App\Billing\PaymentFailedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FakePurchaseTicketsTest extends TestCase
{	
	/** @test */
    function charges_with_a_valid_payment_token_are_successful()
    {
	 	$paymentGateway = new FakePaymentGateway;
		$paymentGateway->charge(2500, $paymentGateway->getValidTestToken());

		$this->assertEquals(2500, $paymentGateway->totalCharges()); 
	}

	/** @test */
    function charges_with_an_invalid_payment_token_fail()
    {
    	try {
		 	$paymentGateway = new FakePaymentGateway;
			$paymentGateway->charge(2500, 'fake_token');    		
    	} catch (PaymentFailedException $e) {
    		return;
    	}

    	$this->fail();
    }
}

