<?php

namespace App\Http\Controllers\Session;

use App\Http\Controllers\Controller;
use App\Http\Requests\Session\DestroyRequest;
use App\Repositories\SessionRepository;

class DestroyController extends Controller
{
    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function __invoke(DestroyRequest $request)
    {
        try {
            $this->sessionRepository->destroy($request->id);
        } catch (\Throwable $exception) {
            return $this->internalServerError('Session could not be destroyed.');
        }

        return response()->json([
            'message' => 'Session destroyed successfully.',
        ]);
    }
}
