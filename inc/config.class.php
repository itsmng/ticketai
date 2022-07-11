<?php 

class PluginWhitelabelConfig extends CommonDBTM {
    /**
     * Displays the configuration page for the plugin
     * 
     * @param mixed $postData HTTP POST form data
     * 
     * @return void
     */
    function showConfigForm($postData) {
        global $DB;

        echo "<form method='post' action='./config.form.php' method='post'>";
        echo "<table class='tab_cadre' cellpadding='5'>";
        echo "<tr><th colspan='2'>".__("Whitelabel Settings", 'holidays')."</th></tr>";       
        
        $this->startField("Brand color");
        Html::showColorField("brand_color", [
            "value" => $this->getBrandColor(),
        ]);
        $this->endField();

        $this->startField(sprintf(__('Favicon (%s)'), Document::getMaxUploadSize()));
        // For some reason, the file upload field accepts multiple files even though multiple is false
        Html::file([
            'name'          => 'favicon',
            'showfilesize'  => true,
            'onlyimages'    => true,
            'showtitle'     => false,
            'multiple'      => false,
         ]);
        $this->endField();

        $this->startField(sprintf(__('Logo (%s)'), Document::getMaxUploadSize()));
        // For some reason, the file upload field accepts multiple files even though multiple is false
        Html::file([
            'name'          => 'logo',
            'showfilesize'  => true,
            'onlyimages'    => true,
            'showtitle'     => false,
            'multiple'      => false,
         ]);
        $this->endField();

        echo "<tr class='tab_bg_1'><td class='center' colspan='2'>";
        echo "<input type='submit' name='update' class='submit'>";
        echo "</td></tr>";
        echo "</table>";
        Html::closeForm();
    }

    private function getBrandColor() {
        global $DB;
        $query = "SELECT brand_color FROM itsm_plugin_whitelabel_brand WHERE id = '1'";
        $result = $DB->query($query);
        if ($DB->numrows($result) > 0) {
            return $DB->result($result, 0, 'value');
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
    private function startField($label) {
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

    function handleWhitelabel($postData, $filesData) {

    }
}