<?php

namespace Tests\Unit\Repositories;

use App\Http\Traits\ServerErrorResponseTrait;
use App\Models\Session;
use App\Models\User;
use App\Repositories\SessionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SessionRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use ServerErrorResponseTrait;
    protected $sessionRepository;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->sessionRepository = new SessionRepository();  // Ideally, you might want to use Laravel's container to resolve dependencies.
    }

    /** @test */
    public function it_stores_a_session_with_given_options()
    {

        $options = [
            'user' => ['id' => $this->user->id],
            'starting_rank' => 'Gold',
            'rank' => 'Platinum',
            'starting_division' => 3,
            'division' => 2
        ];

        $session = $this->sessionRepository->store($options);

        $this->assertInstanceOf(Session::class, $session);
        $this->assertEquals($this->user->id, $session->user_id);
        $this->assertEquals('Gold', $session->starting_rank);
        $this->assertEquals('Platinum', $session->rank);
        $this->assertEquals(3, $session->starting_division);
        $this->assertEquals(2, $session->division);
        $this->assertDatabaseHas('sessions', [
            'user_id' => $this->user->id,
            'starting_rank' => 'Gold',
            'rank' => 'Platinum',
            'starting_division' => 3,
            'division' => 2,
        ]);
    }
    public function testDestroySuccessfullyDeletesSession()
    {
        $session = Session::factory()->create();

        $result = $this->sessionRepository->destroy($session->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('sessions', ['id' => $session->id]);
    }

    public function testDestroyHandlesException()
    {
        // Arrange
        $invalidId = 9999;

        // Act
        $result = $this->sessionRepository->destroy($invalidId);

        // Assert
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $result->getStatusCode());
        $expectedResponse = json_decode($this->internalServerError('Session could not be destroyed.')->getContent());
        $actualResponse = json_decode($result->getContent());

        $this->assertEquals($expectedResponse, $actualResponse);
    }
}
