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

if($plugin->isActivated("ticketai")) {
    $config = new PluginTicketaiConfig();
    if(isset($_POST["api_key"]) || isset($_POST["prompt"])) {
        Session::checkRight("config", UPDATE);
        global $DB;
        $DB->request('UPDATE glpi_plugin_ticketai_config SET api_key = "'.$_POST["api_key"].'", prompt = "'.$_POST["prompt"].'" WHERE id = 1');
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
