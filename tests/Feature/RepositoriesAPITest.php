<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Repository;

class RepositoriesAPITest extends TestCase
{
  /**
   * testAPIEmptyReponse
   * 
   * Test API get and response when table is empty.
   *
   * @return void
   */
  public function testGetEmptyResponse(): void
  {

    Repository::truncate();

    $response = $this->getJson("/api/getRepositories");

    $response->assertStatus(200);

    $response->assertJson(
      fn (AssertableJson $json) =>
      // response should have 0 repositories on initial call.
        $json->has(0)
    );
  }

  /**
   * testUpdateAndGet
   * 
   * Test calling the update API, then the get API after.
   *
   * @return void
   */
  public function testUpdateAndGet(): void
  {

    // The tests run in order of definition, so this is not exactly needed. But just in case...
    Repository::truncate();

    // I don't think the laravel repo is going anywhere from this list anytime soon...
    // Test if it isn't there.
    $this->assertDatabaseMissing('repositories', [
      'name' => 'laravel'
    ]);

    $response = $this->post("/api/updateRepositories");
    $response->assertStatus(201);

    // Then test if it appears after update.
    $this->assertDatabaseHas('repositories', [
      'name' => "laravel",
    ]);

    $get = $this->getJson("/api/getRepositories");

    $get->assertStatus(200);

    $get->assertJson(
      fn (AssertableJson $json) =>
        // response should have 100 repositories on initial call.
        $json->has(100)
        // Then, it's first repo that it returns with should have everything we need.
        ->first(
          fn ($json) =>
            $json->hasAll('id', 'name', 'url', 'created_at', 'pushed_at', 'description', 'stargazers_count')
          )
      );
  }
}
