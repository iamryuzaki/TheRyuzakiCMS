<?php
class CMS {
    public static $Account = null;
    public static $Session = null;
    
    public static function OnLoaded() {
        include_once __DIR__.'/CMS_Session.php';
        include_once __DIR__.'/CMS_Account.php';
        self::$Session = new CMS_Session();
        self::$Account = new CMS_Account();
    }
    
    public static function OnContentLoaded_NoContent($path, $name, $config) {
        $GLOBALS['Engine']['Template']['Content'] = 'Error #404';
    }
    
    public static function OnInitializationShutdown() {
        self::$Session->OnSessionEnd();
    }
}

?>