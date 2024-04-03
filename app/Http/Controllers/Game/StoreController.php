<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\StoreRequest;
use App\Http\Resources\GameResource;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Models\Game;
use App\Repositories\Game\GameRepository;

class StoreController extends Controller
{
    use ServerErrorResponseTrait;
    protected GameRepository $gameRepository;
    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }
    public function __invoke(StoreRequest $request)
    {
        // $this->authorize('create', Game::class);

        try {

            $request->merge(['user_id' => auth()->id()]);
            $game = $this->gameRepository->save($request->all());
        } catch (\Throwable $exception) {

            $message = $exception->getMessage();
            $user = auth()->user();
            return $this->internalServerError("Game could not be created, $message, $user");
        }

        return new GameResource($game);
    }
}