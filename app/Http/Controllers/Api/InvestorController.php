<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Constants;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    public function getCategory(Request $request)
    {
        try {
            $data = Constants::INVESTOR_CATEGORIES;
            return Mapper::array($data, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }
}