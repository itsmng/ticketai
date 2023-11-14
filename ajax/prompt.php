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

include('../../../inc/includes.php');
include('../vendor/autoload.php');

global $DB;

if (!isset($_POST['messages'])) {
    $_POST['messages'] = [];
}

$messages = $_POST['messages'];

// Initialize the OpenAI client for the user
$config = $DB->request("SELECT * FROM glpi_plugin_ticketai_config WHERE id=1")->next();
$api_key = $config["api_key"];
$prompt = '';
switch ($_POST['context']) {
    case 'followup':
        $prompt = $config["tech_prompt_followup"];
        break;
    case 'solution':
        $prompt = $config["tech_prompt_close"];
        break;
    default:
        $prompt = $config["user_prompt"];
        break;
}

$systemPrompt = [
    'role' => 'system',
    'content' => $prompt . ($_POST['context'] == 'helpdesk' ? PluginTicketaiConfig::USER_FORMAT_PROMPT : $_POST['ticket_id'])
];

if ($_POST['context'] != 'helpdesk') {
    $ticket = new Ticket();
    $ticket->getFromDB($_POST['ticket_id']);
    $systemPrompt['content'] .= "ticket: \n";
    $systemPrompt['content'] .= $ticket->fields['content'] . "\n";
}

array_unshift($messages, $systemPrompt);
$userOpenAiClient = OpenAi::client($api_key);

$result = $userOpenAiClient->chat()->create([
    "model" => "gpt-3.5-turbo",
    "messages" => $messages,
]);

$response = $result->choices[0]->toArray()['message'];

$json_ticket = json_decode($response["content"]);
try {
    if (json_last_error() === JSON_ERROR_NONE) {
        $ticket = new Ticket();
        $ticket->add([
            'add' => '',
            'name' => addslashes($json_ticket->name),
            'content' => addslashes($json_ticket->content),
            'type' => $json_ticket->type,
            'users_id_recipient' => $json_ticket->user_id_assign,
        ]);
        $response['ticket_id'] = $ticket->getID();
        $ticket->update(['type' => $json_ticket->type]);
        $DB->request('
            INSERT INTO glpi_tickets_users
            (tickets_id, users_id, type, use_notification)
            VALUES
            (' . $ticket->getID() . ', ' . $json_ticket->user_id_assign . ', 2, 1)'
        );
        //$response['content'] = "Votre ticket a bien été créé, son numéro est le " . $ticket->getID() . ".";
    }
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode($response);
}
