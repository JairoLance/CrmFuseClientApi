<?php

namespace App\Lib;

use Firebase\JWT\JWT,
    Exception;

class Auth
{
    private static $secret_key = '9a4P5K0o/UMajZLuSf9iK1lehlmEwqL5X89//9+ISYgMpqashAzaXXvluEih2rqhBDQsmP0fpYFeCU3uoJXzOA==';
    private static $encrypt = array('HS256');
    private static $aud = null;
    private static $minutes = 120;

    // Crea un nuevo token guardando la información del usuario que hemos autenticado
    public static function SignIn($data)
    {
        $time = time();

        $token = array(
            'exp'  => $time + (60 * self::$minutes),
            'aud'  => self::Aud(),
            'data' => $data
        );

        return JWT::encode($token, self::$secret_key);
    }

    // Verifica si el token ingresado es válido
    public static function Check($token)
    {
        try {

            if (empty($token)) {
                throw new Exception("Invalid token supplied.");
            }

            $decode = JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            );

            if ($decode->aud !== self::Aud()) {
                throw new Exception("Invalid $decode->aud user logged in. " . self::Aud());
            }

            return $decode;
        } catch (\Firebase\JWT\ExpiredException $e) { 
            throw new Exception("Tokens expired: ". $e->getMessage());
        }
    }

    // Obtiene la información del usuario guardada en el token
    public static function GetData($token)
    {

        return JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        )->data;
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }
}
