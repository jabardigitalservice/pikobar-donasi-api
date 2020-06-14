<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Storage;
use Unirest\Request as UniRequest;

class SyncDatabase
{
    public static $SYNC_FILENAME = 'public/sync/' . 'logistik.json';

    public static function syncData()
    {
        $sort = 'matg_id';
        $order = 'asc';
        $limit = 1000;
        $start = 0;
        $headers = array('api-key' => config('covid19.api_key_logistic'));
        $api = config('covid19.api_url_logistic') . '/api/logistik';
        $curlOptions = array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 60
        );
        UniRequest::curlOpts($curlOptions);
        UniRequest::jsonOpts(true, 512, JSON_UNESCAPED_SLASHES);
        UniRequest::verifyPeer(false);
        $params = "?limit=$limit&skip=$start&sort=$sort:$order";
        try {
            $response = UniRequest::get($api . $params, $headers, null);
            if ($response->code == 200) {
                $dataRecords = $response->body['data'];
                $num = 1;
                foreach ($dataRecords as $idx => $row) {
                    $items[] = array(
                        "no" => $num,
                        "id" => (int)$row['id'],
                        "matg_id" => (string)$row['matg_id'],
                        "sisa" => (int)$row['sisa'],
                        "masuk" => (int)$row['masuk'],
                        "distribusi" => (int)$row['distribusi'],
                        "status_medis" => (bool)$row['status_medis'],
                    );
                    $num++;
                }
                self::writeData($items);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function writeData($items = [])
    {
        $exists = Storage::disk()->exists(self::$SYNC_FILENAME);
        if (!$exists) {
            self::write($items);
        } else {
            Storage::disk()->delete(self::$SYNC_FILENAME);
            self::write($items);
        }
    }

    private static function write($items = [])
    {
        $data = json_encode($items);
        Storage::disk()->put(self::$SYNC_FILENAME, $data);
    }
}