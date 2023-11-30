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

$ticket = new Ticket();
global $DB;

// add messages to the ticket based on the context
switch ($_POST['context']) {
    case 'new':
        $ticket->add([
            'add' => '',
            'name' => $_POST['name'],
            'content' => $_POST['content'],
            'type' => $_POST['type'],
            '_groups_id_assign' => $_POST['group_id_assign'],
        ]);
        break;
        case 'followup':
        $ticket->getFromDB($_POST['ticket_id']);
        $followup = new ITILFollowup();
        $followup->add([
            'itemtype' => 'Ticket',
            'items_id' => $ticket->getID(),
            'content' => $_POST['content'],
            'date' => date('Y-m-d H:i:s'),
            'users_id' => Session::getLoginUserID(),
            'requesttypes_id' => $ticket->fields['requesttypes_id'],
        ]);
        break;
    case 'solution':
        $ticket->getFromDB($_POST['ticket_id']);
        $solution = new ITILSolution();
        $solution->add([
            'itemtype' => 'Ticket',
            'items_id' => $ticket->getID(),
            'content' => $_POST['content'],
            'date' => date('Y-m-d H:i:s'),
            'users_id' => Session::getLoginUserID(),
            'requesttypes_id' => $ticket->fields['requesttypes_id'],
        ]);
        break;

}
echo json_encode([
    'status' => 'success',
    'message' => 'Message added to ticket',
    'ticket_id' => $ticket->getID(),
    'ticket_name' => $ticket->fields['name'],
    'ticket_url' => $ticket->getLinkURL(),
]);
?>