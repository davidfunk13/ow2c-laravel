<?php
declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait BadRequestResponseTrait
{
    public function badRequest(string $detail = 'Bad or malformed request'): Response
    {
        return new JsonResponse([
            'title' => 'Bad Request',
            'type' => 'https://httpstatus.es/400',
            'status' => 400,
            'detail' => $detail,
        ], 400);
    }
}
