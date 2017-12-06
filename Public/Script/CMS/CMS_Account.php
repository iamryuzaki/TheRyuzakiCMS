<?php

class CMS_Account {
    
    public $Access = array();
    public $Data = array();
    
    public function __get($name) {
        return isset($this->Data[$name]) ? $this->Data[$name] : null;
    }

    public function __construct() {
        $this->RunAuth();
    }

    public function Login($id) {
        $this->Logout();
        $GLOBALS['Mysql']->query('UPDATE `accounts` SET `session`=?s WHERE `id`=?s', $_COOKIE['SID'], $id);
        $GLOBALS['Mysql']->query('INSERT INTO `accounts_information_login` (`id_account`,`useragent`,`time`,`ip`) VALUES (?s,?s,NOW(),?s)', $id, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR']);
        $this->RunAuth();
    }
    
    public function Registration($login, $email, $password, $phone = '', $active = true, $group = 1, $autoauth = false, $parent = 0) {
        $GLOBALS['Mysql']->query('INSERT INTO `accounts` (`login`,`password`,`email`,`phone`,`group`,`session`,`active`) VALUES (?s,?s,?s,?s,?s,?s,?i)', $login, $this->GetPasswordDatabaseFormat($password), $email, $phone, $group, ($autoauth == true ? $_COOKIE['SID'] : ''), ($active ? 1 : 0));
        $uid = $GLOBALS['Mysql']->getOne('SELECT LAST_INSERT_ID()');
        $GLOBALS['Mysql']->query('INSERT INTO `accounts_information_registration` (`id_account`,`id_parent`,`login`,`email`,`useragent`,`time`,`ip`) VALUES (?s,?s,?s,?s,?s,NOW(),?s)', $uid, $parent, $login, $email, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR']);
        if ($autoauth == true)
            $this->RunAuth();
    }
    
    public function Logout() {
        if ($this->IsAuth()) {
            $GLOBALS['Mysql']->query('UPDATE `accounts` SET `session`=?s WHERE `id`=?s', '', $this->Data['id']);
            $this->Data = array();
            $this->Access = array();
        }
    }

    public function GetValidationAccount($loginOrEmail, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $Res = $GLOBALS['Mysql']->getRow('SELECT `id` FROM `accounts` WHERE `login`=?s AND `password`=?s', $loginOrEmail, $this->GetPasswordDatabaseFormat($password));
        else
            $Res = $GLOBALS['Mysql']->getRow('SELECT `id` FROM `accounts` WHERE `email`=?s AND `password`=?s', $loginOrEmail, $this->GetPasswordDatabaseFormat($password));
        return isset($Res['id']);
    }
    
    public function GetPasswordDatabaseFormat($password) {
        return md5(sha1($password. substr($password, strlen($password) / 2)));
    }
    
    public function IsHaveLogin($login) {
        $Res = $GLOBALS['Mysql']->getRow('SELECT `id` FROM `accounts` WHERE `login`=?s', $login);
        return isset($Res['id']);
    }
    
    public function IsHaveEmail($email) {
        $Res = $GLOBALS['Mysql']->getRow('SELECT `id` FROM `accounts` WHERE `email`=?s', $email);
        return isset($Res['id']);
    }

    public function IsAuth() {
        return isset($this->Data['id']);
    }
    
    private function RunAuth() {
        $this->Data = $GLOBALS['Mysql']->getRow('SELECT * FROM `accounts` WHERE `session`=?s', $_COOKIE['SID']);
        if ($this->IsAuth()) {
            $Res = $GLOBALS['Mysql']->getAll('SELECT `key`,`value` FROM `groups_access` WHERE `id_group`=?s', $this->Data['group']);
            foreach ($Res as $r)
                $this->Access[$r['key']] = $r['value'];
        } 
    }

}

?>