<?php

namespace App\Http\Controllers\Session\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Session\SessionRequest;
use App\Http\Requests\Session\StoreRequest;
use App\Http\Resources\SessionResource;
use App\Http\Traits\ServerErrorResponseTrait;
use App\Models\Session;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    use ServerErrorResponseTrait;
    protected SessionRepository $sessionRepository;
    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }
    public function __invoke(StoreRequest $request)
    {
        try {
            $session = $this->sessionRepository->store($request->all());
        } catch (\Throwable $exception) {
            return $this->internalServerError('Session could not be created');
        }

        return new SessionResource($session);
    }
}
