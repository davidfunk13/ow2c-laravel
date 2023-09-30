<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Traits\ServerErrorResponseTrait;
use App\Models\Session;
use App\Repositories\BaseRepository;
use Symfony\Component\HttpFoundation\Response;

class SessionRepository extends BaseRepository
{
    use ServerErrorResponseTrait;
    public function store(array $options): ?Session
    {
        $session = new Session();
        $this->setFields($session, $options);

        if ($session->save()) {
            return $session;
        }

        return null;
    }
    public function destroy(int $id): Response|bool
    {
        try {
            $session = Session::find($id);
            $session->delete();
            return true;
        } catch (\Throwable $exception) {
            return $this->internalServerError('Session could not be destroyed.');
        }
    }

    protected function setFields(Session &$session, array $options): void
    {
        $session->user_id = $options['user']['id'];
        $session->starting_rank = $options['starting_rank'];
        $session->rank = $options['rank'] ?? null;
        $session->starting_division = $options['starting_division'];
        $session->division = $options['division'] ?? null;
    }
}
