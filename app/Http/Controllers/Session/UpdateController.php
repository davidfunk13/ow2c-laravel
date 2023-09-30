<?php

namespace App\Http\Controllers\Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    // update session logic using the request, and the update method in the session repository
    public function __invoke(Request $request)
    {
        $session = $request->user()->sessions()->find($request->session);
        dd($session);
        // $session->update($request->all());
        // return response()->json([
        //     'data' => $session
        // ], 200);
    }

}
