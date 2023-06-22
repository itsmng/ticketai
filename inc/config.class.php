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
use ScssPhp\ScssPhp\Compiler;

class PluginWhitelabelConfig extends CommonDBTM {
    /**
     * Displays the configuration page for the plugin
     * 
     * @return void
     */
    public function showConfigForm() {
        global $DB;

        if (!Session::haveRight("plugin_whitelabel_whitelabel",UPDATE)) {
            return false;
        }

        echo "<form enctype='multipart/form-data' action='./config.form.php' method='post'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr><th colspan='2'>".__("Whitelabel Settings", 'whitelabel')."</th></tr>";
        
        $colors = $this->getThemeColors();
        //define all the form's fields
        $fields=array("primary_color"=>array("TYPE"=>"color","LBL"=>"Primary color"),
                        "ligne".rand()=>array("TYPE"=>"hr","LBL"=>"<hr>"),
                        "header_icons_color"=>array("TYPE"=>"color","LBL"=>"Header icons color"),
                        "ligne".rand()=>array("TYPE"=>"hr","LBL"=>"<hr>"),
                        "menu_color"=>array("TYPE"=>"color","LBL"=>"Menu color"),
                        "menu_text_color"=>array("TYPE"=>"color","LBL"=>"Menu text color"),
                        "menu_active_color"=>array("TYPE"=>"color","LBL"=>"Active menu color"),
                        "menu_onhover_color"=>array("TYPE"=>"color","LBL"=>"On hover menu color"),
                        "dropdown_menu_background_color"=>array("TYPE"=>"color","LBL"=>"Dropdown menu background color"),
                        "dropdown_menu_text_color"=>array("TYPE"=>"color","LBL"=>"Dropdown menu text color"),
                        "dropdown_menu_text_hover_color"=>array("TYPE"=>"color","LBL"=>"Dropdown menu text hover color"),
                        "ligne".rand()=>array("TYPE"=>"hr","LBL"=>"<hr>"),
                        "alert_background_color"=>array("TYPE"=>"color","LBL"=>"Alert background color"),
                        "alert_text_color"=>array("TYPE"=>"color","LBL"=>"Alert text color"),
                        "alert_header_background_color"=>array("TYPE"=>"color","LBL"=>"Alert header background color"),
                        "alert_header_text_color"=>array("TYPE"=>"color","LBL"=>"Alert header text color"),
                        "ligne".rand()=>array("TYPE"=>"hr","LBL"=>"<hr>"),
                        "table_header_background_color"=>array("TYPE"=>"color","LBL"=>"Table header background color"),
                        "table_header_text_color"=>array("TYPE"=>"color","LBL"=>"Table header text color"),
                        "ligne".rand()=>array("TYPE"=>"hr","LBL"=>"<hr>"),
                        "object_name_color"=>array("TYPE"=>"color","LBL"=>"Object name color"),
                        "ligne".rand()=>array("TYPE"=>"hr","LBL"=>"<hr>"),
                        "button_color"=>array("TYPE"=>"color","LBL"=>"Button color"),
                        "secondary_button_background_color"=>array("TYPE"=>"color","LBL"=>"Secondary button background color"),
                        "secondary_button_text_color"=>array("TYPE"=>"color","LBL"=>"Secondary button text color"),
                        "secondary_button_box_shadow_color"=>array("TYPE"=>"color","LBL"=>"Secondary button box-shadow color"),
                        "submit_button_background_color"=>array("TYPE"=>"color","LBL"=>"Submit button background color"),
                        "submit_button_text_color"=>array("TYPE"=>"color","LBL"=>"Submit button text color"),
                        "submit_button_box_shadow_color"=>array("TYPE"=>"color","LBL"=>"Submit button box-shadow color"),
                        "vsubmit_button_background_color"=>array("TYPE"=>"color","LBL"=>"Vsubmit button background color"),
                        "vsubmit_button_text_color"=>array("TYPE"=>"color","LBL"=>"Vsubmit button text color"),
                        "vsubmit_button_box_shadow_color"=>array("TYPE"=>"color","LBL"=>"Vsubmit button box-shadow color"),
                        "ligne".rand()=>array("TYPE"=>"hr","LBL"=>"<hr>"),
                        "favicon"=>array("TYPE"=>"file","LBL"=>sprintf(__('Favicon (%s)', 'whitelabel'), Document::getMaxUploadSize())),
                        "logo_central"=>array("TYPE"=>"file","LBL"=>sprintf(__('Logo (%s)', 'whitelabel'), Document::getMaxUploadSize())),
                        "ligne".rand()=>array("TYPE"=>"hr","LBL"=>"<hr>"),
                        "css_configuration"=>array("TYPE"=>"file","LBL"=>sprintf(__('Import your CSS configuration (%s)', 'whitelabel'), Document::getMaxUploadSize()))
                        );
        //works on the fields
        foreach ($fields as $k=>$v){
            //translate the lbl
            if ($v['TYPE'] == "color")
                $fields_update[$k]=array("LBL"=>__($v['LBL'], 'whitelabel'),"VALUE"=>$colors[$k],"TYPE"=>$v["TYPE"]);
            //show <hr>
            elseif ($v['TYPE'] == "hr")
                $fields_update[$k]=array("LBL"=>$v['LBL'],"VALUE"=>"","TYPE"=>$v["TYPE"]);
            //file fields
            elseif ($v['TYPE'] == "file"){
                $sql_whitelabel_band = new table_glpi_plugin_whitelabel_brand();
                $value = $sql_whitelabel_band->select($k);
                if ($value[$k] == "")
                    $fields_update[$k]=array("LBL"=>$v['LBL'],"VALUE"=>"","TYPE"=>$v["TYPE"]);
                else
                    $fields_update[$k]=array("LBL"=>$v['LBL'],
                    "VALUE"=>Plugin::getWebDir("plugin_whitelabel_whitelabel")."/plugins/whitelabel/uploads/".$value[$k],
                    "TYPE"=>"IMG","LBL_A"=>__('Clear'),"ALT"=>$value[$k]);
            }
        }

        //show fields
        foreach ($fields_update as $k=>$v){
            $this->startField($v['LBL']);
            if ($v['TYPE'] == "color")
                Html::showColorField($k, ["value" => $v['VALUE']]);
            elseif ($v['TYPE'] == "hr")
                echo "<hr>";
            elseif ($v['TYPE'] == "file")
                $this->showImageUploadField($k);
            elseif ($v['TYPE'] == "IMG")
                $this->showImageUploadField($k,$v);
            $this->endField();
        }

        echo "<tr class='tab_bg_1'><td class='center' colspan='2'>";
        echo "<input type='submit' name='update' class='submit'>&nbsp;&nbsp;<input type='submit' name='reset' class='submit' value='".__('Restore colors', 'whitelabel')."'>";
        echo "</td></tr>";
        echo "</table>";
        Html::closeForm();
    }

