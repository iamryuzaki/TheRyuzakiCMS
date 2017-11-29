<?php

if ($_SERVER['SCRIPT_NAME'] != '/index.php')
    die('This page has protected!');

class ApplicationManager {

    public static function Initialization() {
        self::InitializationRoute();
        self::InitializationMysql();
    }

    public static function InitializationTemplate() {
        $GLOBALS['Engine']['Template']['Template'] = '';
        $GLOBALS['Engine']['Template']['Content'] = '';
        
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
        $path = '';
        foreach ($GLOBALS['Engine']['GET'] as $k => $v) {
            $path .= '/' . $v;
            if ($k + 1 == count($GLOBALS['Engine']['GET'])) {
                if (is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/' . $v . '.php')) {
                    if (is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/header.php'))
                        $GLOBALS['Engine']['Template']['Content'] .= file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/header.php');
                    $GLOBALS['Engine']['Template']['Content'] .= file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/' . $v . '.php');
                    if (is_file('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/footer.php'))
                        $GLOBALS['Engine']['Template']['Content'] .= file_get_contents('./Public/System/' . $GLOBALS['Engine']['System'] . '/Content' . $path . '/footer.php');
                } else {
                    $GLOBALS['Engine']['Template']['Content'] = '';
                    ScriptManager::CallHook('OnContentLoading_NoContent', array($path, $v));
                }
                ScriptManager::CallHook('OnContentLoadingFinish', array($path, $v));
            }
        }
        if ($GLOBALS['Engine']['Template']['Content'] != '') {
            ob_start();
            eval(' ?>' . $GLOBALS['Engine']['Template']['Content'] . '<?php ');
            $GLOBALS['Engine']['Template']['Content'] = ob_get_clean();
        }
        ScriptManager::CallHook('OnInitializationContentFinish');
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