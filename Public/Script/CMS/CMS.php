<?php
class CMS {
    public static function OnContentLoadingFailed($path, $name, $config) {
        $GLOBALS['Engine']['Template']['Content'] = 'Error #404';
    }
}

?>