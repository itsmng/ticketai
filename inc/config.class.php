<?php 

class PluginWhitelabelConfig extends CommonDBTM {
    private $pathToStore = "/pics/whitelabel";
    /**
     * Displays the configuration page for the plugin
     * 
     * @return void
     */
    function showConfigForm() {
        global $DB;

        echo "<form enctype='multipart/form-data' action='./config.form.php' method='post'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr><th colspan='2'>".__("Whitelabel Settings", 'holidays')."</th></tr>";       
        
        $this->startField("Brand color");
        Html::showColorField("brand_color", [
            "value" => $this->getBrandColor(),
        ]);
        $this->endField();

        $this->startField("Secondary color");
        Html::showColorField("brand_color_secondary", [
            "value" => $this->getSecondaryColor(),
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
        $path = GLPI_ROOT.$this->pathToStore;
        $row = $DB->queryOrDie("SELECT * FROM `itsm_plugin_whitelabel_brand` WHERE id = 1", $DB->error())->fetch_assoc();
        if (!empty($row[$fieldName])) {
            echo Html::image($this->pathToStore.$row[$fieldName], [
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

    private function getSecondaryColor() {
        global $DB;
        $query = "SELECT brand_color_secondary FROM itsm_plugin_whitelabel_brand WHERE id = '1'";
        $result = $DB->query($query);
        if ($DB->numrows($result) > 0) {
            return $DB->result($result, 0, 'brand_color_secondary');
        }
        return '#ae0c2a';
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
        $color_secondary = $_POST["brand_color_secondary"];
        $DB->queryOrDie("UPDATE `itsm_plugin_whitelabel_brand` SET brand_color_secondary = '$color_secondary' WHERE id = 1", $DB->error());

        $this->handleFile("favicon");
        $this->handleFile("logo_central");

        $this->handleClear("favicon");
        $this->handleClear("logo_central");
    }

    function refreshScss() {
        global $DB;
        $row = $DB->queryOrDie("SELECT * FROM `itsm_plugin_whitelabel_brand` WHERE id = 1", $DB->error())->fetch_assoc();
        $scssTemplate = "body {
  background-color: ".$row["brand_color"]." !important;
}

.radio .outer {
  border: 3px solid ".$row["brand_color"]." !important;
}

#firstboxlogin {
  background-color: ".$row["brand_color"]." !important;
}

#header_top {
  background-color: ".$row["brand_color"]." !important;
}

.x-button.x-button-drop {
  &.planned:after {
    color: ".$row["brand_color"]." !important;
  }
}

.itilstatus {
  color: ".$row["brand_color"]." !important;
}";
        file_put_contents(GLPI_ROOT."/plugins/whitelabel/styles/template.scss", $scssTemplate);
    }

    private function handleFile($file) {
        global $DB;
        if(empty($_FILES[$file])) {
            return;
        }
        // Get error code from file upload action
        switch ($_FILES[$file]["error"]) {
            case UPLOAD_ERR_OK:
                $allowed = array("image/jpeg", "image/gif", "image/png");
                if (!in_array($_FILES[$file]["type"], $allowed)) {
                    echo "Only images are supported for $file files!";
                    exit();
                }
                $this->createDirectoryIfNotExist(GLPI_ROOT.$this->pathToStore);
                $name = basename($_FILES[$file]["name"]);
                $uploadfile = GLPI_ROOT.$this->pathToStore.$name;
                if (move_uploaded_file($_FILES[$file]["tmp_name"], $uploadfile)) {
                    echo "File Uploaded!";
                    $DB->queryOrDie("UPDATE `itsm_plugin_whitelabel_brand` SET $file = '$name' WHERE id = 1", $DB->error());
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
           mkdir($path, 0777);
        } elseif (!is_dir($path)) {
            return false;
        }
        return true;
    }

    private function handleClear(string $field)
    {
        global $DB;
        if (isset($_POST["_blank_".$field])) {
            $DB->queryOrDie("UPDATE `itsm_plugin_whitelabel_brand` SET $field = '' WHERE id = 1", $DB->error());
        }
    }
}