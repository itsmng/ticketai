<?php
require_once(Plugin::getPhpDir('ticketai') . '/vendor/autoload.php');

class PluginTicketaiChatbot extends CommonDBTM
{

    static function getChatWindow(string $context, string $mode = 'user', int $ticket_id = null) {
        require_once GLPI_ROOT . "/ng/twig.class.php";
        
        $config = PluginTicketaiConfig::getConfig();
        $twig_vars = [
            'root' => Plugin::getWebDir('ticketai'),
            'context' => $context,
            'ticket_id' => $ticket_id,
            'connection_type' => $config['connection_type'],
            'ajax_endpoint' => Plugin::getWebDir('ticketai') . '/ajax/updateTicket.php',
        ];
        $twig = Twig::load(Plugin::getPhpDir('ticketai') . "/templates", false);
        try {
            switch ($config['connection_type']) {
                case 'on_premise':
                    $twig_vars['endpoint'] = $config['endpoint'] . '/api';
                    $twig_vars['model'] = $config[$mode . '_model'];
                    break;
                default:
                    $twig_vars['endpoint'] = Plugin::getWebDir('ticketai') . '/ajax/promptOpenai.php';
                    $twig_vars['api_key'] = $config['api_key'];
                    $twig_vars['mode'] = $mode;
                    break;
            } 
            echo $twig->render('chatbot.twig', $twig_vars);
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
