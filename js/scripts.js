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

function sendMessage(ajaxurl, context, ticket_id = 0) {
    const message = userInput.value;
    userInput.value = '';
    userInput.disabled = true;
    if (message !== '' ) {
        messages.push({role: "user", content: message});
        $("#chatContent").append("<p id='userMessage' class='bg-primary text-white ml-auto mb-1'>" + message + "</p>");
    }
    $("#chatContent").append("<p id='botMessage' class='loading bg-secondary text-white mb-1'>...</p>");
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
            'messages': messages,
            'context': context,
            'ticket_id': ticket_id,
        },
        success: function (data) {
            $("#chatContent").find("p:last").remove();
            $("#chatContent").append("<p id='botMessage' class='bg-secondary text-white mb-1'></p>");
            response = JSON.parse(data);
            messages.push({role: response.role, content: response.content})
            // write the message character by character
            console.log(response);
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

function updateTicketWithPrompt(context, ticket_id, endpoint) {
    content = $("#chatContent").find("p:last")[0].outerText;
    $.ajax({
        type: "POST",
        url: endpoint,
        data: {
            'context': context,
            'ticket_id': ticket_id,
            'content': content,
        },
        success: function (data) {
            window.location.reload();
        },
    });
}