<?php
declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait AccessDeniedResponseTrait
{
    public function unauthorized(string $detail = 'You are not authenticated'): Response
    {
        return new JsonResponse([
            'title' => 'Unauthenticated',
            'type' => 'https://httpstatus.es/401',
            'status' => 401,
            'detail' => $detail,
        ], 401);
    }
    public function forbidden(string $detail = 'This action is forbidden'): Response
    {
        return new JsonResponse([
            'title' => 'Forbidden',
            'type' => 'https://httpstatus.es/403',
            'status' => 403,
            'detail' => $detail,
        ], 403);
    }
}
