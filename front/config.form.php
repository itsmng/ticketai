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
    if(isset($_POST["action"])) {
        Session::checkRight("config", UPDATE);
        global $DB;
        $DB->request('UPDATE glpi_plugin_ticketai_config 
        SET api_key = "'.$_POST["api_key"].
        '", user_activated = "'.($_POST["user_activated"] ?? 0).
        '", tech_activated = "'.($_POST["tech_activated"] ?? 0).
        '", user_prompt = "'.$_POST["user_prompt"].
        '", tech_prompt_followup = "'.$_POST["tech_prompt_followup"].
        '", tech_prompt_close = "'.$_POST["tech_prompt_close"].
        '"WHERE id = 1');
    }

    Html::header("Ticket AI", $_SERVER["PHP_SELF"], "config", "plugins");
    $config->showConfigForm();
} else {
    Html::header("settings", '', "config", "plugins");
    echo "<div class='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
    echo "<b>Please enable the plugin before configuring it</b></div>";
    Html::footer();
}

Html::footer();
