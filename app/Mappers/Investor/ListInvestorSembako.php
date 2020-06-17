<?php

namespace App\Mappers\Investor;

use App\Services\Mapper\BaseMapper;
use App\Services\Mapper\MapperContract;

class ListInvestorSembako extends BaseMapper implements MapperContract
{
    /**
     * Loop through single() function to generate multiple mapped data.
     *
     * @param $items
     * @return array
     */
    function list($items)
    {
        // TODO: Implement list() method.
    }

    /**
     * Mapper class must implement this function in order to make list() function work.
     *
     * @param $item
     * @return mixed
     */
    function single($item)
    {
        // TODO: Implement single() method.
    }

    /**
     * Mapper for data create response.
     *
     * @param $item
     * @return mixed
     */
    function create($item)
    {
        // TODO: Implement create() method.
    }

    /**
     * Mapper for data edit response.
     *
     * @param $item
     * @return mixed
     */
    function edit($item)
    {
        // TODO: Implement edit() method.
    }
}