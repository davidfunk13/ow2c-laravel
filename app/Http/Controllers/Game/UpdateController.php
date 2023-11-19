<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\UpdateRequest;
use App\Http\Resources\GameResource;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Repositories\Game\GameRepository;
class UpdateController extends Controller
{
    use ServerErrorResponseTrait;

    protected GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(UpdateRequest $request, $gameId)
    {
        $this->authorize('update', $gameId);

        try {
            $game = $this->gameRepository->update($gameId, $request->all());

        } catch (\Throwable $exception) {
            return $this->internalServerError('Game could not be updated');
        }

        return new GameResource($game);
    }
}
