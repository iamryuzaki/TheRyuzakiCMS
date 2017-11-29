<?php

if ($_SERVER['SCRIPT_NAME'] != '/index.php')
    die('This page has protected!');

class DefaultSettings {
    public static $DefaultIndexRoute = '/index.html';
    public static $DefaultSystemName = 'Www';
}

?>