<?php

use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    public function test_logout_action()
    {
        // Start the session
        Session::start();
        
        // Mock the logout method of the Auth facade
        Auth::shouldReceive('guard')->with('web')->andReturnSelf();
        Auth::shouldReceive('logout')->andReturn(true);

        // Create a fake request with a session
        $request = Request::create('/logout', 'POST');
        $request->setLaravelSession(app('session.store'));
 
        // Perform the logout action (e.g., make a POST request to /logout)
        $response = $this->app->make(LogoutController::class)->__invoke($request);
 
        // Assert the response status code
        $this->assertEquals(200, $response->getStatusCode());

        // Parse the JSON response content
        $data = json_decode($response->getContent(), true);

        // Assert the JSON data
        $this->assertEquals(['message' => 'Logged out'], $data);
    }
}
