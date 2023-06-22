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

/**
 * 
 * New class for action on table_glpi_plugin_whitelabel_band 
 * 
 */

class table_glpi_plugin_whitelabel_brand
{
    /**
     * get all column and info on this table
     * return array
     */
    function desc(){
        global $DB;
        $query="DESC glpi_plugin_whitelabel_brand";
        $result = $DB->query($query);
        if ($DB->numrows($result) > 0) {
             while ($row=$result->fetch_assoc()){
                foreach ($row as $k=>$v){
                    $fields[$k][$v]=$v;
                }
            }
        }
        return $fields;
    }
    /**
     * Update fields on table
     */
    function update($data,$id=1){
        global $DB;
        if (is_array($data)){
            $query = "UPDATE glpi_plugin_whitelabel_brand SET ";
            foreach ($data as $k=>$v){
                $query .= $k." = '".$v."',";
            }
            $query = substr($query,0,-1);
            $query.= " WHERE id = '".$id."'";
            $DB->queryOrDie($query, $DB->error());
        }
    }

    /**
     * delete line
     */
    function delete($id=1){
        global $DB;
        $query = "DELETE FROM glpi_plugin_whitelabel_brand WHERE id='".$id."'";
        $DB->queryOrDie($query, $DB->error());
    }

    /**
     * insert data (array)
     */
    function insert($data){
        global $DB;
        $fields=self::desc();
        foreach ($data as $k=>$v){
            if (isset($fields['Field'][$k])){
               $fields2update[] = $k;
               $values2update[] = "'".$v."'";
            }
        }
        // Insert first entry with default itsmng colors
        $query = "INSERT INTO glpi_plugin_whitelabel_brand (".implode(',',$fields2update).")     
            VALUES (".implode(',',$values2update).")";
         $DB->queryOrDie($query, $DB->error());
    }
    /**
     * select
     */
    function select($key='*',$id=1){
        global $DB;
        $query="SELECT ".(is_array($key)?implode(',',$key):$key)." FROM glpi_plugin_whitelabel_brand WHERE id = '".$id."'";
        $row = $DB->queryOrDie($query, $DB->error())->fetch_assoc();
        return $row;
    }


}

/**
 * 
 * define class for default value
 * 
 * 
 */
class plugin_whitelabel_const
{
    /**
     * Define default values
     */
    const COLORS_DEFAULT = [
        'primary_color' => '#7b081d',
        'header_icons_color' => "#ffffff",
        'menu_color' => '#ae0c2a',
        'menu_text_color' => '#ffffff',
        'menu_active_color' => '#c70c2f',
        'menu_onhover_color' => '#d40e33',
        'dropdown_menu_background_color' => '#ffffff',
        'dropdown_menu_text_color' => '#131425',
        'dropdown_menu_text_hover_color' => '#ffffff',
        'alert_background_color' => '#dfdfdf',
        'alert_text_color' => '#333333',
        'alert_header_background_color' => '#a9a9a9',
        'alert_header_text_color' => '#ffffff',
        'table_header_background_color' => "#f8f8f8",
        'table_header_text_color' => "#ae0c2a",
        'object_name_color' => "#ae0c2a",
        'button_color' => '#f5b7b1',
        'secondary_button_background_color' => "#e6e6e6",
        'secondary_button_text_color' => "#5f5f5f",
        'secondary_button_box_shadow_color' => "#8f5a0a",
        'submit_button_background_color' => "#f5b7b1",
        'submit_button_text_color' => "#8f5a0a",
        'submit_button_box_shadow_color' => "#8f5a0a",
        'vsubmit_button_background_color' => "#f5b7b1",
        'vsubmit_button_text_color' => "#8f5a0a",
        'vsubmit_button_box_shadow_color' => "#8f5a0a"
    ];
    
    function showConstant() {
        print_r(self::COLORS_DEFAULT);
      }
    
    function value_key($key){
        if (isset(COLORS_DEFAULT[$key]))
            return self::COLORS_DEFAULT[$key];
        else
            return false;
    }
    function all_value(){
        return self::COLORS_DEFAULT;
    }
    function all_value_split(){
        foreach (self::COLORS_DEFAULT as $k=>$v){
            $data[]=$k;
        }
        return $data;
    }
    /**
     * insert default values on database
     */
    function insert_default_config(){
        $insert = new table_glpi_plugin_whitelabel_brand();
        $default_values=self::COLORS_DEFAULT;
        $default_values['id']=1;
        $default_values['favicon']='';
        $default_values['logo_central']='';
        $default_values['css_configuration']='';
        // Insert first entry with default itsmng colors
        $insert -> insert($default_values);
    }


}






function plugin_init_whitelabel() {
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['whitelabel'] = true;
    $PLUGIN_HOOKS['change_profile']['whitelabel'] = array('PluginWhitelabelProfile', 'changeProfile');

    Plugin::registerClass('PluginWhitelabelProfile', array('addtabon' => array('Profile')));

    if (Session::haveRight("profile", UPDATE)) {
        $PLUGIN_HOOKS['config_page']['whitelabel'] = 'front/config.form.php';
    }

    $PLUGIN_HOOKS['add_css']['whitelabel'] = [
        "uploads/whitelabel.css",
        "uploads/css_configuration.css",
    ];
}

function plugin_version_whitelabel() {
    return array(
        'name'           => "White Label",
        'version'        => '2.2.0',
        'author'         => 'ITSM Dev Team, Théodore Clément',
        'license'        => 'GPLv3+',
        'homepage'       => 'https://github.com/itsmng/whitelabel',
        'minGlpiVersion' => '9.5'
    );
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
