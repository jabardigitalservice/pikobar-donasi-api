<?php

namespace App\Mappers\Investor;

use App\Services\Mapper\BaseMapper;
use App\Services\Mapper\MapperContract;

class InvestorMapper extends BaseMapper implements MapperContract
{

    /**
     * Mapper class must implement this function in order to make list() function work.
     *
     * @param $item
     * @return mixed
     */
    function single($item)
    {
        foreach ($item->items as $key => $account) {

        }
        $itemList = array();
        foreach ($item->items as $idx => $dt) {
            $itemList[$idx] = $dt;
            $itemList[$idx]['bank_name'] = $dt->bank ? $dt->bank->name : null;
        }
        return [
            'id' => $item->id,
            'investor_name' => $item->investor_name,
            'phone' => $item->phone,
            'email' => $item->email,
            'address' => $item->address,
            'donate_category_name' => $item->donate_category_name,
            'donate_status_name' => $item->donate_status_name,
            'invoice_number' => $item->invoice_number,
            'attachment_id' => $item->attachment_id ? asset($item->files->getFileUrlAttribute()) : '',
            'items' => $item->items ? $itemList : [],
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