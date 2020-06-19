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
use Webpatser\Uuid\Uuid;

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
            $limit = $request->has('limit') ? $request->input('limit') : 50;
            $sort = $request->has('sort') ? $request->input('sort') : 'statistics.created_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $paged = Statistic::select('*')
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = Statistic::count();
            return Mapper::list(new StatisticMapper(), $paged, $countAll, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function create(StatistikRequest $request)
    {
        \DB::beginTransaction();
        try {
            $item = new Statistic();
            if (Statistic::count() == 1) {
                $item->last_key = Constants::DEFAULT_STATISTIC_ID;
                $lastData = Statistic::find(Constants::DEFAULT_STATISTIC_ID)->first();
                if ($request->personal_investor < $lastData->personal_investor) {
                    throw new \Exception("data personal harus >= dari sebelumnya");
                } else if ($request->company_investor < $lastData->company_investor) {
                    throw new \Exception("data company harus >= dari sebelumnya");
                } else if ($request->total_goods < $lastData->total_goods) {
                    throw new \Exception("data barang harus >= dari sebelumnya");
                } else if ($request->total_cash < $lastData->total_cash) {
                    throw new \Exception("data cash harus >= dari sebelumnya" );
                }
            } else {
                $lastData = Statistic::where('is_last', '=', 1)->first();
                $item->last_key = $lastData->id;
                if ($request->personal_investor < $lastData->personal_investor) {
                    throw new \Exception("data personal harus >= dari sebelumnya");
                } else if ($request->company_investor < $lastData->company_investor) {
                    throw new \Exception("data company harus >= dari sebelumnya");
                } else if ($request->total_goods < $lastData->total_goods) {
                    throw new \Exception("data barang harus >= dari sebelumnya");
                } else if ($request->total_cash < $lastData->total_cash) {
                    throw new \Exception("data cash harus >= dari sebelumnya" );
                }
            }
            $item->id = (string)Uuid::generate(4)->string;
            $item->personal_investor = $request->personal_investor;
            $item->company_investor = $request->company_investor;
            $item->total_goods = $request->total_goods;
            $cash = number_format($request->total_cash, 2, '.', '');
            $item->total_cash = $cash;
            $item->date_input = now();
            $item->is_last = 1;

            // Update
            \DB::table('statistics')
                ->where('is_last', 1)
                ->update(['is_last' => 0]);
            // Save
            $item->save();

            \DB::commit();
            return Mapper::single(new StatisticMapper(), $item, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    /**
     * Get Investor detail, Only For Admin.
     * ini dipergunakan oleh admin untuk approval donatur,
     * yang nantinya akan ditransfer ke table warehouse (quantity langsung dihitung berdasarkan item).
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function showLastStatistic(Request $request)
    {
        try {
            $item = Statistic::where('is_last', '=', 1)->first();
            if (!$item) {
                throw new \Exception("Invalid Investor Id");
            }
            return Mapper::single(new StatisticMapper(), $item, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function update($id, StatistikRequest $request)
    {
        \DB::beginTransaction();
        try {
            $item = Statistic::find($id);
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