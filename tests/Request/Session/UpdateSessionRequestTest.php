<?php

namespace Tests\Request\Session;

use App\Http\Requests\Session\UpdateRequest;
use App\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use Illuminate\Routing\Route;
class UpdateSessionRequestTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    use RefreshDatabase;

    protected $user;
    protected $anotherUser;
    protected $session;
    protected $request;

    public function setUp(): void
    {
        parent::setUp();

        // Create users and session
        $this->user = User::factory()->create();
        $this->anotherUser = User::factory()->create();
        $this->session = Session::factory()->create(['user_id' => $this->anotherUser->id]);

        // Initialize the UpdateRequest
        $this->request = new UpdateRequest();
        $this->request->setUserResolver(fn () => $this->user);
    }
    public function authorize_returns_false_if_user_does_not_own_the_session()
    {
        $this->request->merge(['session' => $this->session->id]);
        $this->assertFalse($this->request->authorize());
    }

    /** @test */public function authorize_returns_true_if_user_owns_the_session_and_caches_the_result()
{
    Cache::flush(); // Clear cache before the test
    $this->actingAs($this->user); // Make sure user is authenticated

    // Mock the route parameter
    $this->request->setRouteResolver(function () {
        $mockRoute = $this->createMock(Route::class);
        $mockRoute->expects($this->any())
                  ->method('parameter')
                  ->with('session.update')
                  ->willReturn($this->session->id);
        return $mockRoute;
    });
    dd($this->request->authorize());
    $this->assertTrue($this->request->authorize());

    $cachedKey = "authorize:{$this->user->id}:{$this->session->id}";
    $this->assertTrue(Cache::has($cachedKey));
}
    /** @test */
    public function it_has_the_expected_validation_rules()
    {
        $request = new UpdateRequest();

        $this->assertEquals([
            'starting_rank' => 'nullable|string',
            'rank' => 'nullable|string',
            'starting_division' => 'nullable|integer',
            'division' => 'nullable|integer',
        ], $request->rules());
    }

    /** @test */
    public function it_fails_when_no_fields_are_filled()
    {
        $data = []; // empty data

        $request = new UpdateRequest();

        $validator = Validator::make($data, $request->rules());

        $request->withValidator($validator);

        $this->assertTrue($validator->fails());
        $this->assertEquals('At least one of the following fields must be present: starting_rank, rank, starting_division, division.', $validator->errors()->first('fields'));
    }

    /** @test */
    public function it_passes_when_at_least_one_field_is_filled()
    {
        $data = ['starting_rank' => 'Silver']; // Only one field is filled

        $request = new UpdateRequest();

        $validator = Validator::make($data, $request->rules());

        $request->withValidator($validator);

        $this->assertFalse($validator->fails());
    }
}
