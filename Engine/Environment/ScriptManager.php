<?php

if ($_SERVER['SCRIPT_NAME'] != '/index.php')
    die('This page has protected!');
class ScriptManager {

    public static function Initialization() {
        if (isset($GLOBALS['Engine']['System']) == false)
            die('[ScriptManager]: ApplicationManager is not Initialized!');
        ApplicationManager::Using('System.IO.DirectoryInfo');
        $dirs = DirectoryInfo::GetDirectories('./Public/Script');
        foreach ($dirs as $dir) {
            if (is_file('./Public/Script/' . $dir . '/' . $dir . '.php')) {
                require_once './Public/Script/' . $dir . '/' . $dir . '.php';
                if (class_exists($dir)) {
                    $GLOBALS['Engine']['Script'][$dir] = true;
                    self::CallHookFrom($dir, 'OnLoaded');
                } else
                    echo '[ScriptManager]: Script ' . $dir . ' is not have class ' . $dir;
            } else
                echo '[ScriptManager]: Not found file {./Public/Script/' . $dir . '/' . $dir . '.php}';
        }
        self::CallHook('OnScriptManagerInitialized');
    }

    public static function CallHook($method, $args = array()) {
        if (isset($GLOBALS['Engine']['Script'])) {
            foreach ($GLOBALS['Engine']['Script'] as $k => $v)
                self::CallHookFrom($k, $method, $args);
        }
    }

    public static function CallHookFrom($class, $method, $args = array()) {
        if (method_exists($class, $method))
            call_user_func_array(array($class, $method), $args);
    }

}
