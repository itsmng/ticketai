<?php

require_once(Plugin::getPhpDir('ticketai') . '/vendor/autoload.php');

class PluginTicketaiChatbot extends CommonDBTM
{

    static function getChatWindow(string $context, string $mode = 'user', string $initPrompt = 'Bonjour', int $ticket_id = null) {
        $config = PluginTicketaiConfig::getConfig();
        $ajax_endpoint = Plugin::getWebDir('ticketai') . '/ajax/updateTicket.php';
        switch ($config['connection_type']) {
            case 'on_premise':
                $endpoint = $config['endpoint'] . '/api';
                $model = $config[$mode . '_model'];
                break;
            default:
                $endpoint = Plugin::getWebDir('ticketai') . '/ajax/promptOpenai.php';
                break;
        } 
?>
<link rel="stylesheet" href="<?php echo Plugin::getWebDir('ticketai') ?>/css/style.css">
<div id="chat" class="tab_cadre_fixe" style="display: flex; flex-direction: column; height: 100%">
    <div id="chatContent">
    </div>
    <div id="inputBox" style="width:100%;display:flex;flex-direction:row;justify-content:center;align-items:center">
        <input type="text" id="userInput" style="flex-grow:1;padding:5px;border-radius:5px;border:1px solid #ccc;">
        <button onclick="sendNewMessage()"
            style="
            padding: 5px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer
            "
        >
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>
<script src="<?php echo Plugin::getWebDir('ticketai') ?>/js/scripts.js"></script>
<script>
    function sendNewMessage() {
        sendMessage(
            '<?php echo $config['connection_type']?>',
            '<?php echo $ajax_endpoint ?>',
            context,
            $('#userInput').val(),
            '<?php echo Session::getNewCSRFToken() ?>',
            '<?php echo $endpoint ?? "" ?>',
            '<?php echo $model ?? "" ?>', true)
            .then(data => {
                context = data.context;
                const jsonregex = /\{.*\}/;
                const match = data.response.match(jsonregex);
                if (match) {
                    const json = JSON.parse(match);
                    var message = ticketCreatedMessage
                        .replace('%url%', json.ticket_url)
                        .replace('%id%', json.ticket_id)
                        .replace('%name%', json.ticket_name);
                    addMessageToChat(message);
                } else {
                    addMessageToChat(data.response)
                }
            });
    }
    const ticketCreatedMessage = '<?php __("I have created the ticket with the id <a class='text-light' href='%url%'>%id%: %name%</a>") ?>'
    context = []
    sendMessage('<?php echo $config['connection_type'] ?>',
        '<?pgp echo $ajax_endpoint ?>',
        context,
        `<?php echo $initPrompt ?>`,
        '<?php echo Session::getNewCSRFToken() ?>',
        '<?php echo $endpoint ?? "" ?>',
        '<?php echo $model ?? "" ?>', false)
        .then(data => {
            context = data.context;
            addMessageToChat(data.response)
        }
    );
    $('#userInput').on("keyup", function (event) {
        if (event.key === "Enter") {
            sendNewMessage();
        }
    });
</script>
<?php
    }

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
        self::getChatWindow('helpdesk');
    }
}