    /**
     * Displays image upload field
     *
     * @param string Field name and $values when image exist
     *
     * @return void
     */
    private function showImageUploadField(string $fieldName, array $values=array()) {
        if ($values != array()) {           
            echo Html::image($values["VALUE"], [
                'style' => 'max-width: 100px; max-height: 50px;',
                'class' => 'picture_square'
            ]);
            echo "&nbsp;&nbsp;";
            echo "<input type='checkbox' name='_blank_$fieldName' value='No'/>";
            echo "&nbsp;".$values["LBL_A"];
        } else {
            echo "<input name='$fieldName' type='file' />";
        }
    }
    
    /**
     * Get the primary theme color
     *
     * @return string
     */
    private function getThemeColors() {
         //use class to select on table 
         $config = new table_glpi_plugin_whitelabel_brand();
         $row=$config->select();
         //if result
         if (count($row) > 0) {
              foreach ($row as $k=>$v){
                 //if the field is a color
                 if (substr($v,0,1) == '#')
                     $colors[$k]=$v;
              }
         }else{//no color value on table use default values
             $default_value_css = new plugin_whitelabel_const();
             $colors = $default_value_css->all_value();
         }
         return $colors;
    }

    /**
     * Open HTML field wrapper
     * 
     * @param string $label Field label
     * 
     * @return void
     */
    private function startField(string $label) {
        echo "<tr class='tab_bg_1'>";
        echo "<th style='width:40%'>";
        echo $label;
        echo "</th>";
        echo "<td colspan='3'>";
    }

    /**
     * Close HTML field wrapper
     * 
     * @return void
     */
    private function endField() {
        echo "</td>";
        echo "</tr>";
    }

