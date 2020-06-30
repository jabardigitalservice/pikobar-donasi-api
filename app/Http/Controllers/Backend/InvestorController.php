<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Libraries\PostmanLibrary as PostLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvestorController extends BaseBackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Donatur';
        $this->route = $this->routes['backend'] . 'investors';
        $this->slug = $this->slugs['backend'] . 'investors';
        $this->view = $this->views['backend'] . 'investor';
        $this->breadcrumb = '<li><a href="' . route($this->route . '.index') . '">' . $this->menu . '</a></li>';
        # share parameters
        $this->share();
    }

    public function index()
    {
        try {
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'List Donatur' . '</li>');
            return view($this->view . '.index', compact('breadcrumb'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    // TODO: TAMBAHKAN TANGGAL VERIFIKASI.
    public function getDatatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $page = (int)$start > 0 ? ($start / $length) + 1 : 1;
        $limit = (int)$length > 0 ? $length : 10;
        $columnIndex = $request->input('order')[0]['column'];
        $columnName = $request->input('columns')[$columnIndex]['data'];
        $columnSortOrder = $request->input('order')[0]['dir'];
        $sortBy = "order=$columnSortOrder&sort=$columnName";

        // search conditions.
        $searchInvestorType = $request->input('columns')[3]['search']['value'];
        $searchInvestorStatus = $request->input('columns')[7]['search']['value'];
        $searchInvestorName = $request->input('columns')[5]['search']['value'];

        //empty string for condition.
        $typeInvestor = '';
        $statusInvestor = '';
        $investorName = '';

        if (!empty($searchInvestorType)) {
            $typeInvestor = "&type=$searchInvestorType";
        }
        if (!empty($searchInvestorStatus)) {
            $statusInvestor = "&donate_status=$searchInvestorStatus";
        }
        if (!empty($searchInvestorName)) {
            $investorName = "&investor_name=$searchInvestorName";
        }
        $api = $this->baseUrl . "/api/v1/donate?limit=$limit&page=$page&$sortBy"
            . "$typeInvestor"
            . "$statusInvestor"
            . "$investorName";
        try {
            $response = PostLib::getJson($api, $this->accessToken);
            $totalRecords = $response->body['page_info']['total'];
            $totalRecordsFiltered = $response->body['page_info']['count'];
            $dataRecords = $response->body['data']['items'];
            $items = array();
            $num = 1;
            foreach ($dataRecords as $idx => $row) {
                $attachment_id = '<a target="_blank" href="' . $row['attachment_id'] . '"><i class="fa fa-file"></i></a>';
                $investor_name = '<a target="" href="' . route('backend::investors.showDetail', $row['id']) . '">' . $row['investor_name'] . '</a>';
                $action = null;
                $status = null;
                $items[] = array(
                    "no" => $num,
                    "id" => $row['id'],
                    "email" => $row['phone'],
                    "donate_category" => $row['donate_category'],
                    "investor_name" => $investor_name,
                    "donate_status_name" => $row['donate_status_name'],
                    "category_name" => $row['category_name'],
                    "donate_date" => $row['donate_date'],
                    "attachment_id" => $attachment_id,
                    "amount" => $row['amount'],
                    "quantity" => $row['quantity'],
                );
                $num++;
            }
            $outputResponse = array(
                "draw" => (int)$draw,
                "recordsTotal" => (int)$totalRecordsFiltered,
                "recordsFiltered" => (int)$totalRecords,
                "data" => $items
            );
            return response()->json($outputResponse);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function showDetail($id, Request $request)
    {
        try {
            $api = $this->baseUrl . "/api/public/v1/donate/show/$id";
            $response = PostLib::getJson($api, $this->accessToken);
            $data = $response->body['data']['item'];
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'Detail Donatur' . '</li>');
            return view($this->view . '.form', compact('breadcrumb', 'data'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

}