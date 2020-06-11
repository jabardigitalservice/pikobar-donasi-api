<?php


namespace App\Mappers;

use App\Libraries\NumberLibrary;
use App\Services\Mapper\BaseMapper;
use App\Services\Mapper\MapperContract;

class StatisticMapper extends BaseMapper implements MapperContract
{
    /**
     * Map single object to desired result.
     *
     * @param $item
     * @return array|mixed
     */
    function single($item)
    {
        return [
            'id' => $item->id,
            'personal_investor' => $item->personal_investor,
            'company_investor' => $item->company_investor,
            'total_goods' => $item->total_goods,
            'total_cash' =>  (float)number_format($item->total_cash, 2, '.', '')
        ];
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
