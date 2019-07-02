<?php 
namespace App\Billing;

use App\Billing\PaymentFailedException;

class FakePaymentGateway implements PaymentGateway
{	
	private $charges;
	public function __construct() {
		$this->charges = collect();
	}

	public function getValidTestToken ()
	{
		return 'odd#token';
	}

	public function charge($amount, $token) {
		if($token !== $this->getValidTestToken()) {
			throw new PaymentFailedException;
		}
		$this->charges[] = $amount;
	}

	public function totalCharges() {
		return $this->charges->sum();
	}
}