<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\StatistikRequest;
use App\Mappers\StatisticMapper;
use App\Models\Constants;
use App\Models\Statistic;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StatistikController extends ApiController
{
    /**
     * Get Investor detail, Only For Admin.
     * ini dipergunakan oleh admin untuk approval donatur,
     * yang nantinya akan ditransfer ke table warehouse (quantity langsung dihitung berdasarkan item).
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $item = Statistic::find(Constants::DEFAULT_STATISTIC_ID);
            if (!$item) {
                throw new \Exception("Invalid statistic Id");
            }
            return Mapper::single(new StatisticMapper(), $item, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function update(StatistikRequest $request)
    {
        \DB::beginTransaction();
        try {
            $item = Statistic::find(Constants::DEFAULT_STATISTIC_ID);
            if (!$item) {
                \DB::rollBack();
                throw new \Exception("Invalid statistic Id");
            }
            $item->personal_investor = $request->personal_investor;
            $item->company_investor = $request->company_investor;
            $item->total_goods = $request->total_goods;
            $cash = number_format($request->total_cash, 2, '.', '');
            $item->total_cash = $cash;
            // Update
            $item->update();
            \DB::commit();
            return Mapper::single(new StatisticMapper(), $item, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }
}