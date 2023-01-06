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

function plugin_whitelabel_install() {
    global $DB;

    $migration = new Migration(101);

    if (!$DB->tableExists("glpi_plugin_whitelabel_brand")) {
        $query = "CREATE TABLE `glpi_plugin_whitelabel_brand` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `favicon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `logo_central` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `css_configuration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `primary_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#7b081d',
            `header_icons_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ffffff',
            `menu_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ae0c2a',
            `menu_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ffffff',
            `menu_active_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#c70c2f',
            `menu_onhover_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#d40e33',
            `dropdown_menu_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ffffff',
            `dropdown_menu_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#131425',
            `dropdown_menu_text_hover_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ffffff',
            `alert_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#dfdfdf',
            `alert_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#333333',
            `alert_header_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#a9a9a9',
            `alert_header_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ffffff',
            `table_header_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#f8f8f8',
            `table_header_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ae0c2a',
            `object_name_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ae0c2a',
            `button_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#f5b7b1',
            `secondary_button_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#e6e6e6',
            `secondary_button_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#5f5f5f',
            `secondary_button_box_shadow_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#999999',
            `submit_button_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#f5b7b1',
            `submit_button_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#8f5a0a',
            `submit_button_box_shadow_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#999999',
            `vsubmit_button_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#f5b7b1',
            `vsubmit_button_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#8f5a0a',
            `vsubmit_button_box_shadow_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#999999',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        
        $DB->queryOrDie($query, $DB->error());

        // Insert first entry with default itsmng colors
        $query = "INSERT INTO `glpi_plugin_whitelabel_brand`
                  (
                    `id`,
                    `favicon`,
                    `logo_central`,
                    `css_configuration`,
                    `primary_color`,
                    `header_icons_color`,
                    `menu_color`,
                    `menu_text_color`,
                    `menu_active_color`,
                    `menu_onhover_color`,
                    `dropdown_menu_background_color`,
                    `dropdown_menu_text_color`,
                    `dropdown_menu_text_hover_color`,
                    `alert_background_color`,
                    `alert_text_color`,
                    `alert_header_background_color`,
                    `alert_header_text_color`,
                    `table_header_background_color`,
                    `table_header_text_color`,
                    `object_name_color`,
                    `button_color`,
                    `secondary_button_background_color`,
                    `secondary_button_text_color`,
                    `secondary_button_box_shadow_color`,
                    `submit_button_background_color`,
                    `submit_button_text_color`,
                    `submit_button_box_shadow_color`,
                    `vsubmit_button_background_color`,
                    `vsubmit_button_text_color`,
                    `vsubmit_button_box_shadow_color`
                  ) 
                  VALUES
                  (
                    1,         #id
                    '',        #favicon
                    '',        #logo_central
                    '',        #css_configuration
                    '#7b081d', #primary_color
                    '#ffffff', #header_icons_color
                    '#ae0c2a', #menu_color
                    '#ffffff', #menu_text_color
                    '#c70c2f', #menu_active_color
                    '#d40e33', #menu_onhover_color
                    '#ffffff', #dropdown_menu_background_color
                    '#131425', #dropdown_menu_text_color
                    '#ffffff', #dropdown_menu_text_hover_color
                    '#dfdfdf', #alert_background_color
                    '#333333', #alert_text_color
                    '#a9a9a9', #alert_header_background_color
                    '#ffffff', #alert_header_text_color
                    '#f8f8f8', #table_header_background_color
                    '#ae0c2a', #table_header_text_color
                    '#ae0c2a', #object_name_color
                    '#f5b7b1', #button_color
                    '#e6e6e6', #secondary_button_background_color
                    '#5f5f5f', #secondary_button_text_color
                    '#999999', #secondary_button_box_shadow_color
                    '#f5b7b1', #submit_button_background_color
                    '#8f5a0a', #submit_button_text_color
                    '#999999', #submit_button_box_shadow_color
                    '#f5b7b1', #vsubmit_button_background_color
                    '#8f5a0a', #vsubmit_button_text_color
                    '#999999' #vsubmit_button_box_shadow_color
                  )";
        $DB->queryOrDie($query, $DB->error());
    }

    if (!$DB->tableExists("glpi_plugin_whitelabel_profiles")) {
        $query = "CREATE TABLE `glpi_plugin_whitelabel_profiles` (
            `id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
            `right` char(1) collate utf8_unicode_ci default NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        $DB->queryOrDie($query, $DB->error());

        include_once(GLPI_ROOT."/plugins/whitelabel/inc/profile.class.php");
        PluginWhitelabelProfile::createAdminAccess($_SESSION['glpiactiveprofile']['id']);

        foreach (PluginWhitelabelProfile::getRightsGeneral() as $right) {
            PluginWhitelabelProfile::addDefaultProfileInfos($_SESSION['glpiactiveprofile']['id'],[$right['field'] => $right['default']]);
        }
    }

    // Create backup of resources that will be altered
    if (!file_exists(Plugin::getPhpDir("whitelabel")."/bak/index.php.bak")) {
        copy(GLPI_ROOT."/index.php", Plugin::getPhpDir("whitelabel")."/bak/index.php.bak");
        copy(GLPI_ROOT."/pics/favicon.ico", Plugin::getPhpDir("whitelabel")."/bak/favicon.ico.bak");
    }

    $loginPage = file_get_contents(GLPI_ROOT."/index.php");
    // Patch login page (only patched on install, we update the styles through the linked CSS)
    $patchMap = [
        "echo Html::css('public/lib/base.css');" => "echo Html::css('public/lib/base.css');\n\techo \"<link rel='stylesheet' type='text/css' href='css/whitelabel_login.css' media='all'>\";\n",
        "login_logo_itsm.png" => "login_logo_whitelabel.png"
    ];

    $patchedLogin = strtr($loginPage, $patchMap);

    file_put_contents(GLPI_ROOT."/index.php", $patchedLogin);

    // Update 2.0
    if($DB->tableExists("glpi_plugin_whitelabel_brand")) {
        // Rename brand_color column to primary_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'primary_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` CHANGE COLUMN `brand_color` `primary_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#7b081d'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column header_icons_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'header_icons_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `header_icons_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ffffff'";
        $DB->queryOrDie($query, $DB->error());
        }

        // Add column menu_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'menu_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `menu_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ae0c2a'";
        $DB->queryOrDie($query, $DB->error());
        }

        // Add column menu_text_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'menu_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `menu_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ffffff'";
        $DB->queryOrDie($query, $DB->error());
        }
        
        // Add column menu_active_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'menu_active_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `menu_active_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#c70c2f'";
            $DB->queryOrDie($query, $DB->error());
        }
        
        // Add column menu_onhover_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'menu_onhover_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `menu_onhover_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#d40e33'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column dropdown_menu_background_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'dropdown_menu_background_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `dropdown_menu_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ffffff'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column dropdown_menu_text_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'dropdown_menu_text_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `dropdown_menu_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#131425'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column dropdown_menu_text_hover_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'dropdown_menu_text_hover_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `dropdown_menu_text_hover_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#131425'";
            $DB->queryOrDie($query, $DB->error());
        }
        
        // Add column alert_background_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'alert_background_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `alert_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#dfdfdf'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column alert_text_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'alert_text_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `alert_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#333333'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column alert_header_background_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'alert_header_background_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `alert_header_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#a9a9a9'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column alert_header_text_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'alert_header_text_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `alert_header_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#f8f8f8'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column table_header_text_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'table_header_text_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `table_header_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ae0c2a'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column object_name_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'object_name_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `object_name_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#ae0c2a'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column button_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'button_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `button_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#f5b7b1'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column secondary_button_background_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'secondary_button_background_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `secondary_button_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#e6e6e6'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column secondary_button_text_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'secondary_button_text_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `secondary_button_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#5f5f5f'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column secondary_button_box_shadow_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'secondary_button_box_shadow_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `secondary_button_box_shadow_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#999999'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column submit_button_background_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'submit_button_background_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `submit_button_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#f5b7b1'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column submit_button_text_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'submit_button_text_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `submit_button_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#8f5a0a'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column submit_button_box_shadow_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'submit_button_box_shadow_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `submit_button_box_shadow_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#999999'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column vsubmit_button_background_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'vsubmit_button_background_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `vsubmit_button_background_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#f5b7b1'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column vsubmit_button_text_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'vsubmit_button_text_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `vsubmit_button_text_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#8f5a0a'";
            $DB->queryOrDie($query, $DB->error());
        }

        // Add column vsubmit_button_box_shadow_color
        if(!$DB->fieldExists('glpi_plugin_whitelabel_brand', 'vsubmit_button_box_shadow_color')) {
            $query = "ALTER TABLE `glpi_plugin_whitelabel_brand` ADD COLUMN `vsubmit_button_box_shadow_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '#999999'";
            $DB->queryOrDie($query, $DB->error());
        }
    }

    $migration->executeMigration();
    return true;
}

