<?php
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

include("../../../inc/includes.php");
include("../inc/chatbot.class.php");

Session::checkLoginUser();


$chatbot = new PluginTicketaiChatbot();
$ticket = new Ticket();
$ticket->getFromDB($_REQUEST['ticket_id']);

$init_prompt = __("I am technician looking to write a %s for the ticket %s which state:
%s: %s
Could you help me with that ?");
$chatbot->getChatWindow($_REQUEST['context'], $_REQUEST['mode'],
    sprintf($init_prompt, $_REQUEST['context'], $_REQUEST['ticket_id'], $ticket->fields['name'], $ticket->fields['content']),
    $_REQUEST['ticket_id'] ?? null);