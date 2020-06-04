<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Constants;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;

class DonateController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function getCategory(Request $request)
    {
        try {
            $data = Constants::DONATE_CATEGORIES;
            return Mapper::array($data, $request->method());
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }
}