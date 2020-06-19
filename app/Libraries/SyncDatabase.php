<?php

namespace App\Libraries;

use App\Models\Material;
use Illuminate\Support\Facades\Storage;
use Unirest\Request as UniRequest;
use Webpatser\Uuid\Uuid;

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
                $unix_timestamp = now();
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
                    );
                    $num++;
                }
                $dataItems = array('time' => $unix_timestamp, 'data' => $items);
                self::writeData($dataItems);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function updateSyncData()
    {
        $fileJson = Storage::disk()->get(self::$SYNC_FILENAME);
        $data = json_decode($fileJson, true);
        foreach ($data['data'] as $row) {
            $item = Material::where('id_pos', $row['id_pos'])->first();
            if ($item) {
                $item->sisa = $row['sisa'];
                $item->masuk = $row['masuk'];
                $item->distribusi = $row['distribusi'];
                $item->status_medis = $row['status_medis'];
                $item->update();
            } else {
                $title = $row['matg_id'];
                if ($title === "") {
                    $title = "NO_NAME";
                }
                $itemSync = new Material();
                $itemSync->id = $row['id'];
                $itemSync->id_pos = $row['id_pos'];
                $itemSync->matg_id = $title;
                $itemSync->sisa = (int)$row['sisa'];
                $itemSync->masuk = (int)$row['masuk'];
                $itemSync->distribusi = (int)$row['distribusi'];
                $itemSync->status_medis = (int)$row['status_medis'];
                $itemSync->status = (int)$row['status'];
                $itemSync->is_show = 1;
                $itemSync->created_at = date('Y-m-d H:i:s');
                $itemSync->updated_at = date('Y-m-d H:i:s');
                $itemSync->save();
            }
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

    /**
     * Mass (bulk) insert or update on duplicate
     *
     * insertOrUpdate([
     *   ['id'=>1,'value'=>10],
     *   ['id'=>2,'value'=>60]
     * ]);
     *
     *
     * @param array $rows
     * @return object
     */
    private function insertOrUpdate(array $rows)
    {
        $table = \DB::getTablePrefix() . with(new self)->getTable();
        $first = reset($rows);
        $columns = implode(',',
            array_map(function ($value) {
                return "$value";
            }, array_keys($first))
        );

        $values = implode(',', array_map(function ($row) {
                return '(' . implode(',',
                        array_map(function ($value) {
                            return '"' . str_replace('"', '""', $value) . '"';
                        }, $row)
                    ) . ')';
            }, $rows)
        );

        $updates = implode(',',
            array_map(function ($value) {
                return "$value = VALUES($value)";
            }, array_keys($first))
        );

        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

        return \DB::statement($sql);
    }
}