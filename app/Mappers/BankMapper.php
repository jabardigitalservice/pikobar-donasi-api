<?php

namespace App\Mappers;

use App\Services\Mapper\BaseMapper;
use App\Services\Mapper\MapperContract;

class BankMapper extends BaseMapper implements MapperContract
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
            'code' => $item->code,
            'name' => $item->name,
            'xendit_code' => $item->xendit_code,
            'created_at' => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : null,
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
