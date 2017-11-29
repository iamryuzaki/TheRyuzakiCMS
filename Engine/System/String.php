<?php

if ($_SERVER['SCRIPT_NAME'] != '/index.php')
    die('This page has protected!');

class String {
    public static function Split($seporator, $line, $bool_remove_empty = false) {
        if ($bool_remove_empty) 
            $result = preg_split('@'.$seporator.'@', $line, -1, PREG_SPLIT_NO_EMPTY);
        else
            $result = explode ($seporator, $line);
        return $result;
    }
}

?>