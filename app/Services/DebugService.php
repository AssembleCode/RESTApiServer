<?php

namespace App\Services;

class DebugService
{
    public static function getSqlWithBindings($query)
    {
        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $needle = '?';
        foreach ($bindings as $replace) {
            $pos = strpos($sql, $needle);
            if ($pos !== false) {
                if (gettype($replace) === "string") {
                    $replace = ' "' . addslashes($replace) . '" ';
                }
                $sql = substr_replace($sql, $replace, $pos, strlen($needle));
            }
        }
        return $sql;
    }

    public static function getSqlWithBindingsAndDie($query)
    {
        echo self::getSqlWithBindings($query);
        exit();
    }

    public static function dump($var, $detail = false) {
        if (is_array($var) || is_object($var)) {
            if ($detail == false) {
                echo "<pre>";
                print_r($var);
                echo "</pre>";
            } else {
                echo "<pre>";
                var_dump($var);
                echo "</pre>";
            }
        }
        else {
            if ($detail == false) {
                echo "<pre>";
                echo $var;
                echo "</pre>";
            } else {
                echo "<pre>";
                var_dump($var);
                echo "</pre>";
            }
        }
    }

    public static function dumpdie($var, $detail = false) {
        self::dump($var, $detail);
        die();
    }

    protected static function getPhpInfo()
    {
        ob_start();
        phpinfo();

        return ob_get_clean();
    }

}
