<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use App\Notifications\NewUserSignup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private $devUser1;
    private $devUser2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->devUser1 = User::factory()->create(['name' => 'Ava Dev', 'email' => 'ava@psu.edu']);
        $this->devUser2 = User::factory()->create(['name' => 'Ava Dev', 'email' => 'kelly@psu.edu']);
    }

    /** @test */
    public function guest_can_register_for_the_site()
    {
        Notification::fake();

        $data = ["name" => "Jennifer Student", "email" => "jnr332@psu.edu", "password" => "someRandomPassword6",
        "password_confirmation" => "someRandomPassword6"];

        $response = $this->post('register', $data);

        $user = User::where('email', 'jnr332@psu.edu')->first();

        foreach (User::getDevUsers() as $email)
        {
            Notification::assertSentTo(
            [User::where('email', $email)->first()], NewUserSignup::class);
        }

        $response->assertRedirect(route('courses.index'));

        $this->assertDatabaseHas('users', ["name" => "Jennifer Student", "email" => "jnr332@psu.edu"]);
    }

    /** @test */
    public function guest_can_not_register_with_out_a_penn_state_email_address()
    {

        Notification::fake();

        $data = ["name" => "Jennifer Student", "email" => "jnr332@gmail.com", "password" => "someRandomPassword6",
                 "password_confirmation" => "someRandomPassword6"];

        $response = $this->from(route('register'))->post('register', $data);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrorsIn('email');

        $this->assertDatabaseMissing('users', ["name" => "Jennifer Student", "email" => "jnr332@psu.edu"]);
    }
}
