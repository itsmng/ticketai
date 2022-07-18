<?php

function plugin_whitelabel_install() {
    global $DB;

    $migration = new Migration(101);

    if (!$DB->tableExists("itsm_plugin_whitelabel_brand")) {
        $query = "CREATE TABLE `itsm_plugin_whitelabel_brand` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `favicon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `logo_central` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `brand_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        
        $DB->queryOrDie($query, $DB->error());
        $DB->queryOrDie("INSERT INTO `itsm_plugin_whitelabel_brand` 
                        (`id`, `favicon`, `logo_central`, `brand_color`) 
                 VALUES (1,    '',         '',            '#7b081d')",
        $DB->error());
    }

    if (!$DB->tableExists("itsm_plugin_whitelabel_profiles")) {

        $query2 = "CREATE TABLE `itsm_plugin_whitelabel_profiles` (
                    `id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
                    `right` char(1) collate utf8_unicode_ci default NULL,
                    PRIMARY KEY  (`id`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        $DB->queryOrDie($query2, $DB->error());

        include_once(GLPI_ROOT."/plugins/whitelabel/inc/profile.class.php");
        PluginWhitelabelProfile::createAdminAccess($_SESSION['glpiactiveprofile']['id']);

        foreach (PluginWhitelabelProfile::getRightsGeneral() as $right) {
            PluginWhitelabelProfile::addDefaultProfileInfos($_SESSION['glpiactiveprofile']['id'],
                [$right['field'] => $right['default']]);
        }
    }

    // Create backup of resources that will be altered
    if (!file_exists(Plugin::getPhpDir("whitelabel") . "/bak/index.php.bak")) {
        copy(GLPI_ROOT . "/index.php", Plugin::getPhpDir("whitelabel") . "/bak/index.php.bak");
        copy(GLPI_ROOT . "/pics/favicon.ico", Plugin::getPhpDir("whitelabel") . "/bak/favicon.ico.bak");
        chown(Plugin::getPhpDir("whitelabel") . "/bak/index.php.bak", 0664);
        chown(Plugin::getPhpDir("whitelabel") . "/bak/favicon.ico.bak", 0664);
    }

    $loginPage = file_get_contents(GLPI_ROOT."/index.php");
    // Patch login page (only patched on install, we update the styles through the linked CSS)
    $patchMap = [
        "echo Html::css('public/lib/base.css');" => "echo Html::css('public/lib/base.css');\n\techo \"<link rel='stylesheet' type='text/css' href='/css/whitelabel_login.css' media='all'>\";\n",
        "login_logo_itsm.png" => "login_logo_whitelabel.png"
    ];

    $patchedLogin = strtr($loginPage, $patchMap);

    file_put_contents(GLPI_ROOT . "/index.php", $patchedLogin);

    $migration->executeMigration();
    return true;
}

function plugin_whitelabel_uninstall() {
    global $DB;

    // Drop tables
    if($DB->tableExists('itsm_plugin_whitelabel_brand')) {
        $DB->queryOrDie(
            "DROP TABLE `itsm_plugin_whitelabel_brand`",
            $DB->error()
        );
    }

    if($DB->tableExists('itsm_plugin_whitelabel_profiles')) {
        $DB->queryOrDie(
            "DROP TABLE `itsm_plugin_whitelabel_profiles`",
            $DB->error()
        );
    }

    // Clear profiles
    foreach (PluginWhitelabelProfile::getRightsGeneral() as $right) {
        $query = "DELETE FROM `glpi_profilerights`
                  WHERE `name` = '".$right['field']."'";
        $DB->query($query);

        if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
           unset($_SESSION['glpiactiveprofile'][$right['field']]);
        }
   }

    // Clear uploads
    $files = glob(Plugin::getPhpDir("whitelabel") . "/uploads/*"); // Get all file names in `uploads`
    foreach($files as $file){ // Iterate files
        if(is_file($file)) {
            unlink($file); // Delete file
        }
    }

    // Clear patches
    if (is_file(Plugin::getPhpDir("whitelabel") . "/bak/index.php.bak")) {
        copy(Plugin::getPhpDir("whitelabel") . "/bak/index.php.bak", GLPI_ROOT . "/index.php");
        copy(Plugin::getPhpDir("whitelabel") . "/bak/favicon.ico.bak", GLPI_ROOT . "/pics/favicon.ico");
    }

    // Clear bakups
    $files = glob(Plugin::getPhpDir("whitelabel") . "/bak/*");
    foreach($files as $file){
        if(is_file($file)) {
            unlink($file);
        }
    }
    return true;
}
