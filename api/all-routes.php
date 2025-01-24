<?php
require_once './DBcontroller.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $db = new dbController();
    $res = $db->getWholeRoutes();

    echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>