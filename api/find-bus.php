<?php
require_once '../DBcontroller.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = $_POST['start'];
    $stop = $_POST['stop'];
    
    if (empty($start) || empty($stop)) {
        echo 'Both starting and ending stops are required.';
        exit;
    }

    $db = new dbController();
    $res = $db->getRoute($start, $stop);

    echo $res;
}
?>
