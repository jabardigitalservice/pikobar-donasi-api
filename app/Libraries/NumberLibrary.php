<?php

namespace App\Libraries;

class NumberLibrary
{
    /**
     * Konvert nilai decimal ke nilai romawi.
     *
     * @param $value
     * @return bool|mixed
     */
    public function decimalToRomawi($value)
    {
        if (!is_numeric($value) || $value > 3999 || $value <= 0) return false;
        $roman = array('M' => 1000, 'D' => 500, 'C' => 100, 'L' => 50, 'X' => 10, 'V' => 5, 'I' => 1);
        foreach ($roman as $k => $v) if (($amount[$k] = floor($value / $v)) > 0) $value -= $amount[$k] * $v;
        $return = '';
        foreach ($amount as $k => $v) {
            $return .= $v <= 3 ? str_repeat($k, $v) : $k . $old_k;
            $old_k = $k;
        }
        return str_replace(array('VIV', 'LXL', 'DCD'), array('IX', 'XC', 'CM'), $return);
    }
}