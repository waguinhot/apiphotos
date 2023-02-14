<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testItShouldBeCreateUser()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->post('/api/auth/register', [
            'name' => 'fake name now',
            'email' => 'fakeemaihappy@email.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
    }

    /**
     * @return void
     */
    public function testItNotBeValidEmail()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->post('/api/auth/register', [
            'name' => 'fake name now',
            'email' => 'fakeemaihappy',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email', 'The email must be a valid email address.');
    }

    /**
     * @return void
     */
    public function testItNotBeValidatePassword()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->post('/api/auth/register', [
            'name' => 'fake name now',
            'email' => 'fakeemaihappy@email.com',
            'password' => 'password123',
            'password_confirmation' => 'password123dd',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password', 'The password confirmation does not match.');
    }

    /**
     * @return void
     */
    public function testNotBeRegisterEmailHasBenTaken()
    {
        Sanctum::actingAs(User::factory()->create());

        User::factory()->create([
            'email' => 'secreting@email.com',
        ]);

        $response = $this->post('/api/auth/register', [
            'name' => 'fake name now',
            'email' => 'secreting@email.com',
            'password' => 'password123',
            'password_confirmation' => 'password123dd',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email', 'The email has already been taken.');
    }
}
