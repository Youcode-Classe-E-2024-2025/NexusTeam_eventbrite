<?php

namespace App\Core;

class Session
{

    public static function start(): void{
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value):void{
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key): mixed{
        self::start();
        return $_SESSION[$key];
    }

    public static function destroy(): void{
        self::start();
        session_destroy();
    }

}