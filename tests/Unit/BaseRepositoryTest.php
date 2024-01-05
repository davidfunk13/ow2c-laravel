<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Repositories\BaseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tests\HasReflectiveTrait;

class BaseRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use HasReflectiveTrait;

    /** @test */
    public function it_handles_sort_without_relation_correctly()
    {
        $this->markTestSkipped();

        //convert this to make and sort games or something
        $userA = User::factory()->create();
        $sessionA = Game::factory()->create([
            'user_id' => $userA->id,
            'starting_rank' => 'A'
        ]);
        $sessionB = Game::factory()->create([
            'user_id' => $userA->id,
            'starting_rank' => 'B'
        ]);

        $repository = new class extends BaseRepository {
        };

        $queryBuilder = Game::query();

        $this->callInaccessibleMethod($repository, 'handleSort', [
            $queryBuilder,
            [
                'sortBy' => 'starting_rank',
                'sortDirection' => 'desc'
            ]
        ]);

        $Games = $queryBuilder->get();

        $this->assertEquals($sessionB->id, $sessions[0]->id);
        $this->assertEquals($sessionA->id, $sessions[1]->id);
        $userA->delete();
    }

    /** @test *//** @test */
    /** @test */
    public function it_handles_sort_with_relation_correctly()
    {
        $this->markTestSkipped();
       
        // convert to "Games"
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();
        $sessionA = Session::factory()->create(['user_id' => $userA->id]);
        $sessionB = Session::factory()->create(['user_id' => $userB->id]);
        $sessionC = Session::factory()->create(['user_id' => $userC->id]);

        $repository = new class extends BaseRepository {
        };

        $queryBuilder = Session::query();

        $this->callInaccessibleMethod($repository, 'handleSort', [
            $queryBuilder,
            [
                'sortBy' => 'user.name',
                'sortDirection' => 'asc'
            ]
        ]);

        $sessions = $queryBuilder->get();

        $this->assertEquals($userA->id, $sessionA->user_id);
        $this->assertEquals($userB->id, $sessionB->user_id);
        $this->assertEquals($userC->id, $sessionC->user_id);
        $userA->delete();
        $userB->delete();
        $userC->delete();
        $sessionA->delete();
        $sessionB->delete();
        $sessionC->delete();
    }

    /** @test */
    public function it_handles_relations_correctly()
    {
        $this->markTestSkipped();
        // convert to "Games"
       
        $userA = User::factory()->create();
        $session = Session::factory()->hasUser()->create([
            'user_id' => $userA->id
        ]);
        $user = $session->user;

        $repository = new class extends BaseRepository {
        };

        $queryBuilder = Session::query();

        $this->callInaccessibleMethod($repository, 'handleRelations', [$queryBuilder, [], ['with' => ['user']]]);

        $resultSession = $queryBuilder->first();

        $this->assertTrue(Schema::hasColumn('sessions', 'user_id'));
        $this->assertNotNull($resultSession->user);
        $this->assertEquals($user->id, $resultSession->user->id);
        $userA->delete();
        $session->delete();
    }
}
