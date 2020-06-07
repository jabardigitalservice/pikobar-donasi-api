<?php

namespace App\Libraries;

class ConstantParser
{
    public static function searchById($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['id'] === $id) {
                return $val;
            }
        }
        return null;
    }

    public static function searchBySlug($slug, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['slug'] === $slug) {
                return $val;
            }
        }
        return null;
    }
}