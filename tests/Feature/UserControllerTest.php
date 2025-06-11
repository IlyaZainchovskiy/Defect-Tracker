<?php
namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->admin()->create();
        $this->user = User::factory()->create();
        $this->department = Department::factory()->create();
    }

    /** @test */
    public function only_admin_can_access_users_management()
    {
        $this->actingAs($this->user)
            ->get(route('users.index'))
            ->assertStatus(403);

        $this->actingAs($this->admin)
            ->get(route('users.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_user()
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department_id' => $this->department->id,
            'role' => 'user',
        ];

        $this->actingAs($this->admin)
            ->post(route('users.store'), $userData)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);
    }

    /** @test */
    public function admin_can_toggle_user_active_status()
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->actingAs($this->admin)
            ->patch(route('users.toggle-active', $user))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function admin_cannot_deactivate_themselves()
    {
        $this->actingAs($this->admin)
            ->patch(route('users.toggle-active', $this->admin))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'is_active' => true,
        ]);
    }
}
