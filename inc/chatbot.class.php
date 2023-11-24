<?php
require_once(Plugin::getPhpDir('ticketai') . '/vendor/autoload.php');

class PluginTicketaiChatbot extends CommonDBTM
{

    static function getChatWindow(string $context, string $init_prompt = '', int $ticket_id = null, string $mode = 'user') {
        $config = PluginTicketaiConfig::getConfig();

        require_once GLPI_ROOT . "/ng/twig.class.php";
        $twig = Twig::load(Plugin::getPhpDir('ticketai') . "/templates", false);
        try {
            echo $twig->render('chatbot.twig', [
                'root' => Plugin::getWebDir('ticketai'),
                'context' => $context,
                'ticket_id' => $ticket_id,
                'endpoint' => $config['endpoint'] . '/api',
                'ajax_endpoint' => Plugin::getWebDir('ticketai') . '/ajax/updateTicket.php',
                'model' => $config[$mode . '_model'],
                'init_prompt' => $init_prompt,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
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
        $config = PluginTicketaiConfig::getConfig();
        self::getChatWindow('helpdesk', $config['user_prompt'] .
            " " . PluginTicketaiConfig::USER_FORMAT_PROMPT);
    }
}
