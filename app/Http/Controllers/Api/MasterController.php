<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mappers\BankMapper;
use App\Models\Bank;
use App\Models\Constants;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MasterController extends Controller
{
    public function getUom(Request $request)
    {
        try {
            $data = Constants::UOM;
            return Mapper::array($data, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function getInvestorCategory(Request $request)
    {
        try {
            $data = Constants::INVESTOR_CATEGORIES;
            return Mapper::array($data, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function getInvestorStatus(Request $request)
    {
        try {
            $data = Constants::INVESTOR_STATUS;
            return Mapper::array($data, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function getDonationType(Request $request)
    {
        try {
            $data = Constants::DONATION_CATEGORIES;
            return Mapper::array($data, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function getBank(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 141;
            $sort = $request->has('sort') ? $request->input('sort') : 'banks.name';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            if (!empty($search_term)) {
                $conditions .= " AND LOWER(banks.name) LIKE '%$search_term%'";
            }
            $paged = Bank::select('*')
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = Bank::count();
            return Mapper::list(new BankMapper(), $paged, $countAll, $request->method());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return Mapper::error($e->getMessage(), $request->method());
        }

    }
}