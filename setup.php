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

function plugin_init_ticketai() {
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['ticketai'] = true;
    $PLUGIN_HOOKS['change_profile']['ticketai'] = array('PluginWhitelabelProfile', 'changeProfile');

    Plugin::registerClass('PluginTickeAiProfile', array('addtabon' => array('Profile')));

    if (Session::haveRight("profile", UPDATE)) {
        $PLUGIN_HOOKS['config_page']['ticketai'] = 'front/config.form.php';
    }

    $PLUGIN_HOOKS['menu_toadd']['ticketai'] = array('helpdesk' => 'PluginTicketAiChatbot');
}

function plugin_version_ticketai() {
    return array(
        'name'           => "Ticketing AI",
        'version'        => '0.1.0',
        'author'         => 'ITSM Dev Team, AntoineLemarchand',
        'license'        => 'GPLv3+',
        'homepage'       => 'https://github.com/AntoineLemarchand/ticketai',
        'requirements'   => [
            'glpi'   => [
               'min' => '9.5'
            ],
            'php'    => [
                'min' => '8.0'
            ]
        ]
    );
}

function plugin_ticketai_check_prerequisites() {
    if (version_compare(ITSM_VERSION, '2.0', 'lt')) {
        echo "This plugin requires ITSM >= 2.0";
        return false;
    }
    return true;
}

function plugin_ticketai_check_config() {
    return true;
}
