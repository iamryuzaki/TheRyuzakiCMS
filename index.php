<?php
#########################################################
# @Name: TheRyuzaki CMS
# @Author: TheRyuzaki
# @Thanks: 
# @Repository: https://github.com/iamryuzaki/TheRyuzakiCMS
# @Contact: lod.skot@gmail.com
#########################################################
require_once './Engine/Environment/ApplicationManager.php';
ApplicationManager::Initialization();
ApplicationManager::Using('Environment.ScriptManager');
ScriptManager::Initialization();
ApplicationManager::InitializationTemplate();
echo $GLOBALS['Engine']['Template']['Template'];
ApplicationManager::InitializationShutdown();
?>