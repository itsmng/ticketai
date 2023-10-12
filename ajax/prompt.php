<?php

include('../../../inc/includes.php');
include('../vendor/autoload.php');

global $DB;


if (!isset($_POST['messages'])) {
    die(json_encode(['message' => 'No messages provided', 'error' => true, 'code' => 400]));
}

$messages = $_POST['messages'];

// Initialize the OpenAI client for the user
$config = $DB->request("SELECT * FROM glpi_plugin_ticketai_config WHERE id=1")->next();
$api_key = $config["api_key"];
$prompt = $config["prompt"];

array_unshift($messages, ['role' => 'user', 'content' => $prompt]);

$userOpenAiClient = OpenAi::client($api_key);

$result = $userOpenAiClient->chat()->create([
    "model" => "gpt-4",
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
        ]);
        $response['ticket_id'] = $ticket->getID();
        $ticket->update(['type' => $json_ticket->type]);
        echo json_encode($response);
    } else {
        echo json_encode($response);
    }
} catch (Exception $e) {
    echo json_encode($response);
}
