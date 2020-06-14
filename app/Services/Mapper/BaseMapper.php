<?php

namespace App\Services\Mapper;


/**
 * Class BaseMapper
 * @package App\Services\Mapper
 */
abstract class BaseMapper
{
    /**
     * Loop through single() function to generate multiple mapped data.
     *
     * @param $items
     * @return array
     */
    abstract function list($items);

    /**
     * Mapper class must implement this function in order to make list() function work.
     *
     * @param $item
     * @return mixed
     */
    abstract function single($item);

    /**
     * Mapper for data create response.
     *
     * @param $item
     * @return mixed
     */
    abstract function create($item);

    /**
     * Mapper for data edit response.
     *
     * @param $item
     * @return mixed
     */
    abstract function edit($item);
}
