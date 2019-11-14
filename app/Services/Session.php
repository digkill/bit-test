<?php

namespace App\Services;

class Session
{

    public function start()
    {
        session_start();
    }

    public function isActive()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function writeClose()
    {
        session_write_close();
    }

    public function destroy()
    {
        if ($this->isActive()) {
            session_unset();
            session_destroy();
        }
    }
}