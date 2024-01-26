<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\Traits\DatabaseFunctions;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    private string $name = "Caio Weber Stuart 3";
    private string $email = "caiostuart03@gmail.com";
    private string $password = "stuartcaio01";
    private int $id;
    use DatabaseFunctions;

    public function __construct(){
        $this->__dbConstruct("users");
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->save(["name" => $this->name, "email" => $this->email, "password" => bcrypt($this->password)]);
    }

    public function test_get(): void
    {
        $response = $this->get('/users');

        $response->assertStatus(200);
    }

    protected function tearDown(): void
    {
        $this->delete(1);
        parent::tearDown();
    }
}
