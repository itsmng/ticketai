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
class PluginTicketaiConfig extends CommonDBTM {

    const DEFAULT_PROMPT = '
    nous sommes une société de service en informatique, nous aidons les clients pour depanner leur probleme informatique.
    il y a 4 services différents :
    Le service support systeme
    Le service support réseau
    le service support materiel
    Le service divers qui gere toutes les autres demandes.
    Les clients sont partout en france et regroupé par département.
    du coté des services support il y a une équipe support par département, par exemple, pour le département 75 , il a :
    support_systeme_75
    support_reseau_75
    support_materiel_75
    support_divers_75
    
    peux tu en fonction des demandes et de la ville du client me proposer la bonne équipe support.
    Reponds moi un fichier au format JSON.
    ';

    /**
     * Displays the configuration page for the plugin
     * 
     * @return void
     */
    public function showConfigForm() {
        global $DB;

        $api_key_label = __("API Key");
        $prompt_label = __("Prompt");
        $form_action = Plugin::getWebDir("whitelabel")."/front/config.form.php";
        
        $config = ($DB->request("SELECT * FROM glpi_plugin_ticketai_config WHERE id=1"))->next();
        $api_key = $config["api_key"];
        $prompt = $config["prompt"];
        
        echo <<<HTML
            <form class="container mx-auto text-center" action="$form_action" method="post">
                <div class="mb-3 d-flex flex-column w-25 mx-auto">
                    <label for="api_key_input">$api_key_label</label>
                    <input type="text" name="api_key" id="api_key_input" value="$api_key"/>
                </div>
                <div class="mb-3 d-flex flex-column w-25 mx-auto">
                    <label for="prompt_input">$prompt_label</label>
                    <textarea name="prompt" id="prompt_input" rows="15">$prompt</textarea>
                </div>
                <button type="submit" class="btn btn-warning">Submit</button>
            </form>
        HTML;
    }

    public function updateConfig() {
        global $DB;

        $api_key = $_POST["api_key"];
        $prompt = $_POST["prompt"];
        $DB->request("UPDATE glpi_plugin_whitelabel_config SET api_key='$api_key', prompt='$prompt' WHERE id=1");
    }
}
