<?php

namespace App;

/**
 * Class Auth
 * @package App
 */
class Auth
{
    // Bearer token
    const AUTH_HEADER_NAME = 'Authorization';

    /**
     * Auth constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get token from request headers
     *
     * @return mixed
     */
    private function getTokenFromHeaders()
    {
        $headers = Helper::getRequestHeaders();
        return $headers[self::AUTH_HEADER_NAME];
    }

    /**
     * Check user auth token
     *
     * @return bool
     */
    public function check()
    {
        // Запрос из таблицы юзеров нужных данных
        // $token = 'Bearer 3f16ecd8b19cd892f358c8c6f9a285a4';

        $userName = 'admin';
        $userPass = '1';
        $token = $this->getTokenFromHeaders();

        return $token == 'Bearer ' . hash_hmac('haval128,3', $userName . $userPass, 'mvcresttest');
    }
}