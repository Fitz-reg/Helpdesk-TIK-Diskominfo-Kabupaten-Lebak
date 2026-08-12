<?php
/**
 * REST API Gateway & Common Helpers
 * Helpdesk TIK Diskominfo Kabupaten Lebak
 */

define('IN_SCRIPT', 1);
define('HESK_PATH', dirname(__DIR__) . '/');

require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
require(HESK_PATH . 'inc/customer_accounts.inc.php');

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

hesk_load_database_functions();
hesk_dbConnect();
hesk_session_start('CUSTOMER');

function send_json_response($status = 'success', $message = '', $data = null, $code = 200) {
    http_response_code($code);
    echo json_encode([
        'status'    => $status,
        'message'   => $message,
        'data'      => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit();
}

function get_json_input() {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : $_POST;
}

if (basename($_SERVER['SCRIPT_FILENAME']) === 'index.php') {
    send_json_response('success', 'Helpdesk TIK Diskominfo Lebak REST API Service', [
        'version' => '1.0.0',
        'endpoints' => [
            'AUTH'       => '/api/auth.php',
            'CATEGORIES' => '/api/categories.php',
            'TICKETS'    => '/api/tickets.php',
            'PROFILE'    => '/api/profile.php'
        ]
    ]);
}