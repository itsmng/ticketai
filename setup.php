<?php

function plugin_init_whitelabel() {
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['whitelabel'] = true;
    $PLUGIN_HOOKS['change_profile']['whitelabel'] = array('PluginWhitelabelProfile', 'changeProfile');

    Plugin::registerClass('PluginWhitelabelProfile', array('addtabon' => array('Profile')));

    if (Session::haveRight("profile", UPDATE) && Session::haveRight("plugin_whitelabel_whitelabel", UPDATE)) {
        $PLUGIN_HOOKS['config_page']['whitelabel'] = 'front/config.form.php';
    }

    $PLUGIN_HOOKS['add_css']['whitelabel'] = "whitelabel.css";
}

function plugin_version_whitelabel() {
    return array('name'           => "White Label",
                 'version'        => '1.0',
                 'author'         => 'Théodore Clément',
                 'license'        => 'GPLv3+',
                 'homepage'       => 'https://github.com/Soulusions/whitelabel',
                 'minGlpiVersion' => '9.5');
}

function plugin_whitelabel_check_prerequisites() {
    if (version_compare(ITSM_VERSION, '1.0', 'lt')) {
        echo "This plugin requires ITSM >= 1.0";
        return false;
    }

    return true;
}

function plugin_whitelabel_check_config() {
    return true;
}