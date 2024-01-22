<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Http\Resources\OverwatchMapResource;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Repositories\Map\MapRepository;

class IndexController extends Controller
{
    use ServerErrorResponseTrait;

    protected MapRepository $mapRepository;

    public function __construct(MapRepository $mapRepository)
    {
        $this->mapRepository = $mapRepository;
    }
    
    public function __invoke()
    {
        try {
            $maps = $this->mapRepository->getAll();
        } catch (\Throwable $exception) {
            return $this->internalServerError('Maps could not be retrieved');
        }

        return OverwatchMapResource::collection($maps);
    }
}