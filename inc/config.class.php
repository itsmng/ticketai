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
Reponds dans la langue du client.";

    const DEFAULT_TECH_PROMPT = "
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

        $form_action = Plugin::getWebDir("ticketai")."/front/config.form.php";
        
        $config = ($DB->request("SELECT * FROM glpi_plugin_ticketai_config WHERE id=1"))->next();

        $update_models = <<<JS
            function formatNumber(number) {
                const gigabyte = 1000000000;

                if (number >= gigabyte) {
                    return (number / gigabyte).toFixed(1) + ' GB';
                } else {
                    return number.toString() + ' Bytes';
                }
            }

            function updateModels(id, value) {
                var endpoint = document.getElementById('endpointTextInput').value;
                var model = document.getElementById(id);
                var url = endpoint + '/api/tags';
                var xhr = new XMLHttpRequest();

                if (!endpoint)
                    return;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            models = data.models;
                            model.innerHTML = '';
                            for (const singleModel of models) {
                                var option = document.createElement('option');
                                option.value = singleModel.name;
                                option.innerHTML = singleModel.name + ' (' + formatNumber(singleModel.size) + ')';
                                model.appendChild(option);
                            }
                            // select the model in config
                            model.value = value;
                        });

            }
            updateModels('userModelSelectInput', '{$config['user_model']}');
            updateModels('techModelSelectInput', '{$config['tech_model']}');
        JS;

        $imgFile = Plugin::getWebDir('ticketai') . '/img/ticketai.png';

        $disableInputs =  <<<JS
            setTimeout(() => {
                onPremiseConfig = [
                    'endpointTextInput',
                    'userModelSelectInput',
                    'techModelSelectInput',
                ];
                onlineConfig = [
                    'gptEndpointTextInput',
                    'userPromptTextInput',
                    'techPromptTextInput',
                ];
                if ($('#connectionTypeSelectInput').val() == 'online') {
                    for (const input of onPremiseConfig) {
                        document.getElementById(input).disabled = true;
                    }
                    for (const input of onlineConfig) {
                        document.getElementById(input).disabled = false;
                    }
                } else {
                    for (const input of onPremiseConfig) {
                        document.getElementById(input).disabled = false;
                    }
                    for (const input of onlineConfig) {
                        document.getElementById(input).disabled = true;
                    }
                }
            }, 100);
        JS;

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
                '' => [
                    'visible' => true,
                    'inputs' => [
                            'logo' => [
                            'content' => <<<HTML
                                <div class="text-center w-100" style="height: 10rem">
                                    <img src="{$imgFile}" class="h-100" alt="ollama logo" />
                                </div>
                            HTML,
                        ],
                    ],
                ],
                __('General configuration') => [
                    'visible' => true,
                    'inputs' => [
                        __("User Activation") => [
                            'name' => 'user_activated',
                            'type' => 'checkbox',
                            'value' => 1,
                            $config['user_activated'] ? 'checked' : '' => '',
                        ],
                        __('Technician activation') => [
                            'name' => 'tech_activated',
                            'type' => 'checkbox',
                            'value' => 1,
                            $config['tech_activated'] ? 'checked' : '' => '',
                        ],
                        __('Connection') => [
                            'name' => 'connection_type',
                            'type' => 'select',
                            'value' => $config['connection_type'],
                            'id' => 'connectionTypeSelectInput',
                            'values' => [
                                'on_premise' => __('On premise'),
                                'online' => __('Online'),
                            ],
                            'init' => $disableInputs,
                            'hooks' => [
                                'change' => $disableInputs,
                            ]
                        ],

                    ]
                ],
                __('On premise configuration') => [
                    'visible' => true,
                    'inputs' => [
                        __("API Endpoint") => [
                            'name' => 'endpoint',
                            'id' => 'endpointTextInput',
                            'type' => 'text',
                            'value' => $config['endpoint'],
                            'placeholder' => 'https://api.ticketai.com:1234',
                            'col_lg' => 12,
                        ],
                        __("User Model") => [
                            'name' => 'user_model',
                            'id' => 'userModelSelectInput',
                            'type' => 'select',
                            'value' => $config['user_model'],
                            'values' => [],
                            'init' => $update_models,
                            'col_lg' => 6,
                        ],
                        __("Technician Model") => [
                            'name' => 'tech_model',
                            'id' => 'techModelSelectInput',
                            'type' => 'select',
                            'value' => $config['tech_model'],
                            'values' => [],
                            'init' => $update_models,
                            'col_lg' => 6,
                        ]
                    ]
                ],
                __('Online configuration (chatGPT)') => [
                    'visible' => true,
                    'inputs' => [
                        __("API key") => [
                            'name' => 'api_key',
                            'id' => 'gptEndpointTextInput',
                            'type' => 'text',
                            'value' => $config['api_key'],
                            'col_lg' => 12,
                        ],
                        __("User Prompt") => [
                            'name' => 'user_prompt',
                            'id' => 'userPromptTextInput',
                            'type' => 'textarea',
                            'value' => $config['user_prompt'],
                            'rows' => 10,
                            'col_lg' => 6,
                        ],
                        __("Technician Prompt") => [
                            'name' => 'tech_prompt',
                            'id' => 'techPromptTextInput',
                            'type' => 'textarea',
                            'value' => $config['tech_prompt'],
                            'rows' => 10,
                            'col_lg' => 6,
                        ],
                    ]
                ],
                'hidden_inputs' => [
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

    public function updateConfig($params) {
        global $DB;
        // Assign values to variables
        $config = $this->getConfig();
        $endpoint = $params["endpoint"] ?? $config['endpoint'];
        $api_key = $params["api_key"] ?? $config['api_key'];
        $connection_type = $params["connection_type"] ?? $config['connection_type'];
        $user_model = $params["user_model"] ?? $config['user_model'];
        $tech_model = $params["tech_model"] ?? $config['tech_model'];
        $user_activated = $params["user_activated"] ?? 0;
        $tech_activated = $params["tech_activated"] ?? 0;
        $user_prompt = $params["user_prompt"] ?? $config['user_prompt'];
        $tech_prompt = $params["tech_prompt"] ?? $config['tech_prompt'];
    
        // Prepare the statement
        $stmt = $DB->prepare('
            UPDATE glpi_plugin_ticketai_config 
            SET 
                endpoint = ?,
                api_key = ?,
                connection_type = ?,
                user_model = ?,
                tech_model = ?,
                user_activated = ?,
                tech_activated = ?,
                user_prompt = ?,
                tech_prompt = ?
            WHERE id = 1');

    
        $stmt->bind_param('sssssiiss',
            $endpoint,
            $api_key,
            $connection_type,
            $user_model,
            $tech_model,
            $user_activated,
            $tech_activated,
            $user_prompt,
            $tech_prompt,
        );
        $stmt->execute();
        $stmt->close();
    }

    static public function getConfig() {
        global $DB;

        $config = ($DB->request("SELECT * FROM glpi_plugin_ticketai_config WHERE id=1"))->next();
        return $config;
    }
}
