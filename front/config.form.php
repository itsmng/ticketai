<?php
/**
 * ---------------------------------------------------------------------
 * ITSM-NG
 * Copyright (C) 2022 ITSM-NG and contributors.
 *
 * https://www.itsm-ng.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of ITSM-NG.
 *
 * ITSM-NG is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * ITSM-NG is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ITSM-NG. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */
include("../../../inc/includes.php");
require_once("../inc/config.class.php");

$plugin = new Plugin();

if($plugin->isActivated("whitelabel")) {
    $config = new PluginWhitelabelConfig();

    if(isset($_POST["update"])) {
        Session::checkRight("config", UPDATE);
        $config->handleWhitelabel();
        $config->refreshCss();
        Session::addMessageAfterRedirect(__('<p><b>Settings applied !</b></p><p><i>If you have any error, do the command in the root installation folder : <b>bin/console system:clear_cache</b></i></p>'));
    }

    if(isset($_POST["reset"])) {
        Session::checkRight("config", UPDATE);
        $config->handleWhitelabel(true);
        $config->refreshCss(true);
        Session::addMessageAfterRedirect(__('<p><b>Default settings applied !</b></p><p><i>If you have any error, do the command in the root installation folder : <b>bin/console system:clear_cache</b></i></p>'));
    }

    Html::header("White Label", $_SERVER["PHP_SELF"], "config", "plugins");
    $config->showConfigForm();
} else {
    Html::header("settings", '', "config", "plugins");
    echo "<div class='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
    echo "<b>Please enable the plugin before configuring it</b></div>";
    Html::footer();
}

Html::footer();
