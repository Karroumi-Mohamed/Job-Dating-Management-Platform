<?php

namespace App\Core;

class Controller
{
    protected function success($message)
    {
        Session::set('flash', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    protected function error($message)
    {
        Session::set('flash', [
            'type' => 'error',
            'message' => $message
        ]);
    }

    protected function warning($message)
    {
        Session::set('flash', [
            'type' => 'warning',
            'message' => $message
        ]);
    }

}
