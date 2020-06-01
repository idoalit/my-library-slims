<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 29/05/20 20.15
 * @File name           : Sysconfig.php
 */

namespace Klaras\Utils;

class Sysconf
{
    static function get($key = null) {
        if (defined('SB')) {
            global $sysconf;
        } else {
            require_once __DIR__ . '/../../../../sysconfig.inc.php';
        }
        if (!isset($sysconf)) $sysconf = [];
        if (is_null($key)) return $sysconf;
        $keys = explode('.', $key);

        $tmp = null;
        foreach ($keys as $i => $key) {
            if ($i < 1) {
                if (isset($sysconf[$key])) {
                    $tmp = $sysconf[$key];
                    continue;
                }
                break;
            } else {
                if (is_array($tmp) && isset($tmp[$key])) {
                    $tmp = $tmp[$key];
                    continue;
                }
                break;
            }
        }
        return $tmp;
    }
}