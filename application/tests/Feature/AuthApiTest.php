<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;

class AuthApiTest extends TestCase
{

  use RefreshDatabase;

  public function it_login_a_valid_user()
  {
    User::factory()->create([
      'name' => 'bar',
      'email' => 'bar@mail.com',
      'password' => Hash::make('password'),
    ]);

    $response = $this->post('/api/login', [
      'email' => 'bar@mail.com',
      'password' => 'password'
    ]);

    $response->assertStatus(200);
    $response->assertSeeText('token');
  }

  public function it_does_not_login_with_wrong_credentials()
  {
    $response = $this->post('/api/login', [
      'email' => 'foo@mail.com',
      'password' => 'WRONG-PASSWORD'
    ]);

    $response->assertStatus(401);
    $response->assertJson([
      "message" => "The provided credentials are incorrect."
    ]);
  }

  public function password_field_is_required()
  {
    $response = $this->post('/api/login', [
      'email' => 'foo@mail.com',
      'password' => null,
    ]);

    $response->assertJson([
      "message" => "Validation Error.",
      "data" => [
        "password" => ["The password field is required."]
      ]
    ]);
  }
}
