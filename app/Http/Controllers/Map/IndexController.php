<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Http\Resources\OverwatchMapResource;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Repositories\Map\MapRepository;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    use ServerErrorResponseTrait;

    protected MapRepository $mapRepository;

    public function __construct(MapRepository $mapRepository)
    {
        $this->mapRepository = $mapRepository;
    }

    public function __invoke(Request $request, $gameType = null)
    {
        try {
            if ($gameType) {
                $maps = $this->mapRepository->getByGameType($gameType);
                return OverwatchMapResource::collection($maps);
            }

            $maps = $this->mapRepository->getAll();
            return OverwatchMapResource::collection($maps);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Maps could not be retrieved', $exception->getMessage()], 500);
        }
    }
}