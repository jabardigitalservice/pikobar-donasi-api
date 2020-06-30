<?php

namespace App\Mappers\Investor;

use App\Services\Mapper\BaseMapper;
use App\Services\Mapper\MapperContract;

class SembakoDonateMap extends BaseMapper implements MapperContract
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
            'investor_name' => $item->investor_name,
            'investor_phone' => $item->phone,
            'investor_email' => $item->email,
            'created_at' => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : null
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