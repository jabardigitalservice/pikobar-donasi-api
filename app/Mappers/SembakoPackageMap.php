<?php


namespace App\Mappers;

use App\Services\Mapper\BaseMapper;
use App\Services\Mapper\MapperContract;

class SembakoPackageMap extends BaseMapper implements MapperContract
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
            'sku' => $item->sku,
            'package_name' => $item->package_name,
            'package_description' => $item->package_description,
            'status' => $item->status,
            'created_at' => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : null,
            'items' => $item->items ? $item->items : [],
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
