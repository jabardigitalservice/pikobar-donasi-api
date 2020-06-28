<?php


namespace App\Mappers;

use App\Libraries\NumberLibrary;
use App\Services\Mapper\BaseMapper;
use App\Services\Mapper\MapperContract;
use Carbon\Carbon;

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
            'total_cash' =>  (float)number_format($item->total_cash, 2, '.', ''),
            //'date_input' => $item->date_input,
            'date_input' => Carbon::parse($item->date_input)->format('d-m-Y'),
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

    /**
     * Loop through single() function to generate multiple mapped data.
     *
     * @param $items
     * @return array
     */
    function list($items)
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $this->single($item);
        }
        return $result;
    }
}
