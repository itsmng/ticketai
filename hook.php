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


function plugin_ticketai_install() {
    global $DB;

    $migration = new Migration(101);
    //get default values for fields 
    if (!$DB->tableExists("glpi_plugin_ticketai_config")) {        
        $query = "CREATE TABLE glpi_plugin_ticketai_config (
            id int(11) NOT NULL AUTO_INCREMENT,
            api_key varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            user_activated tinyint(1) COLLATE utf8_unicode_ci NOT NULL,
            user_prompt varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
            tech_activated tinyint(1) COLLATE utf8_unicode_ci NOT NULL,
            tech_prompt_followup varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
            tech_prompt_close varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY  (`id`))
            ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
            $DB->queryOrDie($query, $DB->error());

            $query = 'INSERT INTO `glpi_plugin_ticketai_config`
            (`id`, `api_key`, `user_activated`, `user_prompt`, `tech_activated`, `tech_prompt_followup`, `tech_prompt_close`)
            VALUES (1, "", 0,"'.PluginTicketaiConfig::DEFAULT_USER_PROMPT
                .'", 0, "'.PluginTicketaiConfig::DEFAULT_FOLLOWUP_PROMPT.'", "'.PluginTicketaiConfig::DEFAULT_CLOSE_PROMPT.'")
            ';
            $DB->queryOrDie($query, $DB->error());
    }
    return true;
}

function plugin_ticketai_uninstall() {
    global $DB;

    // Drop tables
    if($DB->tableExists('glpi_plugin_ticketai_config')) {
        $DB->queryOrDie("DROP TABLE `glpi_plugin_ticketai_config`",$DB->error());
    }


    return true;
}


function ticketai_timeline_actions($options) {
    $item = $options['item'];
    $rand = $options['rand'];
    if (!$item->canDelete()) {
        return;
    }
    
    $label_followup = __('Followup'). ' ' . __('AI');
    $label_close = __('Solution'). ' ' . __('AI');
    $ticketId = $item->fields['id'];
    $plugin_url = Plugin::getWebDir('ticketai').'/ajax/chatbots.php?ticket_id='.$ticketId;
    echo <<<HTML
        <button class="btn btn-sm btn-primary" onclick='viewChatbot("{$ticketId}", "{$rand}", "{$plugin_url}&context=followup")'>{$label_followup}</button>
        <button class="btn btn-sm btn-secondary" onclick='viewChatbot("{$ticketId}", "{$rand}", "{$plugin_url}&context=solution")'>{$label_close}</button>
    HTML;
}