<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class UnauthorizedController extends Controller
{
    public function __invoke()
    {
        $message = [
            'message' => 'Unauthorized'
        ];
        return response()->json($message, JsonResponse::HTTP_UNAUTHORIZED);
    }
}
