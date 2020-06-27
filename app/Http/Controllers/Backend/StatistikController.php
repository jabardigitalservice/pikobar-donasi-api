<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Unirest\Request as UniRequest;

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
            $breadcrumb = $this->breadcrumbs($this->breadcrumb . '<li class="active">' . 'List Statistik' . '</li>');
            return view($this->view . '.index', compact('breadcrumb'));
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
        $searchValue = $request->input('search')['value'];

        $headers = array('Authorization' => 'Bearer ' . $this->accessToken, 'Content-Type' => 'application/json');
        $api = $this->baseUrl . "/api/v1/statistic?limit=$limit&page=$page";
        $curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 60
        );
        UniRequest::curlOpts($curlOptions);
        UniRequest::jsonOpts(true, 512, JSON_UNESCAPED_SLASHES);
        UniRequest::verifyPeer(false); // Disables SSL cert validation
        try {
            $response = UniRequest::get($api, $headers, null);
            $totalRecords = $response->body['page_info']['total'];
            $totalRecordsFiltered = $response->body['page_info']['count'];
            $dataRecords = $response->body['data']['items'];
            //dd($dataRecords);
            $items = array();
            $num = 1;
            foreach ($dataRecords as $idx => $row) {
                /*if ($row['status'] == true) {
                    $row['status'] = 1;
                    $checked = 'checked';
                } else {
                    $row['status'] = 0;
                    $checked = '';
                }*/
                $action = null;
                $status = null;
                $items[] = array(
                    "no" => $num,
                    "id" => $row['id'],
                    "personal_investor" => $row['personal_investor'],
                    "company_investor" => $row['company_investor'],
                    "total_goods" => $row['total_goods'],
                    "total_cash" => $row['total_cash'],
                    "date_input" => $row['date_input'],
                    "action" => $action,
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
            //dd($e->getMessage());
            Log::error($e->getMessage());
        }
        //return response()->json(['error'], 500);
    }
}