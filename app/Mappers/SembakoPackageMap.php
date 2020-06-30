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

        foreach ($items as $id => $item) {
            $result[$id]['id'] = $item->id;
            $result[$id]['sku'] = $item->sku;
            $result[$id]['package_name'] = $item->package_name;
            $result[$id]['package_description'] = $item->package_description;
            $result[$id]['status'] = $item->status;
            $result[$id]['created_at'] = $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : null;
            $result[$id]['updated_at'] = $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : null;
            //$result[$id]['items'] =  $item->items ? $item->items : [];
        }
        return $result;
    }
}
