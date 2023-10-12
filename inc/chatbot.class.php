<?php
require_once(Plugin::getPhpDir('whitelabel') . '/vendor/autoload.php');

class PluginWhitelabelChatbot extends CommonDBTM
{
    static function getMenuContent()
    {
        $menu = [
            'title' => 'Ticket AI',
            'page' => Plugin::getPhpDir('whitelabel', false) . '/front/chatbot.form.php',
            'icon' => 'fas fa-brain'
        ];

        return $menu;
    }

    static function showForm()
    {
        echo <<<HTML
        <style>
            .ml-auto {
                margin-left: auto;
            }

            #chat {
                height: 100%;
                width: 90%;
                border: 1px solid black;
                border-radius: 10px;
                margin: 0 auto;
                padding: 10px;
            }

            #chatContent {
                height: 500px;
                overflow-y: scroll;
            }

            #userMessage {
                border-radius: 10px 10px 0 10px;
            }
            
            #botMessage {
                border-radius: 10px 10px 10px 0;
            }

            #inputBox {
                height: 2rem;
            }

            #userInput {
                height: 70%;
                width: 100%;
                border-radius: 10px;
                border: 2px solid gray;
            }

            #chatContent p {
                box-sizing: border-box;
                padding: 5px;
                width: 45%;
            }
        </style>
        <div id="chat" class='d-flex flex-column'>
            <div id="chatContent" class="d-flex flex-column py-auto">
                <p id="botMessage" class='bg-secondary text-white mb-1'>Bonjour, quelle est votre demande ?</p>
            </div>
            <div id="inputBox" class="d-flex justify-content-center align-items-center">
                <input type="text" id="userInput" class="mx-auto">
            </div>
        </div>
        <script>
            const userInput = document.getElementById("userInput");
            const botResponse = document.getElementById("botResponse");

            var historic = [];

            userInput.addEventListener("keyup", function (event) {
                if (event.key === "Enter") {
                    //empties and disables the input
                    sendMessage();
                }
            });

            function sendMessage() {
                const message = userInput.value;
                userInput.value = '';
                userInput.disabled = true;
                historic.push(message);
                $("#chatContent").append("<p id='userMessage' class='bg-primary text-white ml-auto mb-1'>" + message + "</p>");
                $("#chatContent").append("<p id='botMessage' class='bg-secondary text-white mb-1'>...</p>");
                prompt = ''
                for(prevMessage of historic) {
                    prompt += prevMessage + "\u000A";
                }
                //while waiting, the three dots animates

                $.ajax({
                    type: "POST",
                    url: "../ajax/prompt.php",
                    data: {
                        'prompt': prompt
                    },
                    success: function (data) {
                        $("#chatContent").find("p:last").remove();
                        $("#chatContent").append("<p id='botMessage' class='bg-secondary text-white'></p>");
                        // write the message character by character
                        var i = 0;
                        response = JSON.parse(data).content;
                        for (const character of response) {
                            setTimeout(function () {
                                $("#chatContent").find("p:last").append(character);
                            }, 10 * i);
                            i++;
                        }
                        userInput.disabled = false;
                    },
                });
            }
        </script>
    HTML;
    }
}
