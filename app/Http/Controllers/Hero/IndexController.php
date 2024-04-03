<?php

namespace App\Http\Controllers\Hero;

use App\Http\Controllers\Controller;
use App\Http\Resources\OverwatchHeroResource;
use App\Http\Resources\OverwatchMapResource;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Repositories\Hero\HeroRepository;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    use ServerErrorResponseTrait;

    protected HeroRepository $heroRepository;

    public function __construct(HeroRepository $heroRepository)
    {
        $this->heroRepository = $heroRepository;
    }

    public function __invoke(Request $request, $heroType = null)
    {
        try {
            if ($heroType) {
                $heroes = $this->heroRepository->getByHeroType($heroType);
                return OverwatchHeroResource::collection($heroes);
            }

            $heroes = $this->heroRepository->getAll();
            return OverwatchHeroResource::collection($heroes);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Heroes could not be retrieved', $exception->getMessage()], 500);
        }
    }
}