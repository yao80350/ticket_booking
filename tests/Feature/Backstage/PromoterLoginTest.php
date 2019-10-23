<?php
namespace Tests\Feature\Backstage;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class PromoterLoginTest extends TestCase
{
	use DatabaseMigrations;
    /** @test */
    public function logging_in_with_valid_credentials()
    {
    	
        $user = factory(User::class)->create([
        	'email' => 'jane@example.com',
        	'password' => bcrypt('super-secret-password')
    	]);
    	$response = $this->post('login', [
    		'email' => 'jane@example.com',
    		'password' => 'super-secret-password'
		]);
    	$response->assertRedirect('/backstage/concerts/');
		$this->assertTrue(auth()->check());
		$this->assertTrue(auth()->user()->is($user));
    }

    /** @test */
    public function logging_in_with_invalid_credentials()
    {
        
        $user = factory(User::class)->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('super-secret-password')
        ]);
        $response = $this->post('login', [
            'email' => 'jane@example.com',
            'password' => 'incorrect-password'
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertFalse(auth()->check());
    }

    /** @test */
    public function logging_in_with_an_account_that_does_not_exist()
    {
        $response = $this->post('login', [
            'email' => 'nonexistantUser@example.com',
            'password' => 'password'
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertFalse(auth()->check());
    }

    /** @test */
    public function logging_out_an_authenticated_user()
    {
        $user = factory(User::class)->create([
        	'email' => 'jane@example.com',
        	'password' => bcrypt('super-secret-password')
    	]);
		
		$user = $this->be($user);
        $response = $this->post('logout');
        $response->assertStatus(302)
                 ->assertRedirect('login');
        $this->assertFalse(auth()->check());
    }
}
