<?php

namespace App\Socialite;

use GuzzleHttp\RequestOptions;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class BattleNetProvider extends AbstractProvider implements ProviderInterface
{
    public const IDENTIFIER = 'BATTLENET';

    protected $scopeSeparator = '+';

    protected static $region;

    protected function getAuthUrl($state)
    {
        $url = 'https://us.battle.net/oauth/authorize';

        return $this->buildAuthUrlFromBase($url, $state);
    }

    protected function getTokenUrl()
    {
        return "https://us.battle.net/oauth/token";
    }

    protected function getUserByToken($token)
    {
        $url = 'https://us.battle.net/oauth/userinfo';

        $response = $this->getHttpClient()->get($url, [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['id'],
            'nickname' => $user['battletag'],
            'name'     => null,
            'email'    => null,
            'avatar'   => null,
        ]);
    }

}
