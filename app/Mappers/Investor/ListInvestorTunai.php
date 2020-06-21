<?php

namespace App\Mappers\Investor;

use App\Services\Mapper\BaseMapper;
use App\Services\Mapper\MapperContract;

class ListInvestorTunai extends BaseMapper implements MapperContract
{
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
            $result[$id]['investor_name'] = $item->investor_name;
            $result[$id]['email'] = $item->email;
            $result[$id]['donate_status_name'] = $item->donate_status_name;
            $result[$id]['category_name'] = $item->category_name;
            $date = new \DateTime($item->donate_date);
            $result[$id]['donate_date'] = $date->format('d-m-Y');
            $result[$id]['attachment_id'] = $item->attachment_id ? asset($item->files->getFileUrlAttribute()) : '';
            $result[$id]['profile_picture'] = $item->profile_picture ? asset($item->getProfilePictureAttribute()) : '';
            if (!empty($item->items)) {
                foreach ($item->items as $idx => $itemData) {
                    $result[$id]['amount'] = $itemData->amount;
                }
            }
        }
        return $result;
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