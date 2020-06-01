<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 23/05/20 13.03
 * @File name           : Token.php
 */

namespace Klaras\Utils;


class Token
{
    const TOKEN_ACCESS_KEY = 'circulation_token_access';

    static function login()
    {
        $curl = curl_init();

        $payload = json_encode([
            'email' => Sysconf::get('my_library.username'),
            'password' => Sysconf::get('my_library.password')
        ]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://app.klaras.id/api/v1/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json"
            ),
            CURLOPT_POSTFIELDS => $payload
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            // save to database
            DB::insertOrUpdate('setting', ['setting_name' => self::TOKEN_ACCESS_KEY, 'setting_value' => serialize($response['data'])],
                "setting_name='" . self::TOKEN_ACCESS_KEY . "'");
            return $response;
        }
    }

    static function getToken()
    {
        $query = DB::getConnection()->query(sprintf("SELECT setting_value FROM setting WHERE setting_name = '%s'", self::TOKEN_ACCESS_KEY));
        $data = $query->fetch();
        if (isset($data[0])) {
            $raw = unserialize($data[0]);
            if (isset($raw['accessToken']))
                return $raw['accessToken'];
        }

        // try to login
        self::login();

        // call self
        return self::getToken();
    }
}