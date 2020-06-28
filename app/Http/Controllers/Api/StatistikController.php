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
            $paged = Statistic::sql()
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = Statistic::count();
            return Mapper::list(new StatisticMapper(), $paged, $countAll, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    /**
     * Buat statistik baru pertanggal.
     *
     * @param StatistikRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(StatistikRequest $request)
    {
        \DB::beginTransaction();

        try {
            $model = new Statistic();
            if (Statistic::count() == 1) {
                $model->last_key = Constants::DEFAULT_STATISTIC_ID;
                $lastData = Statistic::find(Constants::DEFAULT_STATISTIC_ID)->first();
                if ($lastData->personal_investor === 0
                    && $lastData->personal_investor === 0
                    && $lastData->company_investor === 0
                    && $lastData->total_goods === 0
                    && $lastData->total_cash === "0.00") {
                    throw new \Exception("data default harus di update terlebih dahulu.");
                }
                if ($request->personal_investor < $lastData->personal_investor) {
                    throw new \Exception("data donatur perorangan harus >= dari sebelumnya");
                } else if ($request->company_investor < $lastData->company_investor) {
                    throw new \Exception("data donatur perusahaan harus >= dari sebelumnya");
                } else if ($request->total_goods < $lastData->total_goods) {
                    throw new \Exception("data barang terkumpul harus >= dari sebelumnya");
                } else if ($request->total_cash < $lastData->total_cash) {
                    throw new \Exception("data cash harus >= dari sebelumnya");
                }
            } else {
                $lastData = Statistic::where('is_last', '=', 1)->first();
                $model->last_key = $lastData->id;
                if ($request->personal_investor < $lastData->personal_investor) {
                    throw new \Exception("data donatur perorangan harus >= dari sebelumnya");
                } else if ($request->company_investor < $lastData->company_investor) {
                    throw new \Exception("data donatur perusahaan harus >= dari sebelumnya");
                } else if ($request->total_goods < $lastData->total_goods) {
                    throw new \Exception("data barang terkumpul harus >= dari sebelumnya");
                } else if ($request->total_cash < $lastData->total_cash) {
                    throw new \Exception("data cash harus >= dari sebelumnya");
                }
            }
            $model->id = (string)Uuid::generate(4)->string;
            $model->personal_investor = $request->personal_investor;
            $model->company_investor = $request->company_investor;
            $model->total_goods = $request->total_goods;
            $cash = number_format($request->total_cash, 2, '.', '');
            $model->total_cash = $cash;
            $model->date_input = now();
            $model->is_last = 1;

            // Update
            Statistic::where('is_last', 1)
                ->update(['is_last' => 0]);
            // Save
            $model->save();
            // Commit To DB
            \DB::commit();
            //Return Success
            return Mapper::single(new StatisticMapper(), $model, $request->method());
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
    public function showCount(Request $request)
    {
        try {
            $item = Statistic::find(Constants::DEFAULT_STATISTIC_ID)->first();
            $isDefault = false;
            if ($item->personal_investor === 0
                && $item->personal_investor === 0
                && $item->company_investor === 0
                && $item->total_goods === 0
                && $item->total_cash === "0.00") {
                $isDefault = true;
            }
            $countAll = Statistic::count();
            $item = [
                'count' => $countAll,
                'default' => $isDefault
            ];
            return Mapper::object($item, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function show($id, Request $request)
    {
        try {
            $item = Statistic::find($id);
            if (!$item) {
                throw new \Exception("Invalid Statistic Id");
            }
            return Mapper::single(new StatisticMapper(), $item, $request->method());
        } catch (\Exception $e) {
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

    /**
     * Update statistik.
     *
     * @param $id
     * @param StatistikRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
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
            // Commit To DB
            \DB::commit();
            // Return Success
            return Mapper::single(new StatisticMapper(), $item, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            \DB::rollBack();
            return Mapper::error($e->getMessage(), $request->method());
        }
    }
}