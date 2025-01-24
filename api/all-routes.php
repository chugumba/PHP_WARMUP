<?php
require_once './DBcontroller.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $db = new dbController();
    $res = $db->getWholeRoutes();

    echo $res;
}
?>