    public function handleWhitelabel($reset = false) {
        global $DB;

        // Update theme colors
        if($_POST["primary_color"]) {
            $color = (!$reset) ? $_POST["primary_color"] : '#7b081d';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `primary_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["header_icons_color"]) {
            $color = (!$reset) ? $_POST["header_icons_color"] : '#ffffff';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `header_icons_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["menu_color"]) {
            $color = (!$reset) ? $_POST["menu_color"] : '#ae0c2a';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `menu_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["menu_text_color"]) {
            $color = (!$reset) ? $_POST["menu_text_color"] : '#ffffff';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `menu_text_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["menu_active_color"]) {
            $color = (!$reset) ? $_POST["menu_active_color"] : '#c70c2f';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `menu_active_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["menu_onhover_color"]) {
            $color = (!$reset) ? $_POST["menu_onhover_color"] : '#d40e33';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `menu_onhover_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["dropdown_menu_background_color"]) {
            $color = (!$reset) ? $_POST["dropdown_menu_background_color"] : '#ffffff';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `dropdown_menu_background_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["dropdown_menu_text_color"]) {
            $color = (!$reset) ? $_POST["dropdown_menu_text_color"] : '#131425';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `dropdown_menu_text_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["dropdown_menu_text_hover_color"]) {
            $color = (!$reset) ? $_POST["dropdown_menu_text_hover_color"] : '#131425';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `dropdown_menu_text_hover_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["alert_background_color"]) {
            $color = (!$reset) ? $_POST["alert_background_color"] : '#dfdfdf';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `alert_background_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["alert_text_color"]) {
            $color = (!$reset) ? $_POST["alert_text_color"] : '#333333';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `alert_text_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["alert_header_background_color"]) {
            $color = (!$reset) ? $_POST["alert_header_background_color"] : '#a9a9a9';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `alert_header_background_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["alert_header_text_color"]) {
            $color = (!$reset) ? $_POST["alert_header_text_color"] : '#131425';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `alert_header_text_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["table_header_background_color"]) {
            $color = (!$reset) ? $_POST["table_header_background_color"] : '#f8f8f8';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `table_header_background_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["table_header_text_color"]) {
            $color = (!$reset) ? $_POST["table_header_text_color"] : '#ae0c2a';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `table_header_text_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["object_name_color"]) {
            $color = (!$reset) ? $_POST["object_name_color"] : '#ae0c2a';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `object_name_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["button_color"]) {
            $color = (!$reset) ? $_POST["button_color"] : '#f5b7b1';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `button_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["secondary_button_background_color"]) {
            $color = (!$reset) ? $_POST["secondary_button_background_color"] : '#e6e6e6';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `secondary_button_background_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["secondary_button_text_color"]) {
            $color = (!$reset) ? $_POST["secondary_button_text_color"] : '#5f5f5f';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `secondary_button_text_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["secondary_button_box_shadow_color"]) {
            $color = (!$reset) ? $_POST["secondary_button_box_shadow_color"] : '#999999';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `secondary_button_box_shadow_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["submit_button_background_color"]) {
            $color = (!$reset) ? $_POST["submit_button_background_color"] : '#f5b7b1';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `submit_button_background_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["submit_button_text_color"]) {
            $color = (!$reset) ? $_POST["submit_button_text_color"] : '#8f5a0a';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `submit_button_text_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["submit_button_box_shadow_color"]) {
            $color = (!$reset) ? $_POST["submit_button_box_shadow_color"] : '#999999';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `submit_button_box_shadow_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["vsubmit_button_background_color"]) {
            $color = (!$reset) ? $_POST["vsubmit_button_background_color"] : '#f5b7b1';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `vsubmit_button_background_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["vsubmit_button_text_color"]) {
            $color = (!$reset) ? $_POST["vsubmit_button_text_color"] : '#8f5a0a';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `vsubmit_button_text_color` = '$color' WHERE `id` = 1", $DB->error());
        }

        if($_POST["vsubmit_button_box_shadow_color"]) {
            $color = (!$reset) ? $_POST["vsubmit_button_box_shadow_color"] : '#999999';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `vsubmit_button_box_shadow_color` = '$color' WHERE `id` = 1", $DB->error());
        }
        
        $this->handleFile("favicon", array("image/x-icon", "image/vnd.microsoft.icon"));
        $this->handleFile("logo_central", array("image/png"));
        $this->handleFile("css_configuration", array("text/css"));

        if ($this->handleClear("favicon")) {
            copy(Plugin::getPhpDir("whitelabel")."/bak/favicon.ico.bak", GLPI_ROOT."/pics/favicon.ico");
        }

        $this->handleClear("logo_central");
        $this->handleClear("css_configuration");

        if(file_exists(Plugin::getPhpDir("whitelabel")."/uploads/favicon.ico")) {
            copy(Plugin::getPhpDir("whitelabel")."/uploads/favicon.ico", GLPI_ROOT."/pics/favicon.ico");
        } 
    }

