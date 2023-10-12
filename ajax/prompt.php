<?php

include('../../../inc/includes.php');
include('../vendor/autoload.php');

global $DB;


if (!isset($_POST['prompt'])) {
    die("No prompt");
}

if (!isset($_SESSION['user_openai_client'])) {
    // Initialize the OpenAI client for the user
    $config = $DB->request("SELECT * FROM glpi_plugin_ticketai_config WHERE id=1")->next();
    $api_key = $config["api_key"];
    $prompt = $config["prompt"];
    $userOpenAiClient = OpenAi::client($api_key);

    // Store the client instance in the session for future use
    $_SESSION['user_openai_client'] = $userOpenAiClient;
} else {
    $userOpenAiClient = $_SESSION['user_openai_client'];
}
$result = $userOpenAiClient->chat()->create([
    "model" => "gpt-4",
    "messages" => [["role" => 'user', "content" => $prompt . "
    - " . $_POST['prompt']]],
]);

echo json_encode($result->choices[0]->toArray()['message']);