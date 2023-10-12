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

    const DEFAULT_PROMPT = "
    Vous êtes mon assistant pour m'aider a résoudre mon problème.
    Restez concis et gardez un format de réponse simple.
    
    Donnez moi des conseils pour résoudre mon problème.
    Si je vous dis que vos conseils me fonctionnent pas, donnez moi une réponse contenant uniquement un json de la forme:
    {
    'name': '...',
    'content': '...'
    'itemType': '...'
    }
    ou name est le titre du ticket, content une description du problème en détails et itemType l'objet référencé dans le ticket.
    Le message ne doit contenir que le json pour qu'il puisse être parse directement avec JSON.parse()";

    /**
     * Displays the configuration page for the plugin
     * 
     * @return void
     */
    public function showConfigForm() {
        global $DB;

        $api_key_label = __("API Key");
        $prompt_label = __("Prompt");
        $form_action = Plugin::getWebDir("ticketai")."/front/config.form.php";
        
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
