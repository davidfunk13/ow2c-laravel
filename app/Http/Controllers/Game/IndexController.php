<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Models\Game;
use App\Repositories\Game\GameRepository;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    use ServerErrorResponseTrait;

    protected GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(Request $request)
    {
        $game = new Game();
        $this->authorize('index', $game);

        try {
            $games = $request->has('per_page') ? 
                $this->gameRepository->getAllPaginated($request) : 
                $this->gameRepository->getAll();
        } catch (\Throwable $exception) {
            return $this->internalServerError('Games could not be retrieved');
        }

        return GameResource::collection($games);
    }
}