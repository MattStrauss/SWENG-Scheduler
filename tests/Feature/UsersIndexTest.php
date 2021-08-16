<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $devUser;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->devUser = User::factory()->create(['name' => 'Ava Dev', 'email' => 'ava@psu.edu']);
        $this->regularUser = User::factory()->create(['name' => 'Eli User', 'email' => 'eli@psu.edu']);
    }

    /**  @test */
    public function dev_user_can_visit_users_index()
    {
        $response = $this->actingAs($this->devUser)->get(route('users.index'));

        $response->assertSuccessful();
        $response->assertSee(['Ava Dev', 'Eli User']);
    }

    /**  @test */
    public function regular_user_can_not_visit_users_index()
    {
        $response = $this->actingAs($this->regularUser)->get(route('users.index'));

        $response->assertForbidden();
        $response->assertDontSee(['Ava Dev', 'Eli User']);
    }

    /**  @test */
    public function guest_can_not_visit_users_index()
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect(route('login'));
    }
}
