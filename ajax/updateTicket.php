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

if (!isset($_POST['ticket_id']) || !isset($_POST['content'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing parameters'
    ]);
    return;
}

$ticket->getFromDB($_POST['ticket_id']);
$content = $_POST['content'];

// add messages to the ticket based on the context
if ($_POST['context'] == 'followup') {
    $DB->queryOrDie(<<<SQL
    INSERT INTO 'glpi_itilfollowups'
    ('itemtype', 'item_id', 'content', 'date', 'users_id') VALUES
    (
        'Ticket',
        {$_POST['ticket_id']},
        '{$content}',
        NOW(),
        {$_SESSION['glpiID']}
    )
    SQL);
    echo json_encode([
        'status' => 'success',
        'message' => 'Message added to ticket'
    ]);
}
?>