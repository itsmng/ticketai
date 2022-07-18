<?php

include("../../../inc/includes.php");
require_once("../inc/config.class.php");

$plugin = new Plugin();
if ($plugin->isActivated("whitelabel")) {
    $config = new PluginWhitelabelConfig();

    if (isset($_POST["update"])) {
        Session::checkRight("config", UPDATE);
        $config->handleWhitelabel();
        $config->refreshCss();
    }

    Html::header("White Label", $_SERVER["PHP_SELF"], "config", "plugins");
    $config->showConfigForm();
} else {
    Html::header("settings", '', "config", "plugins");
    echo "<div class='center'><br><br>".
         "<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
    echo "<b>Please enable the plugin before configuring it</b></div>";
    Html::footer();
}

Html::footer();
