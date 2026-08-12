<?php
require_once(__DIR__ . '/index.php');

if (!isset($_SESSION['customer']['id'])) {
    send_json_response('error', 'Anda harus login untuk mengakses profil', null, 401);
}

$cust_id = intval($_SESSION['customer']['id']);
$action = hesk_REQUEST('action', hesk_GET('action', 'get'));

if ($action === 'get') {
    $res = hesk_dbQuery("SELECT `id`, `name`, `email`, `verified`, `created_at` FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "customers` WHERE `id` = {$cust_id} LIMIT 1");
    if ($customer = hesk_dbFetchAssoc($res)) {
        send_json_response('success', 'Data Profil Pengguna', [
            'customer' => $customer
        ]);
    }
    send_json_response('error', 'Pengguna tidak ditemukan', null, 404);
}

elseif ($action === 'update') {
    $input = get_json_input();
    $name = trim($input['name'] ?? '');

    if (empty($name)) {
        send_json_response('error', 'Nama lengkap tidak boleh kosong', null, 400);
    }

    hesk_dbQuery("UPDATE `" . hesk_dbEscape($hesk_settings['db_pfix']) . "customers` SET `name` = '" . hesk_dbEscape($name) . "' WHERE `id` = {$cust_id}");
    $_SESSION['customer']['name'] = $name;

    send_json_response('success', 'Profil berhasil diperbarui', [
        'customer' => $_SESSION['customer']
    ]);
}

send_json_response('error', 'Aksi profil tidak valid', null, 400);