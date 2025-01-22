<?php
require_once '../DBcontroller.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = $_POST['start'];
    $stop = $_POST['stop'];
    $startName = $_POST['startName'];
    $stopName = $_POST['stopName'];
    
    if (empty($start) || empty($stop) || empty($startName) || empty($stopName)) {
        echo 'Both starting and ending stops are required.';
        exit;
    }

    $db = new dbController();
    $res = $db->getRoute($start, $stop, $startName, $stopName);
    if(empty($res)) {
        echo 'Такого маршрута не существует!';
        exit;
    }

    $response  = [
        "from" => $startName,
        "to" => $stopName,
        "buses" => $res
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>
