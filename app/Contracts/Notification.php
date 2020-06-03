<?php

namespace App\Contracts;

interface Notification
{
    public function send($phone, $message);
}
