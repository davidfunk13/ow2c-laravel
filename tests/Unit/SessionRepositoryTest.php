<?php
namespace Tests\Unit\Repositories;

use App\Models\Session;
use App\Models\User;
use App\Repositories\SessionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $sessionRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sessionRepository = new SessionRepository();  // Ideally, you might want to use Laravel's container to resolve dependencies.
    }

    /** @test */
    public function it_stores_a_session_with_given_options()
    {
        $user = User::factory()->create();

        $options = [
            'user' => ['id' => $user->id],
            'starting_rank' => 'Gold',
            'rank' => 'Platinum',
            'starting_division' => 3,
            'division' => 2
        ];

        $session = $this->sessionRepository->store($options);

        $this->assertInstanceOf(Session::class, $session);
        $this->assertEquals($user->id, $session->user_id);
        $this->assertEquals('Gold', $session->starting_rank);
        $this->assertEquals('Platinum', $session->rank);
        $this->assertEquals(3, $session->starting_division);
        $this->assertEquals(2, $session->division);
        $this->assertDatabaseHas('sessions', [
            'user_id' => $user->id,
            'starting_rank' => 'Gold',
            'rank' => 'Platinum',
            'starting_division' => 3,
            'division' => 2,
        ]);
    }
}
