<?php
function plugin_whitelabel_install() {
    global $DB;

    $migration = new Migration(100);

    if (!$DB->tableExists("itsm_plugin_whitelabel_brand")) {
        $query = "CREATE TABLE `itsm_plugin_whitelabel_brand` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `favicon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `logo_central` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `brand_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
            `brand_color_secondary` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        
        $DB->queryOrDie($query, $DB->error());
        $DB->queryOrDie("INSERT INTO `itsm_plugin_whitelabel_brand` 
                        (`id`, `favicon`, `logo_central`, `brand_color`,   `brand_color_secondary`) 
                 VALUES (1,    '',         '',            '#7b081d',       '#ae0c2a')",
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

    $migration->executeMigration();
    return true;
}

function plugin_whitelabel_uninstall() {
    global $DB;

    $tablename = 'itsm_plugin_whitelabel_brand';
    if($DB->tableExists($tablename)) {
        $DB->queryOrDie(
            "DROP TABLE `$tablename`",
            $DB->error()
        );
    }

    $tablename = 'itsm_plugin_whitelabel_profiles';
    if($DB->tableExists($tablename)) {
        $DB->queryOrDie(
            "DROP TABLE `$tablename`",
            $DB->error()
        );
    }

    foreach (PluginWhitelabelProfile::getRightsGeneral() as $right) {
        $query = "DELETE FROM `glpi_profilerights`
                  WHERE `name` = '".$right['field']."'";
        $DB->query($query);

        if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
           unset($_SESSION['glpiactiveprofile'][$right['field']]);
        }
   }

    return true;
}