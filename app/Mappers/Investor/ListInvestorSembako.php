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
        $result = [];
        foreach ($items as $id => $item) {
            $result[$id]['id'] = $item->id;
            $result[$id]['invoice_number'] = $item->invoice_number;
            $result[$id]['investor_name'] = $item->investor_name;
            $result[$id]['email'] = $item->email;
            $result[$id]['phone'] = $item->phone;
            $result[$id]['donate_category'] = $item->donate_category;
            $result[$id]['donate_status_name'] = $item->donate_status_name;
            $result[$id]['category_name'] = $item->category_name;
            $date = new \DateTime($item->donate_date);
            $result[$id]['donate_date'] = $date->format('d-m-Y');
            $result[$id]['attachment_id'] = $item->attachment_id ? asset($item->files->getFileUrlAttribute()) : '';
            $result[$id]['profile_picture'] = $item->profile_picture ? asset($item->getProfilePictureAttribute()) : '';
            //$result[$id]['items'] = $item->items ? $item->items : [];
            $result[$id]['amount'] = 0;
            if (!empty($item->items)) {
                $qty = 0;
                foreach ($item->items as $idx => $itemData) {
                    $qty += $itemData->quantity;
                    $result[$id]['item_package_name'] = $itemData->item_package_name;
                    $result[$id]['quantity'] = $qty;
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