    /**
     * Generate and install new CSS sheets w/ styles mapped
     */
    public function refreshCss($reset = false) {
        global $DB;

        $row = $DB->queryOrDie("SELECT * FROM `glpi_plugin_whitelabel_brand` WHERE `id` = 1", $DB->error())->fetch_assoc();

        $primaryColor = (!$reset) ? $row["primary_color"] : '#7b081d';
        $headerIconsColor = (!$reset) ? $row["header_icons_color"] : '#ffffff';
        $menuColor = (!$reset) ? $row["menu_color"] : '#ae0c2a';
        $menuTextColor = (!$reset) ? $row["menu_text_color"] : '#ffffff';
        $menuActiveColor = (!$reset) ? $row["menu_active_color"] : '#c70c2f';
        $menuOnHoverColor = (!$reset) ? $row["menu_onhover_color"] : '#d40e33';
        $dropdownMenuBackgroundColor = (!$reset) ? $row["dropdown_menu_background_color"] : '#ffffff';
        $dropdownMenuTextColor = (!$reset) ? $row["dropdown_menu_text_color"] : '#131425';
        $dropdownMenuTextHoverColor = (!$reset) ? $row["dropdown_menu_text_hover_color"] : '#ffffff';
        $alertBackgroundColor = (!$reset) ? $row["alert_background_color"] : '#dfdfdf';
        $alertTextColor = (!$reset) ? $row["alert_text_color"] : '#333333';
        $alertHeaderBackgroundColor = (!$reset) ? $row["alert_header_background_color"] : '#a9a9a9';
        $alertHeaderTextColor = (!$reset) ? $row["alert_header_text_color"] : '#ffffff';
        $tableHeaderBackgroundColor = (!$reset) ? $row["table_header_background_color"] : '#f8f8f8';
        $tableHeaderTextColor = (!$reset) ? $row["table_header_text_color"] : '#ae0c2a';
        $objectNameColor = (!$reset) ? $row["object_name_color"] : '#ae0c2a';
        $buttonColor = (!$reset) ? $row["button_color"] : '#f5b7b1';
        $secondaryButtonBackgroundColor = (!$reset) ? $row["secondary_button_background_color"] : '#e6e6e6';
        $secondaryButtonTextColor = (!$reset) ? $row["secondary_button_text_color"] : '#5f5f5f';
        $secondaryButtonBoxShadowColor = (!$reset) ? $row["secondary_button_box_shadow_color"] : '#999999';
        $submitButtonBackgroundColor = (!$reset) ? $row["submit_button_background_color"] : '#f5b7b1';
        $submitButtonTextColor = (!$reset) ? $row["submit_button_text_color"] : '#8f5a0a';
        $submitButtonBoxShadowColor = (!$reset) ? $row["submit_button_box_shadow_color"] : '#999999';
        $vsubmitButtonBackgroundColor = (!$reset) ? $row["vsubmit_button_background_color"] : '#f5b7b1';
        $vsubmitButtonTextColor = (!$reset) ? $row["vsubmit_button_text_color"] : '#8f5a0a';
        $vsubmitButtonBoxShadowColor = (!$reset) ? $row["vsubmit_button_box_shadow_color"] : '#999999';

        list($logoW, $logoH) = getimagesize(GLPI_ROOT."/pics/fd_logo.png");
        copy(GLPI_ROOT."/pics/fd_logo.png", GLPI_ROOT."/pics/login_logo_whitelabel.png");
        $logo = "../../../pics/login_logo_whitelabel.png";

        if(!empty($row["logo_central"])) {
            list($logoW, $logoH) = getimagesize(Plugin::getPhpDir("whitelabel", true)."/uploads/logo_central.png");
            copy(Plugin::getPhpDir("whitelabel")."/uploads/".$row["logo_central"], GLPI_ROOT."/pics/login_logo_whitelabel.png");
        }

        $map = [
            "%primary_color%" => $primaryColor,
            "%header_icons_color%" => $headerIconsColor,
            "%menu_color%" => $menuColor,
            "%menu_text_color%" => $menuTextColor,
            "%menu_active_color%" => $menuActiveColor,
            "%menu_onhover_color%" => $menuOnHoverColor,
            "%dropdown_menu_background_color%" => $dropdownMenuBackgroundColor,
            "%dropdown_menu_text_color%" => $dropdownMenuTextColor,
            "%dropdown_menu_text_hover_color%" => $dropdownMenuTextHoverColor,
            "%alert_background_color%" => $alertBackgroundColor,
            "%alert_text_color%" => $alertTextColor,
            "%alert_header_background_color%" => $alertHeaderBackgroundColor,
            "%alert_header_text_color%" => $alertHeaderTextColor,
            "%table_header_background_color%" => $tableHeaderBackgroundColor,
            "%table_header_text_color%" => $tableHeaderTextColor,
            "%object_name_color%" => $objectNameColor,
            "%button_color%" => $buttonColor,
            "%secondary_button_background_color%" => $secondaryButtonBackgroundColor,
            "%secondary_button_text_color%" => $secondaryButtonTextColor,
            "%secondary_button_box_shadow_color%" => $secondaryButtonBoxShadowColor,
            "%submit_button_background_color%" => $submitButtonBackgroundColor,
            "%submit_button_text_color%" => $submitButtonTextColor,
            "%submit_button_box_shadow_color%" => $submitButtonBoxShadowColor,
            "%vsubmit_button_background_color%" => $vsubmitButtonBackgroundColor,
            "%vsubmit_button_text_color%" => $vsubmitButtonTextColor,
            "%vsubmit_button_box_shadow_color%" => $vsubmitButtonBoxShadowColor,
            "%logo%" => $logo,
            "%logo_width%" => ceil(55 * ($logoW / $logoH))
        ];

        $template = file_get_contents(Plugin::getPhpDir("whitelabel")."/styles/template.scss");
        $login_template = file_get_contents(Plugin::getPhpDir("whitelabel")."/styles/login_template.scss");

        // Interpolate SCSS
        $style = strtr($template, $map);
        $login_style = strtr($login_template, $map);

        // Compile SCSS to pure CSS
        $scssCompiler = new Compiler();
        $css = $scssCompiler->compile($style);
        $loginCss = $scssCompiler->compile($login_style);

        if(file_exists(Plugin::getPhpDir("whitelabel", true)."/uploads/whitelabel.css")) {
            unlink(Plugin::getPhpDir("whitelabel", true)."/uploads/whitelabel.css");
        }

        if(file_exists(GLPI_ROOT."/css/whitelabel_login.css")) {
            unlink(GLPI_ROOT."/css/whitelabel_login.css");
        }

        // Place compiled CSS
        file_put_contents(Plugin::getPhpDir("whitelabel", true)."/uploads/whitelabel.css", $css);
        file_put_contents(GLPI_ROOT."/css/whitelabel_login.css", $loginCss);

        // Ensure permissions
        chmod(Plugin::getPhpDir("whitelabel", true)."/uploads/whitelabel.css", 0664);
        chmod(GLPI_ROOT."/css/whitelabel_login.css", 0664);
    }

