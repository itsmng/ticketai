<?php
require_once(Plugin::getPhpDir('ticketai') . '/vendor/autoload.php');

class PluginTicketaiChatbot extends CommonDBTM
{

    static function getChatWindow(string $context, int $ticket_id = null) {
        require_once GLPI_ROOT . "/ng/twig.class.php";
        $twig = Twig::load(Plugin::getPhpDir('ticketai') . "/templates", false);
        try {
            echo $twig->render('chatbot.twig', [
                'root' => Plugin::getWebDir('ticketai'),
                'context' => $context,
                'ticket_id' => $ticket_id,
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
        self::getChatWindow('helpdesk');
    }
}
