<?php
require_once(__DIR__ . '/index.php');

$action = hesk_REQUEST('action', hesk_GET('action', 'list'));

if ($action === 'list') {
    if (!isset($_SESSION['customer']['id'])) {
        send_json_response('error', 'Anda harus login untuk melihat daftar tiket', null, 401);
    }

    $cust_id = intval($_SESSION['customer']['id']);
    $query = "SELECT t.`id`, t.`trackid`, t.`name`, t.`subject`, t.`status`, t.`priority`, t.`category`, t.`lastchange`, t.`dt` 
              FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "tickets` t
              INNER JOIN `" . hesk_dbEscape($hesk_settings['db_pfix']) . "ticket_to_customer` tc ON t.`id` = tc.`ticket_id`
              WHERE tc.`customer_id` = {$cust_id}
              ORDER BY t.`lastchange` DESC";

    $res = hesk_dbQuery($query);
    $tickets = [];
    while ($row = hesk_dbFetchAssoc($res)) {
        $cat_name = isset($hesk_settings['categories'][$row['category']]) ? $hesk_settings['categories'][$row['category']] : 'Layanan TIK';
        $priority_name = isset($hesk_settings['priorities'][$row['priority']]['name']) ? $hesk_settings['priorities'][$row['priority']]['name'] : 'Medium';
        
        $tickets[] = [
            'id'            => intval($row['id']),
            'trackid'       => $row['trackid'],
            'subject'       => $row['subject'],
            'category_id'   => intval($row['category']),
            'category_name' => $cat_name,
            'status'        => strip_tags($row['status']),
            'priority'      => $priority_name,
            'created_at'    => $row['dt'],
            'updated_at'    => $row['lastchange']
        ];
    }

    send_json_response('success', 'Daftar Tiket Permohonan Saya', [
        'total'   => count($tickets),
        'tickets' => $tickets
    ]);
}

elseif ($action === 'detail') {
    $trackid = trim(hesk_REQUEST('track', hesk_GET('track', '')));
    if (empty($trackid)) {
        send_json_response('error', 'Parameter trackid (nomor tiket) diperlukan', null, 400);
    }

    $res = hesk_dbQuery("SELECT * FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "tickets` WHERE `trackid` = '" . hesk_dbEscape($trackid) . "' LIMIT 1");
    if ($ticket = hesk_dbFetchAssoc($res)) {
        // Fetch replies
        $r_res = hesk_dbQuery("SELECT * FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "replies` WHERE `replyto` = " . intval($ticket['id']) . " ORDER BY `id` ASC");
        $replies = [];
        while ($reply = hesk_dbFetchAssoc($r_res)) {
            $replies[] = [
                'id'         => intval($reply['id']),
                'name'       => $reply['name'],
                'message'    => $reply['message'],
                'created_at' => $reply['dt'],
                'staff_id'   => intval($reply['staffid'])
            ];
        }

        $ticket['replies_list'] = $replies;
        send_json_response('success', 'Detail Tiket', [
            'ticket' => $ticket
        ]);
    }

    send_json_response('error', 'Tiket tidak ditemukan', null, 444);
}

send_json_response('error', 'Aksi tiket tidak valid', null, 400);