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
        require_once GLPI_ROOT . "/ng/twig.class.php";        
        $template_dir[] = GLPI_ROOT . "/templates";
        $template_dir[] = Plugin::getPhpDir("whitelabel")."/templates";
        $twig = Twig::load($template_dir, false);      
        $colors = $this->getThemeColors();
        $field_labels = [
            'primary_color' => __('Primary Color'),
            'secondary_color' => __('Secondary Color'),
            'primary_text_color' => __('Primary Text Color'),
            'secondary_text_color' => __('Secondary Text Color'),
            'header_background_color' => __('Header Background Color'),
            'header_text_color' => __('Header Text Color'),
            'nav_background_color' => __('Nav Background Color'),
            'nav_text_color' => __('Nav Text Color'),
            'nav_submenu_color' => __('Nav Submenu Color'),
            'nav_hover_color' => __('Nav Hover Color'),
            'favorite_color' => __('Favorite Color'),
        ];
        //define all the form's fields
        foreach ($colors as $name=>$color){
            $fields[$name]=["TYPE" => "color","LBL" => $field_labels[$name], "VALUE" => $color];
        }
        $fields += [
            "favicon"=>[
                "TYPE" => "file",
                "LBL"=>sprintf(__('Favicon (%s)', 'whitelabel'), Document::getMaxUploadSize()),
                "ACCEPT" => ".ico"
            ],
            "logo_central"=>[
                "TYPE" => "file",
                "LBL"=>sprintf(__('Logo (%s)', 'whitelabel'), Document::getMaxUploadSize()),
                "ACCEPT" => ".png"
            ],
            "css_configuration"=>[
                "TYPE" => "file",
                "LBL"=>sprintf(__('Import your CSS configuration (%s)', 'whitelabel'), Document::getMaxUploadSize()),
                "ACCEPT" => ".css"]
        ];
        foreach ($fields as $k=>$v){
            //translate the lbl
            if ($v['TYPE'] == "color")
            $fields_update[$k]=array(["VALUE"=>$k,"TYPE"=>"id"],["VALUE"=>__($v['LBL'], 'whitelabel'),"TYPE"=>"lbl"],["VALUE"=>$colors[$k],"TYPE"=>$v["TYPE"],"NAME"=>$k]);
            //file fields
            elseif ($v['TYPE'] == "file"){
                $sql_whitelabel_band = new table_glpi_plugin_whitelabel_brand();
                $value = $sql_whitelabel_band->select($k);
                if ($value[$k] == "" && isset($colors[$k]))
                $fields_update[$k]=array(["VALUE"=>$k,"TYPE"=>"id"],["VALUE"=>$v['LBL'],"TYPE"=>"lbl"],["VALUE"=>$colors[$k],"TYPE"=>$v["TYPE"],"NAME"=>$k,"VALUE_ACCEPT"=>$v['ACCEPT']]);
                else
                $fields_update[$k]=array(["VALUE"=>$k,"TYPE"=>"id"],["VALUE"=>$v['LBL'],"TYPE"=>'lbl'],["VALUE"=>Plugin::getWebDir("plugin_whitelabel_whitelabel")."/plugins/whitelabel/uploads/".$value[$k],"TYPE"=>"img"]);
            }
            // $fields_update[$k][]=array("TYPE"=>'checkbox',"NAME"=>'checkbox_'.$k,"VALUE"=>$k);
        }
        //print_r( $fields_update);
        try {
            echo $twig->render('config.class.twig',  ['fields_update' => $fields_update,'csrf' => Session::getNewCSRFToken()]);   
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

     /**
     * Get the primary theme color
     *
     * @return array
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

    public function handleWhitelabel($reset = false) {
        //use class to colors values
        $default_value_css = new plugin_whitelabel_const();
        //use class to use table
        $sql = new table_glpi_plugin_whitelabel_brand();

        if ($reset) {
            if ($_POST['selected_rows'] != "") {
                $toReplace= explode(',',$_POST['selected_rows']);
                foreach ($toReplace as $v){
                    $value = $default_value_css->value_key($v);
                    if ($value != false)
                        $data[$v]=$value;
                }
                if (isset($data)) {
                    $sql -> update($data);
                }
            }
        } else {
            $fields = $default_value_css->all_value();
            foreach($fields as $key => $val){
                //if post value exist
                if (isset($_POST[$key])){
                    //put it on array
                    $data[$key] = $_POST[$key];
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
        $css_default_values=$default_value_css->all_value();
        $all_fields_color = $default_value_css->all_value_split();
        //we need logo_central central field pour testing is exist or not
        $all_fields_color[] = "logo_central";
        $sql = new table_glpi_plugin_whitelabel_brand();
        if ($reset) {
            $row=$css_default_values;
        } else {
            $row=$sql->select($all_fields_color);        
        }

        foreach ($row as $k=>$v){
            $map["%".$k."%"] = $v;
        }
        //tab <address to put css>=><scss modele>
        $style_css= [
            GLPI_ROOT."/css/custom.scss"=>'template.scss',
            //GLPI_ROOT."/css/whitelabel_login.css"=>'login_template.scss'
        ];

        foreach ($style_css as $k=>$v){
            //if a old css file exist => unlink
            if(file_exists($k))
                unlink($k);
            //scss
            $template = file_get_contents(Plugin::getPhpDir("whitelabel")."/styles/".$v);
            // Interpolate SCSS
            $style = strtr($template, $map);
            if (isset($map['css_configuration']) && $map['css_configuration'] != ""){
                $style += "\n@import url('".$map['css_configuration']."');\n";
            }
            if (isset($map['%logo_central%']) && $map['%logo_central%'] != ""){
                $style .= "\n\$logo-file: url('"."../plugins/whitelabel/uploads/".$map['%logo_central%']."');\n";
            }
            file_put_contents($k, $style);
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
        if (in_array($field, explode(',',$_POST["selected_rows"]))) {
            $sql = new table_glpi_plugin_whitelabel_brand();
            //check this file exist
            $row=$sql-> select($field);   
            if (isset($row[$field])){
                //unlink file
                if (isset($row[$field]) && $row[$field] != "" && file_exists(Plugin::getPhpDir("whitelabel")."/uploads/".$row[$field]))
                    unlink(Plugin::getPhpDir("whitelabel")."/uploads/".$row[$field]);
                //update table
                $sql-> update(array($field=>''));  
                return true; 
            }            
        }
        return false;
    }
}