    /**
     * Handles file upload actions
     */
    private function handleFile(string $file, array $formats) {
        global $DB;

        if(empty($_FILES[$file])) {
            return;
        }

        // Get error code from file upload action
        switch ($_FILES[$file]["error"]) {
            case UPLOAD_ERR_OK:
                if (!in_array($_FILES[$file]["type"], $formats)) {
                    echo "Only images of mime types: ".implode($formats)." are supported for $file files!";
                    exit();
                }
                $this->createDirectoryIfNotExist(Plugin::getPhpDir("whitelabel", true)."/uploads/");
                $ext = pathinfo($_FILES[$file]["name"], PATHINFO_EXTENSION);
                $uploadfile = Plugin::getPhpDir("whitelabel", true)."/uploads/".$file.".".$ext;

                if(file_exists($uploadfile)) {
                    unlink($uploadfile);
                }

                if (move_uploaded_file($_FILES[$file]["tmp_name"], $uploadfile)) {
                    $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET $file = '$file.".$ext."' WHERE id = 1", $DB->error());
                    chmod($uploadfile, 0664);
                }
                break;
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded $file file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded $file file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded $file file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No $file file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write $file file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
    }

    /**
     * Creates a directory in the specified path, returns false if it fails
     *
     * @param string $path The path to the folder to create
     * @return bool
     */
    private function createDirectoryIfNotExist(string $path) {
        if (!file_exists($path)) {
           mkdir($path, 0664);
        } elseif (!is_dir($path)) {
            return false;
        }

        return true;
    }

    private function handleClear(string $field) {
        global $DB;

        if (isset($_POST["_blank_".$field])) {
            $row = $DB->queryOrDie("SELECT * FROM `glpi_plugin_whitelabel_brand` WHERE `id` = 1", $DB->error())->fetch_assoc();
            unlink(Plugin::getPhpDir("whitelabel")."/uploads/".$row[$field]);
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET $field = '' WHERE `id` = 1", $DB->error());
            return true;
        }

        return false;
    }
}
