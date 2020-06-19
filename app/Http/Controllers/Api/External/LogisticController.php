<?php

namespace App\Http\Controllers\Api\External;

use App\Http\Controllers\Controller;
use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;
use Unirest\Request as UniRequest;
use Webpatser\Uuid\Uuid;

class LogisticController extends Controller
{
    public function getMaterial(Request $request)
    {
        $sort = $request->has('sort') ? $request->input('sort') : 'matg_id';
        $order = $request->has('order') ? $request->input('order') : 'ASC';
        $limit = $request->has('limit') ? $request->input('limit') : 10;
        $start = $request->has('start') ? $request->input('start') : 0;
        $request->input('start');
        $search_term = $request->input('search');
        $headers = array('api-key' => config('covid19.api_key_logistic'));
        $api = config('covid19.api_url_logistic');
        $curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 60
        );
        UniRequest::curlOpts($curlOptions);
        UniRequest::jsonOpts(true, 512, JSON_UNESCAPED_SLASHES);
        UniRequest::verifyPeer(false);
        $params = "?limit=$limit&skip=$start&search=$search_term&sort=$sort:$order";
        try {
            $response = \Unirest\Request::get($api . '/master/material' . $params, $headers, null);
            if ($response->code == 200) {
                $dataRecords = $response->body['data'];
                $num = 1;
                foreach ($dataRecords as $idx => $row) {
                    $items[] = array(
                        "no" => $num,
                        "id" => $row['id'],
                        "uuid" => (string)Uuid::generate(4),
                        "donatur_id" => $row['donatur_id'],
                        "material_id" => $row['material_id'],
                        "uom" => $row['uom'],
                        "material_name" => $row['material_name'],
                        "matg_id" => $row['matg_id'],
                        "material_desc" => $row['material_desc'],
                        "donatur_name" => $row['donatur_name'],
                    );
                    $num++;
                }
                return Mapper::array($items, $request->method());
            } else {
                return Mapper::error($response->body['message'], $request->method());
            }
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }

    public function getLogisticNeeds(Request $request)
    {
        $sort = $request->has('sort') ? $request->input('sort') : 'matg_id';
        $order = $request->has('order') ? $request->input('order') : 'asc';
        $limit = $request->has('limit') ? $request->input('limit') : 1000;
        $start = $request->has('start') ? $request->input('start') : 0;

        $search_term = $request->input('search');
        $headers = array('api-key' => config('covid19.api_key_logistic'));
        $api = config('covid19.api_url_logistic') . '/api/logistik';
        $curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 800
        );
        UniRequest::curlOpts($curlOptions);
        UniRequest::jsonOpts(true, 512, JSON_UNESCAPED_SLASHES);
        UniRequest::verifyPeer(false);
        $params = "?limit=$limit&skip=$start&search=$search_term&sort=$sort:$order";
        try {
            if ($request->has('count')) {
                $response = \Unirest\Request::get($api . "?count=true", $headers, null);
            } else {
                $response = \Unirest\Request::get($api . $params, $headers, null);
            }
            if ($response->code == 200) {
                $dataRecords = $response->body['data'];
                $num = 1;
                if (!$request->has('count')) {
                    foreach ($dataRecords as $idx => $row) {
                        $items[] = array(
                            "no" => $num,
                            "id" => (string)Uuid::generate(4),
                            "id_pos" => (int)$row['id'],
                            "matg_id" => (string)$row['matg_id'],
                            "sisa" => (int)$row['sisa'],
                            "masuk" => (int)$row['masuk'],
                            "distribusi" => (int)$row['distribusi'],
                            "status" => (int)$row['status'],
                            "status_medis" => (int)$row['status_medis'],
                            "is_show" => 1,
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        );
                        $num++;
                    }
                    return Mapper::array($items, $request->method());
                } else {
                    return Mapper::object($dataRecords['count'], $request->method());
                }
            } else {
                return Mapper::error($response->body['message'], $request->method());
            }
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }
}