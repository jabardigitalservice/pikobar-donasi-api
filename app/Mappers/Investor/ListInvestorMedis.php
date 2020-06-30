<?php

namespace App\Mappers\Investor;

use App\Services\Mapper\BaseMapper;
use App\Services\Mapper\MapperContract;

class ListInvestorMedis extends BaseMapper implements MapperContract
{
    /**
     * Loop through single() function to generate multiple mapped data.
     *
     * @param $items
     * @return array
     */
    function list($items)
    {

    }

    /**
     * Mapper class must implement this function in order to make list() function work.
     *
     * @param $item
     * @return mixed
     */
    function single($item)
    {

    }

    /**
     * Mapper for data create response.
     *
     * @param $item
     * @return mixed
     */
    function create($item)
    {

    }

    /**
     * Mapper for data edit response.
     *
     * @param $item
     * @return mixed
     */
    function edit($item)
    {

    }
}