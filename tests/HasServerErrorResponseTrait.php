<?php
declare(strict_types=1);

namespace Tests;

trait HasServerErrorResponseTrait
{
    protected function getInternalServerErrorStructure(?string $detail = 'Internal server error'): array
    {
        return [
            'title' => 'Internal Server Error',
            'type' => 'https://httpstatus.es/500',
            'status' => 500,
            'detail' => $detail,
        ];
    }
}
