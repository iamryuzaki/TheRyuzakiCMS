<?php

if ($_SERVER['SCRIPT_NAME'] != '/index.php')
    die('This page has protected!');

class Uri {
    public static function GetRouteFrom($route) {
        ApplicationManager::Using('System.String');
        ApplicationManager::Using('Data.DefaultSettings');
        
        if ($route == null or $route == '' or $route == '/' or $route == '/index.php')
            $route = DefaultSettings::$DefaultIndexRoute;

        return array($route, String::Split('/', $route, true));
    }
    
    public static function SetCurrentLocation($location) {
        header('Location: '.$location);
        die();
    }
}

?>