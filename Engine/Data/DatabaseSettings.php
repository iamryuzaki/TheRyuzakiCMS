<?php

if ($_SERVER['SCRIPT_NAME'] != '/index.php')
    die('This page has protected!');

class DatabaseSettings {
    public static $Host = 'localhost';
    public static $Port = 3306;
    public static $User = 'cms.host-eu.ru';
    public static $Pass = 'cms.host-eu.ru';
    public static $Base = 'cms.host-eu.ru';
    public static $Charset = 'utf8';
}

?>