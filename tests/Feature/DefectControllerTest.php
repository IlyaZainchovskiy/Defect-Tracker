<?php
namespace Tests\Feature;

use App\Models\Defect;
use App\Models\Department;
use App\Models\Reason;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DefectControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->department = Department::factory()->create();
        $this->reason = Reason::factory()->create();
        $this->admin = User::factory()->admin()->create();
        $this->user = User::factory()->create(['department_id' => $this->department->id]);
    }

    /** @test */
    public function authenticated_user_can_view_defects_index()
    {
        $this->actingAs($this->user)
            ->get(route('defects.index'))
            ->assertStatus(200)
            ->assertViewIs('defects.index');
    }

    /** @test */
    public function guest_cannot_access_defects()
    {
        $this->get(route('defects.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_defect()
    {
        $defectData = [
            'date' => now()->format('Y-m-d'),
            'department_id' => $this->department->id,
            'product_type' => 'Test Product',
            'description' => 'Test defect description',
            'reason_id' => $this->reason->id,
        ];

        $this->actingAs($this->user)
            ->post(route('defects.store'), $defectData)
            ->assertRedirect(route('defects.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('defects', [
            'product_type' => 'Test Product',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function defect_creation_requires_valid_data()
    {
        $this->actingAs($this->user)
            ->post(route('defects.store'), [])
            ->assertSessionHasErrors([
                'date',
                'department_id',
                'product_type',
                'description',
                'reason_id'
            ]);
    }

    /** @test */
    public function user_can_only_view_their_own_defects()
    {
        $otherUser = User::factory()->create();
        $userDefect = Defect::factory()->create(['user_id' => $this->user->id]);
        $otherDefect = Defect::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->get(route('defects.show', $userDefect))
            ->assertStatus(200);

        $this->actingAs($this->user)
            ->get(route('defects.show', $otherDefect))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_all_defects()
    {
        $defect = Defect::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->admin)
            ->get(route('defects.show', $defect))
            ->assertStatus(200);
    }

    /** @test */
    public function user_can_update_their_own_defect()
    {
        $defect = Defect::factory()->create(['user_id' => $this->user->id]);
        
        $updateData = [
            'date' => now()->format('Y-m-d'),
            'department_id' => $this->department->id,
            'product_type' => 'Updated Product',
            'description' => 'Updated description',
            'reason_id' => $this->reason->id,
        ];

        $this->actingAs($this->user)
            ->put(route('defects.update', $defect), $updateData)
            ->assertRedirect(route('defects.index'));

        $this->assertDatabaseHas('defects', [
            'id' => $defect->id,
            'product_type' => 'Updated Product',
        ]);
    }

    /** @test */
    public function user_can_delete_their_own_defect()
    {
        $defect = Defect::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->delete(route('defects.destroy', $defect))
            ->assertRedirect(route('defects.index'));

        $this->assertDatabaseMissing('defects', ['id' => $defect->id]);
    }

    /** @test */
    public function defects_can_be_filtered_by_date_range()
    {
        $oldDefect = Defect::factory()->create([
            'date' => now()->subDays(10),
            'user_id' => $this->user->id
        ]);
        
        $newDefect = Defect::factory()->create([
            'date' => now(),
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('defects.index', [
                'date_from' => now()->subDays(5)->format('Y-m-d'),
                'date_to' => now()->format('Y-m-d')
            ]));

        $response->assertSee($newDefect->product_type)
                ->assertDontSee($oldDefect->product_type);
    }
}