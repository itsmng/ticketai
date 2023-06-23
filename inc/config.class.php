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
                        "favicon"=>array("TYPE"=>"file","LBL"=>sprintf(__('Favicon (%s)', 'whitelabel'), Document::getMaxUploadSize()),"ACCEPT"=>".ico"),
                        "logo_central"=>array("TYPE"=>"file","LBL"=>sprintf(__('Logo (%s)', 'whitelabel'), Document::getMaxUploadSize()),"ACCEPT"=>".png"),
                        "ligne".rand()=>array("TYPE"=>"hr","LBL"=>"<hr>"),
                        "css_configuration"=>array("TYPE"=>"file","LBL"=>sprintf(__('Import your CSS configuration (%s)', 'whitelabel'), Document::getMaxUploadSize()),"ACCEPT"=>".css")
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
                    $fields_update[$k]=$v;
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
            elseif ($v['TYPE'] == "file"){
                echo "<input name='$k' type='file' accept='".$v['ACCEPT']."' />";
            }elseif ($v['TYPE'] == "IMG"){
                echo Html::image($v["VALUE"], [
                    'style' => 'max-width: 100px; max-height: 50px;',
                    'class' => 'picture_square'
                ]);
                echo "&nbsp;&nbsp;";
                echo "<input type='checkbox' name='_blank_$k' value='No'/>";
                echo "&nbsp;".$v["LBL_A"];
            }
            $this->endField();
        }

        echo "<tr class='tab_bg_1'><td class='center' colspan='2'>";
        echo "<input type='submit' name='update' class='submit'>&nbsp;&nbsp;<input type='submit' name='reset' class='submit' value='".__('Restore colors', 'whitelabel')."'>";
        echo "</td></tr>";
        echo "</table>";
        Html::closeForm();
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
           //use class to colors values
           $default_value_css = new plugin_whitelabel_const();
           //use class to use table
           $sql = new table_glpi_plugin_whitelabel_brand();
           //if reset
           if ($reset){
               //delete values
               $sql -> delete();
               //insert default values
               $default_value_css -> insert_default_config();
           }else{
               //get all fields of color
               $fields = $default_value_css -> all_value();
               foreach($fields as $k=>$v){
                   //if post value exist
                   if (isset($_POST[$k])){
                       //put it on array
                       $data[$k]=$_POST[$k];
                   }
               }
               //update on database color fields values
               if (isset($data))
                   $sql -> update($data);
           }
        $message="";
        $files_to_upload = array("favicon" => array("image/x-icon", "image/vnd.microsoft.icon"),
                                 "logo_central" => array("image/png"),
                                 "css_configuration" => array("text/css"));
        foreach ($files_to_upload as $k=>$v)
            $message .= $this->handleFile($k, $v);
        
        if ($message != ""){
            Session::addMessageAfterRedirect("<font color=red><b>".$message."</b></font>", 'whitelabel');
        }

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

        $default_value_css = new plugin_whitelabel_const();        
        $css_default_values=$default_value_css -> all_value();
        $all_fields_color = $default_value_css -> all_value_split();
        //we need logo_central central field pour testing is exist or not
        $all_fields_color[] = "logo_central";
        $sql = new table_glpi_plugin_whitelabel_brand();
        if ($reset)
            $row=$css_default_values;
        else
            $row=$sql->select($all_fields_color);        

        $logo = "../../../pics/login_logo_whitelabel.png";

        if(isset($row["logo_central"]) && $row["logo_central"] != "") {
            copy(Plugin::getPhpDir("whitelabel")."/uploads/".$row["logo_central"], GLPI_ROOT."/pics/login_logo_whitelabel.png");
        }else{
            copy(GLPI_ROOT."/pics/fd_logo.png", GLPI_ROOT."/pics/login_logo_whitelabel.png");
        }
        list($logoW, $logoH) = getimagesize(GLPI_ROOT."/pics/login_logo_whitelabel.png");

        foreach ($row as $k=>$v){
            $map["%".$k."%"] = $v;
        }
        $map["%logo%"] = $logo;
        $map["%logo_width%"] = ceil(55 * ($logoW / $logoH));
        //tab <address to put css>=><scss modele>
        $style_css=array(Plugin::getPhpDir("whitelabel", true)."/uploads/whitelabel.css"=>'template.scss',
        GLPI_ROOT."/css/whitelabel_login.css"=>'login_template.scss');

        // Class Compile SCSS
        $scssCompiler = new Compiler();
        foreach ($style_css as $k=>$v){
            //if a old css file exist => unlink
            if(file_exists($k))
                unlink($k);
            //scss
            $template = file_get_contents(Plugin::getPhpDir("whitelabel")."/styles/".$v);
            // Interpolate SCSS
            $style = strtr($template, $map);
            // Compile SCSS to pure CSS
            $css = $scssCompiler->compile($style);
            //put new css on the right rep
            file_put_contents($k, $css);
            //change chmod
            chmod($k, 0664);
        }
    }

    /**
     * Handles file upload actions
     */
    private function handleFile(string $file, array $formats) {

        if(empty($_FILES[$file])) {
            return;
        }

        // Get error code from file upload action
        switch ($_FILES[$file]["error"]) {
            case UPLOAD_ERR_OK:
                if (!in_array($_FILES[$file]["type"], $formats)) {
                    return "Only images of mime types: ".implode($formats)." are supported for $file files!";
                    exit();
                }
                $this->createDirectoryIfNotExist(Plugin::getPhpDir("whitelabel", true)."/uploads/");
                $ext = pathinfo($_FILES[$file]["name"], PATHINFO_EXTENSION);
                $uploadfile = Plugin::getPhpDir("whitelabel", true)."/uploads/".$file.".".$ext;

                if(file_exists($uploadfile)) {
                    unlink($uploadfile);
                }

                if (move_uploaded_file($_FILES[$file]["tmp_name"], $uploadfile)) {
                    $sql = new table_glpi_plugin_whitelabel_brand();
                    $sql-> update(array($file => $file.".".$ext));   
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
                //$message = "No $file file was uploaded";
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
        if (isset($message))
            return $message;
        return false;
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
        //if checkbox selected to delete file
        if (isset($_POST["_blank_".$field])) {
            $sql = new table_glpi_plugin_whitelabel_brand();
            //check this file exist
            $row=$sql-> select($field);   
            if (isset($row[$field])){
                //unlink file
                unlink(Plugin::getPhpDir("whitelabel")."/uploads/".$row[$field]);
                //update table
                $sql-> update(array($field=>''));  
                return true; 
            }            
        }
        return false;
    }
}
