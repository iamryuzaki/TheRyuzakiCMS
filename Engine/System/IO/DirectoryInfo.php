<?php

if ($_SERVER['SCRIPT_NAME'] != '/index.php')
    die('This page has protected!');

class DirectoryInfo {

    public static function GetDirectories($path) {
        $result = array();
        if (is_dir($path)) {
            $dir = opendir($path);
            while ($file = readdir($dir))
                if ($file != '.' && $file != '..' && is_dir($path . '/' . $file))
                    $result[] = $file;
        }
        return $result;
    }

}

?>