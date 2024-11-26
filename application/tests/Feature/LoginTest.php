<?php

namespace Tests\Example\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
  use RefreshDatabase;

  public function test_login_screen_can_be_rendered()
  {
    $response = $this->get('/login');

    $response->assertStatus(200);
  }

  public function test_users_can_authenticate_using_the_login_screen()
  {
    $user = User::factory()->create();

    $response = $this->post('/login', [
      'email' => $user->email,
      'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/home');
  }

  public function test_users_can_not_authenticate_with_invalid_password()
  {
    $user = User::factory()->create();

    $this->post('/login', [
      'email' => $user->email,
      'password' => 'wrong-password',
    ]);

    $this->assertGuest();
  }

  public function test_users_get_redirected_to_login_if_not_authenticated()
  {
    $response = $this->get('/home');
    $this->assertGuest();
    $response->assertRedirectToRoute('login');
  }

  public function test_users_can_view_breweries_only_if_authenticated()
  {
    $user = User::factory()->create();

    $response = $this->post('/login', [
      'email' => $user->email,
      'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response = $this->get('/breweries');
    $response->assertStatus(200);
  }
}
