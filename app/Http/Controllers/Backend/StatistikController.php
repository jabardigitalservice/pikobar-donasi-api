<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Libraries\PostmanLibrary as PostLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StatistikController extends BaseBackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Statistik';
        $this->route = $this->routes['backend'] . 'statistics';
        $this->slug = $this->slugs['backend'] . 'statistics';
        $this->view = $this->views['backend'] . 'statistic';
        $this->breadcrumb = '<li><a href="' . route($this->route . '.index') . '">' . $this->menu . '</a></li>';
        # share parameters
        $this->share();
    }

    public function index()
    {
        try {
            $api = $this->baseUrl . "/api/v1/statistic/count";
            $response = PostLib::getJson($api, $this->accessToken);
            $count = $response->body['data']['item']['count'];
            $default = $response->body['data']['item']['default'];
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'List Statistik' . '</li>');
            return view($this->view . '.index', compact('breadcrumb', 'count', 'default'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

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
        $api = $this->baseUrl . "/api/v1/statistic?limit=$limit&page=$page&$sortBy";
        try {
            $response = PostLib::getJson($api, $this->accessToken);
            $totalRecords = $response->body['page_info']['total'];
            $totalRecordsFiltered = $response->body['page_info']['count'];
            $dataRecords = $response->body['data']['items'];
            $items = array();
            $num = 1;
            foreach ($dataRecords as $idx => $row) {
                $action = null;
                $status = null;
                $items[] = array(
                    "no" => $num,
                    "id" => $row['id'],
                    "personal_investor" => $row['personal_investor'],
                    "company_investor" => $row['company_investor'],
                    "total_goods" => $row['total_goods'],
                    "total_cash" => $row['total_cash'],
                    "date_input" => $row['date_input']
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

    public function showUpdate($id, Request $request)
    {
        try {
            $api = $this->baseUrl . "/api/v1/statistic/show/$id";
            $response = PostLib::getJson($api, $this->accessToken);
            $data = $response->body['data']['item'];
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'List Statistik' . '</li>');
            return view($this->view . '.form.update', compact('breadcrumb', 'data'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function showCreate()
    {
        try {
            $api = $this->baseUrl . "/api/v1/statistic/show-last";
            $response = PostLib::getJson($api, $this->accessToken);
            $data = $response->body['data']['item'];
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'Tambah data donasi' . '</li>');
            return view($this->view . '.form.create', compact('breadcrumb', 'data'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = array(
                'personal_investor' => $request->input('personal_investor'),
                'company_investor' => $request->input('company_investor'),
                'total_goods' => $request->input('total_goods'),
                'total_cash' => $request->input('total_cash'),
            );
            $api = $this->baseUrl . "/api/v1/statistic/create";
            $response = PostLib::postJson($api, $this->accessToken, $data);
            if ($response->code == 200) {
                return redirect()->route('backend::statistics.index')->with('success', "Sukses ubah data");
            } else {
                return redirect()
                    ->back()
                    ->withErrors($response->body['errors'][0])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $data = array(
                'personal_investor' => $request->input('personal_investor'),
                'company_investor' => $request->input('company_investor'),
                'total_goods' => $request->input('total_goods'),
                'total_cash' => $request->input('total_cash'),
            );
            $api = $this->baseUrl . "/api/v1/statistic/update/$id";
            $response = PostLib::postJson($api, $this->accessToken, $data);
            if ($response->code == 200) {
                return redirect()->route('backend::statistics.index')->with('success', "Sukses ubah data");
            } else {
                return redirect()
                    ->back()
                    ->withErrors($response->body['errors'][0])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}