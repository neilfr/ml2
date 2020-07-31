<?php

use Illuminate\Support\Facades\Session;

if (! function_exists('flash')) {
    /**
     * Flash a message in the session.
     *
     * @param  string  $message
     * @param  bool  $error
     * @return  void
     */
    function flash(string $message, bool $error = false): void
    {
        Session::put('flash', [
            'message' => $message,
            'isError' => $error,
        ]);
    }
}
