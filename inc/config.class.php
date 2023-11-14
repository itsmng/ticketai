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

    const DEFAULT_USER_PROMPT = "
Vous êtes un assistant dédié au support informatique, offrant une assistance aux utilisateurs d'un parc informatique.
Ces utilisateurs sont pris en charge par une équipe de techniciens du support technique.
Il existe deux catégories de problèmes auxquelles les utilisateurs peuvent être confrontés : 'Incident' et 'Demande d'évolution'.
Lorsqu'un utilisateur signale un problème, votre rôle consiste à poser des questions pour comprendre et résoudre le problème, en les guidant vers une solution.
Si le problème est lié à un logiciel, vous devez attribuer le ticket au technicien de support logiciel (ID 2).
Si le problème est lié au matériel, vous devez attribuer le ticket au technicien de support matériel (ID 4).
";

    const DEFAULT_FOLLOWUP_PROMPT = "
Vous êtes une aide technicien a la résolution de problème. Vous devez poser des questions au technicien pour resumer ses actions et les enregistrer dans le ticket.
";

    const DEFAULT_CLOSE_PROMPT = "
Vous êtes une aide technicien a la résolution de problème. Vous devez poser des questions au technicien pour resumer ses actions et les enregistrer dans le ticket.
";

    const USER_FORMAT_PROMPT = "
Si vous ne parvenez pas à trouver une solution directe, vous devez envoyer un message contenant UNIQUEMENT le JSON du ticket suivant :
{
    'name': '...',
    'content': '...',
    'type': '...',
    'user_id_assign': '...'
}
Les champs du JSON sont les suivants :
'name' : le titre du ticket.
'content' : une description détaillée du problème (essayez d'être aussi précis que possible).
'type' : le type de ticket ('1' pour incident, '2' pour demande d'évolution).
'user_id_assign' : l'identifiant de l'utilisateur auquel attribuer le ticket.
N'oubliez pas d'utiliser des guillemets doubles pour le format JSON. Le ticket sera créé et attribué à l'utilisateur lorsque vous l'enverrez. Veillez à clore la conversation en un maximum de 5 messages
";

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

        $form = [
            'action' => $form_action,
            'method' => 'post',
            'buttons' => [
                [
                    'type' => 'submit',
                    'name' => 'update_config',
                    'value' => __('Update'),
                    'class' => 'submit-button btn btn-warning',
                ],
            ],
            'content' => [
                __('General configuration') => [
                    'visible' => true,
                    'inputs' => [
                        $api_key_label => [
                            'name' => 'api_key',
                            'type' => 'text',
                            'value' => $api_key
                        ],
                    ]
                ],
                __('User Prompt') => [
                    'visible' => true,
                    'inputs' => [
                        __("Activated") => [
                            'name' => 'user_activated',
                            'type' => 'checkbox',
                            'value' => 1,
                            $config['user_activated'] ? 'checked' : '' => '',
                        ],
                        $prompt_label => [
                            'name' => 'user_prompt',
                            'type' => 'textarea',
                            'value' => $config['user_prompt'],
                            'rows' => 10,
                        ],
                    ]
                ],
                __('Technician Prompt') => [
                    'visible' => true,
                    'inputs' => [
                        __('Activated') => [
                            'name' => 'tech_activated',
                            'type' => 'checkbox',
                            'value' => 1,
                            $config['tech_activated'] ? 'checked' : '' => '',
                        ],
                        $prompt_label . ' ' . __('Followup') => [
                            'name' => 'tech_prompt_followup',
                            'type' => 'textarea',
                            'value' => $config['tech_prompt_followup'],
                            'rows' => 10,
                        ],
                        $prompt_label . ' ' . __('Solution') => [
                            'name' => 'tech_prompt_close',
                            'type' => 'textarea',
                            'value' => $config['tech_prompt_close'],
                            'rows' => 10,
                        ],
                    ]
                ],
                '' => [
                    'visible' => false,
                    'inputs' => [
                        'action' => [
                            'name' => 'action',
                            'type' => 'hidden',
                            'value' => 'update'
                        ]
                    ]
                ]
            ]
        ];

        include_once GLPI_ROOT."/ng/form.utils.php";
        renderTwigForm($form);
        
    }

    public function updateConfig() {
        global $DB;

        $api_key = $_POST["api_key"];
        $prompt = $_POST["prompt"];
        $DB->request("UPDATE glpi_plugin_whitelabel_config SET api_key='$api_key', prompt='$prompt' WHERE id=1");
    }

    static public function getConfig() {
        global $DB;

        $config = ($DB->request("SELECT * FROM glpi_plugin_ticketai_config WHERE id=1"))->next();
        return $config;
    }
}
