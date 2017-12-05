<?php

class CMS_Session {
    public function __construct() {
        $_SESSION = array();
        if (isset($_COOKIE['SID']) == false) {
            $_COOKIE['SID'] = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . '-' . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . '-' . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . '-' . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        }
        setcookie('SID', $_COOKIE['SID'], time() + (3600 * $GLOBALS['Engine']['Settings']['session_lifetime']), '/');
    }
    
    public function OnSessionEnd() {
        $GLOBALS['Mysql']->query('INSERT INTO `sessions` (`session`,`useragent`,`time`,`ip`,`location`,`execution_time`,`data`) VALUES (?s,?s,NOW(),?s,?s,?s,?s) ON DUPLICATE KEY UPDATE `useragent`=?s,`time`=NOW(),`ip`=?s,`location`=?s,`execution_time`=?s,`data`=?s', $_COOKIE['SID'], $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], $GLOBALS['Engine']['Route'], substr(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 0, 5), json_encode($_SESSION), $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], $GLOBALS['Engine']['Route'], substr(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 0, 5), json_encode($_SESSION));
    }
}

?>