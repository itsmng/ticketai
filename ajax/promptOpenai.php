<?php

/**
 * ---------------------------------------------------------------------
 * ITSM-NG
 * Copyright (C) 2022 ITSM-NG and contributors.
 *
 * https://www.itsm-ng.org
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

Session::checkRight('plugin_ticketai_ticketai', READ);

if (!Plugin::isPluginActive('ticketai')) {
    echo json_encode(['error' => 'Plugin not active']);
} else if (!isset($_POST['messages'])) {
    echo json_encode(['error' => 'No messages provided']);
}

$config = PluginTicketaiConfig::getConfig();
$api_key = $config['api_key'];
$messages = $_POST['messages'];
$prompt = '';
if (!isset($_POST['context']) || $_POST['context'] == 'helpdesk') {
    $prompt = $config['user_prompt'];
} else {
    $prompt = $config['tech_prompt'];
}

$systemPrompt = [
    'role' => 'system',
    'content' => $prompt . ($messages == 'helpdesk' ? ' ' . PluginTicketaiConfig::USER_FORMAT_PROMPT : '')
];

if ($messages != 'helpdesk' && isset($_POST['ticket_id'])) {
    $ticket = new Ticket();
    $ticket->getFromDB($_POST['ticket_id']);
    $systemPrompt['content'] .= 'ticket name : ' . $ticket->fields['name'] . "\n";
    $systemPrompt['content'] .= 'ticket content : ' . $ticket->fields['content'];
}

array_unshift($messages, $systemPrompt);

$openAiClient = OpenAi::client($api_key);
try {
    $result = $openAiClient->chat()->create([
        'model' => 'gpt-4',
        'messages' => $messages,
    ]);
    echo json_encode($result->choices[0]->message);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

