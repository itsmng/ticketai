<?php
use ScssPhp\ScssPhp\Compiler;

class PluginWhitelabelConfig extends CommonDBTM {
    /**
     * Displays the configuration page for the plugin
     * 
     * @return void
     */
    function showConfigForm() {
        global $DB;

        if (!Session::haveRight("plugin_whitelabel_whitelabel",UPDATE)) {
            return false;
        }

        echo "<form enctype='multipart/form-data' action='./config.form.php' method='post'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr><th colspan='2'>".__("Whitelabel Settings", 'holidays')."</th></tr>";       
        
        $this->startField("Brand color");
        Html::showColorField("brand_color", [
            "value" => $this->getBrandColor(),
        ]);
        $this->endField();

        $this->startField(sprintf(__('Favicon (%s)'), Document::getMaxUploadSize()));
        $this->showImageUploadField("favicon");
        $this->endField();

        $this->startField(sprintf(__('Logo (%s)'), Document::getMaxUploadSize()));
        $this->showImageUploadField("logo_central");
        $this->endField();

        echo "<tr class='tab_bg_1'><td class='center' colspan='2'>";
        echo "<input type='submit' name='update' class='submit'>";
        echo "</td></tr>";
        echo "</table>";
        Html::closeForm();
    }

    /**
     * Displays an image upload field
     *
     * @param string Field name
     *
     * @return void
     */
    private function showImageUploadField(string $fieldName) {
        global $DB;
        $path = Plugin::getPhpDir("whitelabel", false)."/uploads/";
        $row = $DB->queryOrDie("SELECT * FROM `itsm_plugin_whitelabel_brand` WHERE id = 1", $DB->error())->fetch_assoc();
        if (!empty($row[$fieldName])) {
            echo Html::image($path.$row[$fieldName], [
                'style' => 'max-width: 100px; max-height: 50px;',
                'class' => 'picture_square'
            ]);
            echo "&nbsp;&nbsp;";
            echo "<input type='checkbox' name='_blank_$fieldName' value='No'/>";
            echo "&nbsp;".__('Clear');
        } else {
            echo "<input name='$fieldName' type='file' />";
        }
    }

