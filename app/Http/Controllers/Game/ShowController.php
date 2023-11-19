<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Repositories\Game\GameRepository;
class ShowController extends Controller
{
    use ServerErrorResponseTrait;

    protected GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke($gameId) // Replace with your actual route parameter name
    {
        $this->authorize('show', $gameId);

        try {
            $game = $this->gameRepository->findById($gameId);
        } catch (\Throwable $exception) {
            return $this->internalServerError('Game details could not be retrieved');
        }

        if (!$game) {
            return $this->notFoundError('Game not found');
        }

        return new GameResource($game);
    }
}
