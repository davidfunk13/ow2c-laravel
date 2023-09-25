<?php

namespace Tests\Feature\Session;

use App\Http\Controllers\Session\StoreController;
use App\Models\User;
use App\Repositories\SessionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\HasServerErrorResponseTrait;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;
    use HasServerErrorResponseTrait;
    /** @test */
    public function it_creates_a_session_successfully()
    {
        // Given
        $user = User::factory()->create();
        $this->actingAs($user);
        $payload = [
            'user' => ['id' => $user->id],
            'starting_rank' => 'Gold',
            'rank' => 'Platinum',
            'starting_division' => 3,
            'division' => 2
        ];

        // When
        $response = $this->postJson(route('session.store'), $payload);

        // Then
        $response->assertStatus(201);  // Assuming session creation results in a 201 Created status.
        $response->assertJsonStructure([
            'data' => [
                'user_id',
                'starting_rank',
                'rank',
                'starting_division',
                'division'
            ]
        ]);
        $this->assertDatabaseHas('sessions', [
            'user_id' => $user->id,
            'starting_rank' => 'Gold',
            'rank' => 'Platinum',
            'starting_division' => 3,
            'division' => 2,
        ]);
    }

    /** @test */
    /** @test */
    public function it_returns_internal_server_error_when_an_exception_is_thrown()
    {
        // Mock the StoreController's __invoke method to throw an exception.
        $this->app->bind(StoreController::class, function ($app) {
            $sessionRepoMock = $this->getMockBuilder(SessionRepository::class)
                ->getMock();

            $sessionRepoMock->expects($this->once())
            ->method('store')
            ->willReturn(null);

            $sessionRepoMock->expects($this->once())
                ->method('store')
                ->willThrowException(new \Exception('Session could not be created'));
                return new StoreController($sessionRepoMock);
        });

        $user = User::factory()->create();
        $payload = [
            'user' => ['id' => $user->id],
            'starting_rank' => 'Gold',
            'rank' => 'Platinum',
            'starting_division' => 3,
            'division' => 2
        ];

        $this->actingAs($user);

        // When
        $response = $this->post(route('session.store'), $payload);

        // Then
        $response->assertStatus(500);
        $response->assertExactJson($this->getInternalServerErrorStructure('Session could not be created'));
    }
}
