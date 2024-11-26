<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;



class BreweryApiTest extends TestCase
{
  use RefreshDatabase;

  public function it_shows_brewery_list_to_authenticated_user_only()
  {
    Sanctum::actingAs(
      User::factory()->create(),
    );

    $response = $this->get('/api/breweries');

    $response->assertStatus(200);
    $response->assertSeeText('brewery_type');
  }

  public function it_does_not_allow_to_access_publicly()
  {
    $response = $this->get('/api/breweries');
    $this->assertGuest($guard = null);
    $response->assertStatus(401);
  }
}
