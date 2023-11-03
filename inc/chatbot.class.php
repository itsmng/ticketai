<?php
require_once(Plugin::getPhpDir('ticketai') . '/vendor/autoload.php');

class PluginTicketaiChatbot extends CommonDBTM
{
    static function getMenuContent()
    {
        $menu = [
            'title' => 'Ticket AI',
            'page' => Plugin::getPhpDir('ticketai', false) . '/front/chatbot.form.php',
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

            .loading {
                position: relative;
                overflow: hidden;
            }

            .loading::after {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                transform: translateX(-100%);
                background-image: linear-gradient(
                    70deg,
                    rgba(255, 255, 255, 0) 0,
                    rgba(255, 255, 255, 0.2) 20%,
                    rgba(255, 255, 255, 0.5) 60%,
                    rgba(255, 255, 255, 0)
                );
                animation: shimmer 2s infinite;
                transform: translateX(-100%);
                content: '';
            }
            @keyframes shimmer {
                100% {
                transform: translateX(100%);
                }
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

            var messages = [];

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
                messages.push({role: "user", content: message});
                $("#chatContent").append("<p id='userMessage' class='bg-primary text-white ml-auto mb-1'>" + message + "</p>");
                $("#chatContent").append("<p id='botMessage' class='loading bg-secondary text-white mb-1'>...</p>");

                console.table(messages);
                $.ajax({
                    type: "POST",
                    url: "../ajax/prompt.php",
                    data: {
                        'messages': messages
                    },
                    success: function (data) {
                        $("#chatContent").find("p:last").remove();
                        $("#chatContent").append("<p id='botMessage' class='bg-secondary text-white mb-1'></p>");
                        response = JSON.parse(data);
                        messages.push({role: response.role, content: response.content})
                        // write the message character by character
                        var i = 0;
                        for (const character of response.content) {
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
