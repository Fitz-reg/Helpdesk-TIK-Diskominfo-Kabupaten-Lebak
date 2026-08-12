<?php
require_once(__DIR__ . '/index.php');

$action = hesk_REQUEST('action', hesk_GET('action', 'check'));

if ($action === 'login') {
    $input = get_json_input();
    $email = trim($input['email'] ?? '');
    $password = trim($input['password'] ?? '');

    if (empty($email) || empty($password)) {
        send_json_response('error', 'Email dan password tidak boleh kosong', null, 400);
    }

    $res = hesk_dbQuery("SELECT * FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "customers` WHERE `email` = '" . hesk_dbEscape($email) . "' LIMIT 1");
    if ($customer = hesk_dbFetchAssoc($res)) {
        if (password_verify($password, $customer['pass'])) {
            $_SESSION['customer'] = [
                'id'       => $customer['id'],
                'name'     => $customer['name'],
                'email'    => $customer['email'],
                'verified' => $customer['verified']
            ];
            send_json_response('success', 'Login berhasil', [
                'customer' => $_SESSION['customer']
            ]);
        }
    }
    send_json_response('error', 'Email atau password salah', null, 401);
}

elseif ($action === 'logout') {
    unset($_SESSION['customer']);
    send_json_response('success', 'Logout berhasil');
}

elseif ($action === 'check') {
    if (isset($_SESSION['customer']) && is_array($_SESSION['customer'])) {
        send_json_response('success', 'Sesi aktif', [
            'is_logged_in' => true,
            'customer'     => $_SESSION['customer']
        ]);
    } else {
        send_json_response('success', 'Sesi tidak aktif', [
            'is_logged_in' => false,
            'customer'     => null
        ]);
    }
}

send_json_response('error', 'Aksi auth tidak valid', null, 400);