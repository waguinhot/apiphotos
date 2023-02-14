<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @return  void
     */
    public function test_should_be_login()
    {
        User::create([
            'name' => 'secret',
            'email' => 'fakesecret@email.com',
            'password' => Hash::make('secret123')
        ]);

        $response = $this->post('api/sanctum/token',[
            'email' => 'fakesecret@email.com',
            'password' => 'secret123',
            'device_name' => 'underground'
        ]);

        $response->assertStatus(200);
    }

    /**
     * @return  void
     */
    public function test_should_not_be_valid_email()
    {
        $response = $this->post('api/sanctum/token',[
            'email' => 'fakesecret',
            'password' => 'secret123',
            'device_name' => 'underground'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email' , 'The email must be a valid email address.');
    }


     /**
     * @return  void
     */
    public function test_should_not_be_valid_password()
    {
        $response = $this->post('api/sanctum/token',[
            'email' => 'fakesecret@email.com',
            'password' => '',
            'device_name' => 'underground'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password' , 'The password field is required');
    }

     /**
     * @return  void
     */
    public function test_should_not_be_valid_device_name()
    {
        $response = $this->post('api/sanctum/token',[
            'email' => 'fakesecret@email.com',
            'password' => 'secret',
            'device_name' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('device_name' , 'The device name field is required.');
    }
}
