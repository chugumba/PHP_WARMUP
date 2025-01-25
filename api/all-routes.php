<?php
require_once './DBcontroller.php';
// Подключаем контроллер БД
$db = new dbController();

// Получаем текущий URI
$requestUri = $_SERVER['REQUEST_URI'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $res = $db->getWholeRoutes();
    echo $res;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Добавление остановки
    if(strpos($requestUri, '/api/all-routes.php/add') !== false) {
        $bus = $_POST['bus'];
        $stops = $_POST['ids'];
        $dir = $_POST['dir'];
        // Проверяем все ли данные на месте
        if (empty($bus) || empty($stops) || empty($dir)) {
            header("HTTP/1.1 422 Unprocessable Entity");
            echo 'Были переданные не все данные!';
            exit;
        }   
        $res = $db->addStop($bus,$stops,$dir);
        echo $res;
        exit;
    }

    // Обновление пути
    if(strpos($requestUri, '/api/all-routes.php/upd') !== false) {
        $route = $_POST['route'];
        // Проверяем все ли данные на месте
        if (empty($route)) {
            header("HTTP/1.1 422 Unprocessable Entity");
            echo 'Были переданные не все данные!';
            exit;
        }

        $res = $db->updStop($route);
        echo $res;
        exit;
    }

    // Удаление остановки
    if(strpos($requestUri, '/api/all-routes.php/del') !== false) {
        $stops = $_POST['ids'];
        // Проверяем все ли данные на месте
        if (empty($stops)) {
            header("HTTP/1.1 422 Unprocessable Entity");
            echo 'Были переданные не все данные!';
            exit;
        }

        $res = $db->delStop($stops);
        echo $res;
        exit;
    }
}
?>