<?php
namespace Tests\Feature;

use App\Models\Defect;
use App\Models\Department;
use App\Models\Reason;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->department = Department::factory()->create();
        $this->reason = Reason::factory()->create();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->admin()->create();
    }

    /** @test */
    public function authenticated_user_can_view_analytics()
    {
        $this->actingAs($this->user)
            ->get(route('analytics.index'))
            ->assertStatus(200)
            ->assertViewIs('analytics.index');
    }

    /** @test */
    public function analytics_displays_monthly_defects_data()
    {
        Defect::factory()->create([
            'date' => now()->subMonth(),
            'department_id' => $this->department->id,
            'reason_id' => $this->reason->id,
            'user_id' => $this->user->id
        ]);

        Defect::factory()->create([
            'date' => now(),
            'department_id' => $this->department->id,
            'reason_id' => $this->reason->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('analytics.index'));

        $response->assertViewHas('monthlyDefects');
        $monthlyDefects = $response->viewData('monthlyDefects');
        $this->assertCount(2, $monthlyDefects);
    }

    /** @test */
    public function analytics_shows_top_reasons()
    {
        $reason1 = Reason::factory()->create(['name' => 'Reason 1']);
        $reason2 = Reason::factory()->create(['name' => 'Reason 2']);

        Defect::factory()->count(3)->create([
            'reason_id' => $reason1->id,
            'department_id' => $this->department->id,
            'user_id' => $this->user->id
        ]);

        Defect::factory()->create([
            'reason_id' => $reason2->id,
            'department_id' => $this->department->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('analytics.index'));

        $topReasons = $response->viewData('topReasons');
        $this->assertEquals($reason1->id, $topReasons->first()->id);
        $this->assertEquals(3, $topReasons->first()->defects_count);
    }

    /** @test */
    public function only_admin_can_export_pdf()
    {
        $this->actingAs($this->user)
            ->get(route('analytics.export-pdf'))
            ->assertStatus(403);

        $this->actingAs($this->admin)
            ->get(route('analytics.export-pdf'))
            ->assertStatus(200);
    }
}