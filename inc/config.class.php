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

        $this->startField(__("Primary color", 'whitelabel'));
        Html::showColorField("primary_color", ["value" => $colors["primary_color"]]);
        $this->endField();

        $this->startField("<hr>");
        echo "<hr>";
        $this->endField();

        $this->startField(__("Menu color", 'whitelabel'));
        Html::showColorField("menu_color", ["value" => $colors["menu_color"]]);
        $this->endField();

        $this->startField(__("Active menu color", 'whitelabel'));
        Html::showColorField("menu_active_color", ["value" => $colors["menu_active_color"]]);
        $this->endField();

        $this->startField(__("On hover menu color", 'whitelabel'));
        Html::showColorField("menu_onhover_color", ["value" => $colors["menu_onhover_color"]]);
        $this->endField();

        $this->startField(__("Dropdown menu background color", 'whitelabel'));
        Html::showColorField("dropdown_menu_background_color", ["value" => $colors["dropdown_menu_background_color"]]);
        $this->endField();

        $this->startField(__("Dropdown menu text color", 'whitelabel'));
        Html::showColorField("dropdown_menu_text_color", ["value" => $colors["dropdown_menu_text_color"]]);
        $this->endField();

        $this->startField(__("Dropdown menu text hover color", 'whitelabel'));
        Html::showColorField("dropdown_menu_text_hover_color", ["value" => $colors["dropdown_menu_text_hover_color"]]);
        $this->endField();

        $this->startField("<hr>");
        echo "<hr>";
        $this->endField();

        $this->startField(__("Alert background color", 'whitelabel'));
        Html::showColorField("alert_background_color", ["value" => $colors["alert_background_color"]]);
        $this->endField();

        $this->startField(__("Alert text color", 'whitelabel'));
        Html::showColorField("alert_text_color", ["value" => $colors["alert_text_color"]]);
        $this->endField();

        $this->startField(__("Alert header background color", 'whitelabel'));
        Html::showColorField("alert_header_background_color", ["value" => $colors["alert_header_background_color"]]);
        $this->endField();

        $this->startField(__("Alert header text color", 'whitelabel'));
        Html::showColorField("alert_header_text_color", ["value" => $colors["alert_header_text_color"]]);
        $this->endField();

        $this->startField("<hr>");
        echo "<hr>";
        $this->endField();

        $this->startField(__("Table header background color", 'whitelabel'));
        Html::showColorField("table_header_background_color", ["value" => $colors["table_header_background_color"]]);
        $this->endField();

        $this->startField(__("Table header text color", 'whitelabel'));
        Html::showColorField("table_header_text_color", ["value" => $colors["table_header_text_color"]]);
        $this->endField();

        $this->startField("<hr>");
        echo "<hr>";
        $this->endField();

        $this->startField(__("Object name color", 'whitelabel'));
        Html::showColorField("object_name_color", ["value" => $colors["object_name_color"]]);
        $this->endField();

        $this->startField("<hr>");
        echo "<hr>";
        $this->endField();

        $this->startField(__("Button color", 'whitelabel'));
        Html::showColorField("button_color", ["value" => $colors["button_color"]]);
        $this->endField();

        $this->startField(__("Secondary button background color", 'whitelabel'));
        Html::showColorField("secondary_button_background_color", ["value" => $colors["secondary_button_background_color"]]);
        $this->endField();

        $this->startField(__("Secondary button text color", 'whitelabel'));
        Html::showColorField("secondary_button_text_color", ["value" => $colors["secondary_button_text_color"]]);
        $this->endField();

        $this->startField(__("Secondary button box-shadow color", 'whitelabel'));
        Html::showColorField("secondary_button_box_shadow_color", ["value" => $colors["secondary_button_box_shadow_color"]]);
        $this->endField();

        $this->startField(__("Submit button background color", 'whitelabel'));
        Html::showColorField("submit_button_background_color", ["value" => $colors["submit_button_background_color"]]);
        $this->endField();

        $this->startField(__("Submit button text color", 'whitelabel'));
        Html::showColorField("submit_button_text_color", ["value" => $colors["submit_button_text_color"]]);
        $this->endField();

        $this->startField(__("Submit button box-shadow color", 'whitelabel'));
        Html::showColorField("submit_button_box_shadow_color", ["value" => $colors["submit_button_box_shadow_color"]]);
        $this->endField();

        $this->startField(__("Vsubmit button background color", 'whitelabel'));
        Html::showColorField("vsubmit_button_background_color", ["value" => $colors["vsubmit_button_background_color"]]);
        $this->endField();

        $this->startField(__("Vsubmit button text color", 'whitelabel'));
        Html::showColorField("vsubmit_button_text_color", ["value" => $colors["vsubmit_button_text_color"]]);
        $this->endField();

        $this->startField(__("Vsubmit button box-shadow color", 'whitelabel'));
        Html::showColorField("vsubmit_button_box_shadow_color", ["value" => $colors["vsubmit_button_box_shadow_color"]]);
        $this->endField();

        $this->startField("<hr>");
        echo "<hr>";
        $this->endField();

        $this->startField(sprintf(__('Favicon (%s)'), Document::getMaxUploadSize()));
        $this->showImageUploadField("favicon");
        $this->endField();

        $this->startField(sprintf(__('Logo (%s)'), Document::getMaxUploadSize()));
        $this->showImageUploadField("logo_central");
        $this->endField();

        echo "<tr class='tab_bg_1'><td class='center' colspan='2'>";
        echo "<input type='submit' name='update' class='submit'>&nbsp;&nbsp;<input type='submit' name='reset' class='submit' value='".__('Restore colors', 'whitelabel')."'>";
        echo "</td></tr>";
        echo "</table>";
        Html::closeForm();
    }

    /**
     * Displays image upload field
     *
     * @param string Field name
     *
     * @return void
     */
    private function showImageUploadField(string $fieldName) {
        global $DB;
        $path = Plugin::getPhpDir("whitelabel", false)."/uploads/";
        $row = $DB->queryOrDie("SELECT * FROM `glpi_plugin_whitelabel_brand` WHERE `id` = 1", $DB->error())->fetch_assoc();

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
    
    /**
     * Get the primary theme color
     *
     * @return string
     */
    private function getThemeColors() {
        global $DB;

        // Default colors
        $colors = [
            'primary_color' => '#7b081d',
            'menu_color' => '#ae0c2a',
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

        $query = "SELECT * FROM `glpi_plugin_whitelabel_brand` WHERE id = '1'";
        $result = $DB->query($query);

        if ($DB->numrows($result) > 0) {
            $colors = [
                'primary_color' => $DB->result($result, 0, 'primary_color'),
                'menu_color' => $DB->result($result, 0, 'menu_color'),
                'menu_active_color' => $DB->result($result, 0, 'menu_active_color'),
                'menu_onhover_color' => $DB->result($result, 0, 'menu_onhover_color'),
                'dropdown_menu_background_color' => $DB->result($result, 0, 'dropdown_menu_background_color'),
                'dropdown_menu_text_color' => $DB->result($result, 0, 'dropdown_menu_text_color'),
                'dropdown_menu_text_hover_color' => $DB->result($result, 0, 'dropdown_menu_text_hover_color'),
                'alert_background_color' => $DB->result($result, 0, 'alert_background_color'),
                'alert_text_color' => $DB->result($result, 0, 'alert_text_color'),
                'alert_header_background_color' => $DB->result($result, 0, 'alert_header_background_color'),
                'alert_header_text_color' => $DB->result($result, 0, 'alert_header_text_color'),
                'table_header_background_color' => $DB->result($result, 0, 'table_header_background_color'),
                'table_header_text_color' => $DB->result($result, 0, 'table_header_text_color'),
                'object_name_color' => $DB->result($result, 0, 'object_name_color'),
                'button_color' => $DB->result($result, 0, 'button_color'),
                'secondary_button_background_color' => $DB->result($result, 0, 'secondary_button_background_color'),
                'secondary_button_text_color' => $DB->result($result, 0, 'secondary_button_text_color'),
                'secondary_button_box_shadow_color' => $DB->result($result, 0, 'secondary_button_box_shadow_color'),
                'submit_button_background_color' => $DB->result($result, 0, 'submit_button_background_color'),
                'submit_button_text_color' => $DB->result($result, 0, 'submit_button_text_color'),
                'submit_button_box_shadow_color' => $DB->result($result, 0, 'submit_button_box_shadow_color'),
                'vsubmit_button_background_color' => $DB->result($result, 0, 'vsubmit_button_background_color'),
                'vsubmit_button_text_color' => $DB->result($result, 0, 'vsubmit_button_text_color'),
                'vsubmit_button_box_shadow_color' => $DB->result($result, 0, 'vsubmit_button_box_shadow_color')
            ];
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

        if($_POST["menu_color"]) {
            $color = (!$reset) ? $_POST["menu_color"] : '#ae0c2a';
            $DB->queryOrDie("UPDATE `glpi_plugin_whitelabel_brand` SET `menu_color` = '$color' WHERE `id` = 1", $DB->error());
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
        
        $this->handleFile("favicon", array("image/x-icon"));
        $this->handleFile("logo_central", array("image/png"));

        if ($this->handleClear("favicon")) {
            copy(Plugin::getPhpDir("whitelabel")."/bak/favicon.ico.bak", GLPI_ROOT."/pics/favicon.ico");
        }

        $this->handleClear("logo_central");

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
        $menuColor = (!$reset) ? $row["menu_color"] : '#ae0c2a';
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
            "%menu_color%" => $menuColor,
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
