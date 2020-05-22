<?php

namespace App\Classes;

class Session
{
    public function getCurrent(string $name): ?object
    {
        $session = null;

        if (isset($_SESSION[$name])) {
            $session = $_SESSION[$name];
        }

        return $session;
    }

    public function setSession(string $name, $parameter)
    {
        $_SESSION[$name] = $parameter;
    }
}
