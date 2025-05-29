<?php
session_start();

if (!isset($_SESSION['usr_id'])) {

    header('Content-Type: application/json');

    echo json_encode(['status' => 'not_logged_in']);
    exit();
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_destroy();


    header('Content-Type: application/json');

    echo json_encode(['status' => 'expired']);
} else {
    $_SESSION['last_activity'] = time();

    header('Content-Type: application/json');

    echo json_encode(['status' => 'active']);
}
?>