<?php

include ('../../../inc/includes.php');

Session::haveRight('plugin_whitelabel_whitelabel', UPDATE);

$prof = new PluginWhitelabelProfile();

if (isset($_POST['update'])) {
    $prof->update($_POST);
    Html::back();
}
