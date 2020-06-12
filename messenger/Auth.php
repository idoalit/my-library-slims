<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 10/06/20 22.45
 * @File name           : Auth.php
 */

namespace Messenger;

use Klaras\Utils\Token;

class Auth
{
    use Commons;

    protected $token;

    /**
     * Get header Authorization
     * */
    function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } else if (isset($_SERVER['HTTP_X_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_X_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    static function verify($block = true)
    {
        // get last setting
        $setting = \ORM::forTable('setting')->where('setting_name', 'crawl_token')->findOne();

        // create instance
        $auth = new static();

        // first request
        if (password_verify($auth->config('my_library.password'), $auth->getBearerToken())
            || $setting->setting_value === $auth->getBearerToken()) {
            $auth->setToken(password_hash($auth->config('my_library.username') . '_' . date('Ymd-Hi'), PASSWORD_DEFAULT));

            // save to setting
            if (!$setting) {
                $setting = \ORM::for_table('setting')->create();
                $setting->setting_name = 'crawl_token';
            }
            $setting->setting_value = $auth->getToken();
            $setting->save();
        } else if ($block) {
            http_response_code(401);
            exit();
        }

        return $auth;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return Auth
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    function callback()
    {
    }
}