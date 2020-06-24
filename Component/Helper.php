<?php

namespace App;

/**
 * Class Helper
 * @package App
 */
class Helper
{
    /**
     * Fetch all HTTP request headers
     *
     * @return array|false
     */
    public static function getRequestHeaders()
    {
        // If you using nginx instead of apache
        if (!function_exists('getallheaders')) {
            function getallheaders()
            {
                $headers = [];
                foreach ($_SERVER as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $headers[
                            str_replace(
                                ' ',
                                '-',
                                ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))
                        ] = $value;
                    }
                }
                return $headers;
            }
        }

        return getallheaders();
    }

    /**
     * Define request method
     *
     * @return mixed|string
     */
    public static function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $method = 'DELETE';
            } else {
                if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                    $method = 'PUT';
                } else {
                    $method = '';
                }
            }
        }

        return $method;
    }

    /**
     * Get request data
     *
     * @return array
     */
    public static function getRequestData()
    {
        $method = self::getRequestMethod();

        if ($method === 'GET') {
            return $_GET;
        }

        if ($method === 'POST') {
            return $_POST;
        }

        // PUT, PATCH or DELETE
        $data = array();
        $exploded = explode('&', file_get_contents('php://input'));

        foreach ($exploded as $pair) {
            $item = explode('=', $pair);
            if (count($item) == 2) {
                $data[urldecode($item[0])] = urldecode($item[1]);
            }
        }

        return $data;
    }

    /**
     * Get only the 3-digit HTTP response code
     *
     * @param $url
     * @return bool|string
     */
    public static function getHttpResponseCode($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    /**
     * @param string $message
     */
    public static function badRequest(string $message)
    {
        header('HTTP/1.0 400 Bad Request');
        exit(json_encode(array(
            'error' => $message
        )));
    }
}