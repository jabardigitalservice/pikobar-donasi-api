<?php

namespace App\Contracts;

interface Model
{
    /**
     * Declare sql method for custom query.
     *
     * @return string
     */
    public function sql();
}
