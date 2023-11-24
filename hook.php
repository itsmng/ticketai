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

    //get default values for fields 
    if (!$DB->tableExists("glpi_plugin_ticketai_config")) {        
        $query = "CREATE TABLE glpi_plugin_ticketai_config (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_activated tinyint(1) COLLATE utf8_unicode_ci NOT NULL,
            tech_activated tinyint(1) COLLATE utf8_unicode_ci NOT NULL,
            connection_type varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            endpoint varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            user_model varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            tech_model varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            api_key varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            user_prompt LONGTEXT COLLATE utf8_unicode_ci NOT NULL,
            tech_prompt LONGTEXT COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY  (`id`))
            ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
            $DB->queryOrDie($query, $DB->error());

            $default_prompt = addslashes(PluginTicketaiConfig::DEFAULT_USER_PROMPT);
            $default_tech_prompt = addslashes(PluginTicketaiConfig::DEFAULT_TECH_PROMPT);
            $query = "INSERT INTO `glpi_plugin_ticketai_config`
                (
                    `id`,
                    `user_activated`,
                    `tech_activated`,
                    `connection_type`,
                    `endpoint`,
                    `user_model`,
                    `tech_model`,
                    `api_key`,
                    `user_prompt`,
                    `tech_prompt`
                ) VALUES (
                    1,
                    1,
                    1,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '{$default_prompt}',
                    '{$default_tech_prompt}'
                )";
                echo $query;
            $DB->queryOrDie($query, $DB->error());
    }

    if (!$DB->tableExists("glpi_plugin_ticketai_profiles")) {
        $query2 = "CREATE TABLE `glpi_plugin_ticketai_profiles` (
        `id` int(11) NOT NULL default '0',
        `right` char(1) collate utf8_unicode_ci default NULL,
        PRIMARY KEY  (`id`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    
        $DB->queryOrDie($query2, $DB->error());
    
        include_once(GLPI_ROOT . "/plugins/ticketai/inc/profile.class.php");
        PluginTicketaiProfile::createAdminAccess($_SESSION['glpiactiveprofile']['id']);
    
        foreach (PluginTicketaiProfile::getRightsGeneral() as $right) {
            PluginTicketaiProfile::addDefaultProfileInfos($_SESSION['glpiactiveprofile']['id'], [$right['field'] => $right['default']]);
        }
    } else $DB->queryOrDie("ALTER TABLE `glpi_plugin_ticketai_profiles` ENGINE = InnoDB", $DB->error());
    return true;
}

function plugin_ticketai_uninstall() {
    global $DB;

    // Drop tables
    if($DB->tableExists('glpi_plugin_ticketai_config')) {
        $DB->queryOrDie("DROP TABLE `glpi_plugin_ticketai_config`",$DB->error());
    }
    if($DB->tableExists('glpi_plugin_ticketai_profiles')) {
        $DB->queryOrDie("DROP TABLE `glpi_plugin_ticketai_profiles`",$DB->error());
    }
    foreach (PluginTicketaiProfile::getRightsGeneral() as $right) {
        $query = "DELETE FROM `glpi_profilerights` WHERE `name` = '" . $right['field'] . "'";
        $DB->query($query);
    
        if (isset($_SESSION['glpiactiveprofile'][$right['field']])) unset($_SESSION['glpiactiveprofile'][$right['field']]);
    }


    return true;
}