function plugin_whitelabel_uninstall() {
    global $DB;

    // Drop tables
    if($DB->tableExists('glpi_plugin_whitelabel_brand')) {
        $DB->queryOrDie("DROP TABLE `glpi_plugin_whitelabel_brand`",$DB->error());
    }

    if($DB->tableExists('glpi_plugin_whitelabel_profiles')) {
        $DB->queryOrDie("DROP TABLE `glpi_plugin_whitelabel_profiles`",$DB->error());
    }

    // Clear profiles
    foreach (PluginWhitelabelProfile::getRightsGeneral() as $right) {
        $query = "DELETE FROM `glpi_profilerights` WHERE `name` = '".$right['field']."'";
        $DB->query($query);

        if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
           unset($_SESSION['glpiactiveprofile'][$right['field']]);
        }
   }

    // Clear uploads
    $files = glob(Plugin::getPhpDir("whitelabel")."/uploads/*"); // Get all file names in `uploads`

    foreach($files as $file){ // Iterate files
        if(is_file($file)) unlink($file); // Delete file
    }

    // Clear patches
    if (is_file(Plugin::getPhpDir("whitelabel")."/bak/index.php.bak")) {
        copy(Plugin::getPhpDir("whitelabel")."/bak/index.php.bak", GLPI_ROOT."/index.php");
        copy(Plugin::getPhpDir("whitelabel")."/bak/favicon.ico.bak", GLPI_ROOT."/pics/favicon.ico");
    }

    // Clear bakups
    $files = glob(Plugin::getPhpDir("whitelabel")."/bak/*");

    foreach($files as $file){
        if(is_file($file)) unlink($file);
    }

    return true;
}
