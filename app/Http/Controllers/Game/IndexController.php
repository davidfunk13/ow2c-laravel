<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Repositories\Game\GameRepository;

class IndexController extends Controller
{
    use ServerErrorResponseTrait;

    protected GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke()
    {
        $this->authorize('index');

        try {
            $games = $this->gameRepository->getAll();
        } catch (\Throwable $exception) {
            return $this->internalServerError('Games could not be retrieved');
        }

        return GameResource::collection($games);
    }
}
