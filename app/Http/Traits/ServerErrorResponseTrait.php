<?php
declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ServerErrorResponseTrait
{    protected function internalServerError(string $detail = 'Internal server error'): Response
    {
        return new JsonResponse([
            'title' => 'Internal Server Error',
            'type' => 'https://httpstatus.es/500',
            'status' => 500,
            'detail' => $detail,
        ], 500);
    }
}
