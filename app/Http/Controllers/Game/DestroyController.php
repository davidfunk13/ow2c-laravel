<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Repositories\Game\GameRepository;

class DestroyController extends Controller
{
    use ServerErrorResponseTrait;

    protected GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke($gameId) // Replace with your actual route parameter name
    {
        $this->authorize('destroy', $gameId);
        

        try {
            $this->gameRepository->destroy($gameId);
        } catch (\Throwable $exception) {
            return $this->internalServerError('Game could not be deleted');
        }

        return response()->json(['message' => 'Game deleted successfully']);
    }
}
