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

echo json_encode($result->choices[0]->toArray()['message']);