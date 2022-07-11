<?php
function plugin_whitelabel_install() {
    global $DB;

    $migration = new Migration(100);

    if (!$DB->tableExists("itsm_plugin_whitelabel_brand")) {
        $query = "CREATE TABLE `itsm_plugin_whitelabel_brand` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `favicon_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
            `brand_color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
            `brand_color_secondary` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        
        $DB->queryOrDie($query, $DB->error());
        $DB->queryOrDie("INSERT INTO `itsm_plugin_whitelabel_brand` 
                        (`id`, `favicon_file`, `brand_color`,   `brand_color_secondary`) 
                 VALUES (1,    '',             '#7b081d',       '#f39c12')",
        $DB->error());
    }

    $migration->executeMigration();
    return true;
}

function plugin_whitelabel_uninstall() {
    global $DB;

    $tablename = 'itsm_plugin_whitelabel_brand';
    //Create table only if it doesn't exist yet
    if($DB->tableExists($tablename)) {
        $DB->queryOrDie(
            "DROP TABLE `$tablename`",
            $DB->error()
        );
    }

    return true;
}