<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestDashboardTest extends TestCase
{
    use RefreshDatabase;

    private Facility $facility;

    protected function setUp(): void
    {
        parent::setUp();

        $this->facility = Facility::create([
            'name' => 'Lapangan Olahraga FSM',
            'location' => 'Area FSM',
            'description' => 'Lapangan futsal dan basket.',
        ]);

        $user = User::factory()->create(['name' => 'Budi Pemesan']);

        Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $this->facility->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'status' => 'approved',
            'activity_name' => 'Latihan Futsal Angkatan',
            'organization' => 'HMIF',
            'nim' => '24060100000001',
            'phone' => '081234567890',
        ]);

        Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $this->facility->id,
            'start_time' => now()->addDays(2),
            'end_time' => now()->addDays(2)->addHour(),
            'status' => 'pending',
            'activity_name' => 'Rapat Rahasia Pending',
        ]);
    }

    public function test_guest_pages_are_accessible_without_login(): void
    {
        $this->get(route('guest.dashboard'))->assertOk();
        $this->get(route('guest.reservations'))->assertOk();
        $this->get(route('guest.facilities'))->assertOk();
    }

    public function test_guest_sees_only_approved_reservations(): void
    {
        $this->get(route('guest.reservations'))
            ->assertOk()
            ->assertSee('Latihan Futsal Angkatan')
            ->assertDontSee('Rapat Rahasia Pending');
    }

    public function test_guest_does_not_see_personal_data(): void
    {
        foreach (['guest.dashboard', 'guest.reservations'] as $route) {
            $this->get(route($route))
                ->assertDontSee('Budi Pemesan')
                ->assertDontSee('24060100000001')
                ->assertDontSee('081234567890');
        }
    }

    public function test_guest_sees_all_facilities_and_can_search(): void
    {
        Facility::create(['name' => 'Laboratorium Komputer', 'location' => 'Gedung A']);

        $this->get(route('guest.facilities'))
            ->assertSee('Lapangan Olahraga FSM')
            ->assertSee('Laboratorium Komputer');

        $this->get(route('guest.facilities', ['search' => 'Lab']))
            ->assertSee('Laboratorium Komputer')
            ->assertDontSee('Lapangan Olahraga FSM');
    }

    public function test_reservations_can_be_filtered_by_facility(): void
    {
        $other = Facility::create(['name' => 'Ruang Sidang', 'location' => 'Gedung B']);

        $this->get(route('guest.reservations', ['facility' => $other->id]))
            ->assertOk()
            ->assertDontSee('Latihan Futsal Angkatan');
    }

    public function test_login_page_links_to_guest_dashboard(): void
    {
        $this->get(route('login'))->assertSee(route('guest.dashboard'));
    }
}
