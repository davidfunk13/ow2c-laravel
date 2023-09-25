<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Session;
use App\Repositories\BaseRepository;

class SessionRepository extends BaseRepository
{
    public function store(array $options): ?Session
    {
        $session = new Session();
        $this->setFields($session, $options);

        if ($session->save()) {
            return $session;
        }

        return null;
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
