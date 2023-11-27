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
function viewChatbot(id, rand, url) {
    $('#viewitem' + id + rand).load(url);
    setTimeout(function () { grid.resizeToContent($('#ContentForTabs > div').first()[0]); }, 100);

}

function sendMessage(mode, ajax_endpoint, context, prompt, endpoint = '', model = '', displayUser = true) {
    userInput.value = '';
    userInput.disabled = true;
    if (prompt !== '' && displayUser) {
        $("#chatContent").append("<p class='bg-primary text-white ml-auto mb-1 userMessage'>" + prompt + "</p>");
    }
    const unescapedPrompt = prompt.replace(/\\'/g, "'");
    $("#chatContent").append("<p class='loading bg-secondary text-white mb-1 botMessage'>...</p>");
    chatContent = document.getElementById("chatContent");
    chatContent.scrollTop = chatContent.scrollHeight;
    switch (mode) {
        case 'on_premise':
            return fetch(endpoint + '/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ model, prompt: unescapedPrompt, context, stream: false })
            }).then(response => response.json()).then(data => {
                data.response = extractJsonForTicket(data.response);
                addMessageToChat(data.response);
                return data.context;
            });
            break;
        default:
            context.push({role: 'user', 'content': unescapedPrompt});
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "POST",
                    url: endpoint,
                    data: { messages: context },
                    success: function (data) {
                        jsonData = JSON.parse(data);
                        jsonData = extractJsonForTicket(jsonData.content);
                        addMessageToChat(jsonData);
                        context.push({role: 'assistant', 'content': jsonData})
                        // Resolve the Promise with the updated context
                        resolve(context);
                    },
                    error: function (error) {
                        // Reject the Promise with an error
                        reject(error);
                    }
                });
            });
    }
}

function addMessageToChat(data) {
    $("#chatContent").find("p:last").remove();
    $("#chatContent").append("<p class='bg-secondary text-white mb-1 botMessage'></p>");
    // add response character by character to botMessage while scrolling to bottom
    for (let i = 0; i < data.length; i++) {
        setTimeout(function (index) {
            $("#chatContent").find("p:last").append(data[index]);
            chatContent = document.getElementById("chatContent");
            chatContent.scrollTop = chatContent.scrollHeight;
        }, i * 10, i);
    }
    userInput.disabled = false;
}

function extractJsonForTicket(message) {
    const jsonregex = /\{.*\}/;
    const match = message.match(jsonregex);
    
    if (match) {
        message = message.replace(jsonregex, "");
        const jsonString = match[0]
            .replace(/\\/g, "");
        try {
            body = JSON.parse(jsonString);
            body.context = "new";
            updateTicketWithPrompt(ajax_endpoint, body);
        } catch (e) {
            console.log(e);
        }
    }
    return message;
}

function updateTicketWithPrompt(endpoint, body) {
    console.log(body);
    $.ajax({
        type: "POST",
        url: endpoint,
        data: body,
        success: function (data) {
        },
    });
}