<?php
require_once(__DIR__ . '/index.php');

$res = hesk_dbQuery("SELECT `id`, `name`, `cat_order` FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "categories` WHERE `type` = '0' ORDER BY `cat_order` ASC");

$categories = [];
while ($row = hesk_dbFetchAssoc($res)) {
    $categories[] = [
        'id'   => intval($row['id']),
        'name' => $row['name'],
        'order' => intval($row['cat_order'])
    ];
}

send_json_response('success', 'Daftar Kategori Layanan TIK', [
    'total'      => count($categories),
    'categories' => $categories
]);