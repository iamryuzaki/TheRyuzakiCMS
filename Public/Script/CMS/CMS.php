<?php

class CMS {
    public static function OnContentLoadingFailed($path, $name) {
        $GLOBALS['Engine']['Template']['Content'] = 'Error #404';
    }
}