    private function getBrandColor() {
        global $DB;
        $query = "SELECT brand_color FROM itsm_plugin_whitelabel_brand WHERE id = '1'";
        $result = $DB->query($query);
        if ($DB->numrows($result) > 0) {
            return $DB->result($result, 0, 'brand_color');
        }
        return '#7b081d';
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

    function handleWhitelabel() {
        global $DB;
        $color = $_POST["brand_color"];
        $DB->queryOrDie("UPDATE `itsm_plugin_whitelabel_brand` SET brand_color = '$color' WHERE id = 1", $DB->error());

        $this->handleFile("favicon", array("image/x-icon"));
        $this->handleFile("logo_central", array("image/jpeg", "image/gif", "image/png"));

        if ($this->handleClear("favicon")) {
            copy(Plugin::getPhpDir("whitelabel")."/bak/favicon.ico.bak", GLPI_ROOT."/pics/favicon.ico");
        }
        $this->handleClear("logo_central");

        copy(Plugin::getPhpDir("whitelabel")."/uploads/favicon.ico", GLPI_ROOT."/pics/favicon.ico");
        chown(GLPI_ROOT."/pics/favicon.ico", 0664);
    }

    /**
     * Generate and install new CSS sheets w/ styles mapped
     */
    function refreshCss() {
        global $DB;
        $row = $DB->queryOrDie("SELECT * FROM `itsm_plugin_whitelabel_brand` WHERE id = 1", $DB->error())->fetch_assoc();

        list($r, $g, $b) = sscanf($row["brand_color"], "#%02x%02x%02x"); // Magic format parse to do #HEX to RGB
        // Apply color offsets from the stock theme
        $secondary     = sprintf("#%02x%02x%02x",
            $this->rgbClamp($r + 13),
            $this->rgbClamp($g + 4),
            $this->rgbClamp($b + 23));
        $ternary       = sprintf("#%02x%02x%02x",
            $this->rgbClamp($r + 122),
            $this->rgbClamp($g + 124),
            $this->rgbClamp($b + 148));
        $ternary_hover = sprintf("#%02x%02x%02x",
            $this->rgbClamp($r + 71),
            $this->rgbClamp($g + 96),
            $this->rgbClamp($b + 111));
        $quad          = sprintf("#%02x%02x%02x",
            $this->rgbClamp($r + 18),
            $this->rgbClamp($g + 4),
            $this->rgbClamp($b + 45));
        $quad_hover    = sprintf("#%02x%02x%02x",
            $this->rgbClamp($r + 8),
            $this->rgbClamp($g + 2),
            $this->rgbClamp($b + 23));
        $low_contrast  = sprintf("#%02x%02x%02x",
            $this->rgbClamp($r - 19),
            $this->rgbClamp($g + 20),
            $this->rgbClamp($b + 45));
        $logo = "/pics/fd_logo.png";
        list($logoW, $logoH) = getimagesize(GLPI_ROOT."/pics/fd_logeo.png");
        copy(GLPI_ROOT."/pics/fd_logo.png", GLPI_ROOT."/pics/login_logo_whitelabel.png");
        if(!empty($row["logo_central"])) {
            list($logoW, $logoH) = getimagesize(Plugin::getPhpDir("whitelabel", true) . "/uploads/logo_central.png");
            $logo = $row["logo_central"];
            copy(Plugin::getPhpDir("whitelabel")."/uploads/".$row["logo_central"], GLPI_ROOT."/pics/login_logo_whitelabel.png");
        }
        $map = [
            "%brand_color%"               => $row["brand_color"],
            "%brand_color_secondary%"     => $secondary,
            "%brand_color_ternary%"       => $ternary,
            "%brand_color_hover_ternary%" => $ternary_hover,
            "%brand_color_quad%"          => $quad,
            "%brand_color_hover_quad%"    => $quad_hover,
            "%low_contrast_text%"         => $low_contrast,
            "%logo%"                      => $logo,
            "%logo_width%"                => ceil(55 * ($logoW / $logoH))
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

        unlink(Plugin::getPhpDir("whitelabel", true)."/uploads/whitelabel.css");
        unlink(GLPI_ROOT."/css/whitelabel_login.css");
        // Place compiled CSS
        file_put_contents(Plugin::getPhpDir("whitelabel", true)."/uploads/whitelabel.css", $css);
        file_put_contents(GLPI_ROOT."/css/whitelabel_login.css", $loginCss);

        // Ensure permissions
        chmod(Plugin::getPhpDir("whitelabel", true)."/uploads/whitelabel.css", 0664);
        chmod(GLPI_ROOT."/css/whitelabel_login.css", 0664);

        // Clear cache
        $files = glob(GLPI_ROOT."/files/_cache/*");
        foreach($files as $file){
            if(is_file($file)) {
                unlink($file);
            }
        }
        $files = glob(GLPI_ROOT."/files/_tmp/*");
        foreach($files as $file){
            if(is_file($file)) {
                unlink($file);
            }
        }
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
                unlink($uploadfile);
                if (move_uploaded_file($_FILES[$file]["tmp_name"], $uploadfile)) {
                    $DB->queryOrDie("UPDATE `itsm_plugin_whitelabel_brand` SET $file = '$file.".$ext."' WHERE id = 1", $DB->error());
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
    private function createDirectoryIfNotExist(string $path)
    {
        if (!file_exists($path)) {
           mkdir($path, 0664);
        } elseif (!is_dir($path)) {
            return false;
        }
        return true;
    }

    private function handleClear(string $field)
    {
        global $DB;
        if (isset($_POST["_blank_".$field])) {
            $row = $DB->queryOrDie("SELECT * FROM `itsm_plugin_whitelabel_brand` WHERE id = 1", $DB->error())->fetch_assoc();
            unlink(Plugin::getPhpDir("whitelabel")."/uploads/".$row[$field]);
            $DB->queryOrDie("UPDATE `itsm_plugin_whitelabel_brand` SET $field = '' WHERE id = 1", $DB->error());
            return true;
        }
        return false;
    }

    private function rgbClamp($value) {
        return max(0, min(255, $value));
    }
}
