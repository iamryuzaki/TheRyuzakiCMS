<?php

if ($_SERVER['SCRIPT_NAME'] != '/index.php')
    die('This page has protected!');

class ApplicationManager {

    public static function Initialization() {
        self::InitializationPreCheck();
        self::InitializationRoute();
        self::InitializationMysql();
    }

    public static function InitializationTemplate() {
        $GLOBALS['Engine']['Template']['Template'] = '';
        $GLOBALS['Engine']['Template']['Content'] = '';
        ScriptManager::CallHook('OnInitializationTemplate');

        ob_start();
        if (is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Template/main.php')) {
            if (is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Template/header.php'))
                eval(' ?>' . file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Template/header.php') . '<?php ');

            self::InitializationContent();
            eval(' ?>' . file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Template/main.php') . '<?php ');

            if (is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Template/footer.php'))
                eval(' ?>' . file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Template/footer.php') . '<?php ');
        } else
            ScriptManager::CallHook('OnInitializationTemplate_NoTemplate');
        $GLOBALS['Engine']['Template']['Template'] = ob_get_clean();

        ScriptManager::CallHook('OnInitializationTemplateFinish');
    }

    private static function InitializationContent() {
        ScriptManager::CallHook('OnInitializationContent');
        $path = '';
        foreach ($GLOBALS['Engine']['GET'] as $k => $v) {
            $path .= '/' . $v;

            $config = array();
            if (is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/config.json'))
                $config = json_decode(file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/config.json'), true);
            ScriptManager::CallHook('OnContentLoadingTick', array(&$path, &$v, &$config));

            if (isset($config['dourl']) and is_array($config['dourl'])) {
                $config['curent'] = true;
                $out = count($GLOBALS['Engine']['GET']) - $k;
                $v = $config['dourl'][(((count($config['dourl']) < $out) ? count($config['dourl']) : $out) - 1)];
                $path .= '/' . $v;
            }

            if (isset($config['curent_header']) and is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/header.php'))
                $GLOBALS['Engine']['Template']['Content'] .= file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/header.php');

            if ($k + 1 == count($GLOBALS['Engine']['GET']) || isset($config['curent'])) {
                ScriptManager::CallHook('OnContentLoaded', array(&$path, &$v, &$config));
                if (is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/' . $v . '.php')) {
                    if (isset($config['curent_header']) == false and is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/header.php'))
                        $GLOBALS['Engine']['Template']['Content'] .= file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/header.php');
                    $GLOBALS['Engine']['Template']['Content'] .= file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/' . $v . '.php');
                    if (is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/footer.php'))
                        $GLOBALS['Engine']['Template']['Content'] .= file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/footer.php');
                } else {
                    $GLOBALS['Engine']['Template']['Content'] = '';
                    ScriptManager::CallHook('OnContentLoaded_NoContent', array($path, $v, $config));
                }
                ScriptManager::CallHook('OnContentLoadedFinish', array(&$path, &$v, &$config));
                break;
            }
        }
        if ($GLOBALS['Engine']['Template']['Content'] != '') {
            ob_start();
            eval(' ?>' . $GLOBALS['Engine']['Template']['Content'] . '<?php ');
            $GLOBALS['Engine']['Template']['Content'] = ob_get_clean();
        }
        ScriptManager::CallHook('OnInitializationContentFinish');
    }

    public static function InitializationShutdown() {
        ScriptManager::CallHook('OnInitializationShutdown');
        die();
    }
    
    private static function InitializationPreCheck() {
        if (isset($_GET['route']) and $_GET['route'] == '/favicon.ico')
        {
            header("HTTP/1.1 404 Not Found");
            die();
        }
    }

    private static function InitializationRoute() {
        ApplicationManager::Using('System.Uri');
        ApplicationManager::Using('Data.DefaultSettings');
        $result = Uri::GetRouteFrom(isset($_GET['route']) ? $_GET['route'] : null);
        $GLOBALS['Engine']['Route'] = $result[0];
        $GLOBALS['Engine']['GET'] = $result[1];

        $GLOBALS['Engine']['System'] = ucfirst($GLOBALS['Engine']['GET'][0]);
        if (!is_dir('./Public/System/' . $GLOBALS['Engine']['System']))
            $GLOBALS['Engine']['System'] = DefaultSettings::$DefaultSystemName;
    }

    private static function InitializationMysql() {
        ApplicationManager::Using('Data.DatabaseSettings');
        ApplicationManager::Using('Library.Database.SafeMySQL');
        $GLOBALS['Mysql'] = new SafeMySQL(array(
            'host' => DatabaseSettings::$Host,
            'user' => DatabaseSettings::$User,
            'pass' => DatabaseSettings::$Pass,
            'db' => DatabaseSettings::$Base,
            'port' => DatabaseSettings::$Port,
            'charset' => DatabaseSettings::$Charset,
        ));
    }

    public static function Using($namespace_name) {
        $namespace_name = str_replace('.', '/', $namespace_name);
        if (isset($GLOBALS['Engine']['Using'][$namespace_name]) == false) {
            if (is_file('./Engine/' . $namespace_name . '.php')) {
                include_once './Engine/' . $namespace_name . '.php';
                $GLOBALS['Engine']['Using'][$namespace_name] = true;
            } else
                echo '[ApplicationManager::Using(' . $namespace_name . ')]: Not have using class!';
        }
    }

}

?>