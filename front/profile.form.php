<?php

include ('../../../inc/includes.php');

// Gestion des données du formulaire

Session::haveRight('plugin_whitelabel_whitelabel', UPDATE);

$prof = new PluginWhitelabelProfile();

if (isset($_POST['update'])) {
    $prof->update($_POST);
    Html::back();
}