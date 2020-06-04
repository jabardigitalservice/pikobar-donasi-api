<?php

namespace App\Http\Controllers\Api;

use App\Services\Mapper\Facades\Mapper;
use Illuminate\Http\Request;
use Unirest\Request as UniRequest;
use Webpatser\Uuid\Uuid;

class MasterController
{
    private $url = 'https://dashboard-pikobar.digitalservice.id/api-pt-pos/master/material';

    public function getMaterial(Request $request)
    {
        $sort = $request->has('sort') ? $request->input('sort') : 'matg_id';
        $order = $request->has('order') ? $request->input('order') : 'ASC';
        $limit = $request->has('limit') ? $request->input('limit') : 10;
        $start = $request->has('start') ? $request->input('start') : 0;
        $request->input('start');
        $search_term = $request->input('search');
        $headers = array('api-key' => config('covid19.api_key_donate'));
        $api = config('covid19.api_url_logistic');
        $curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 60
        );
        UniRequest::curlOpts($curlOptions);
        UniRequest::jsonOpts(true, 512, JSON_UNESCAPED_SLASHES);
        UniRequest::verifyPeer(false); // Disables SSL cert validation
        $params = "?limit=$limit&skip=$start&search=$search_term&sort=$sort:$order";
        try {
            $response = UniRequest::get($api . $params, $headers, null);

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
                return Mapper::error($response, $request->method());
            }
        } catch (\Exception $e) {
            return Mapper::error($e->getMessage(), $request->method());
        }
    }
}