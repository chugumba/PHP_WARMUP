<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовое задание (автобусы)</title>
    <link rel="stylesheet" href="./index.css">
</head>
<body>
    <?php
        // Подключаем файл с контроллером
        require_once './api/DBcontroller.php';
        $db = new dbController();
    ?>
    <main>
        <div class="starting-stop">
            <label for="first-stop-select">Посадка</label>
            <select name="first-stop" id="first-stop-select" class="form-select stop-selectors">
                <?php $db->getStops();?>
            </select>
        </div>
        <div class="last-stop">
            <label for="second-stop-select">Конечная</label>
            <select name="second-stop" id="second-stop-select" class="form-select stop-selectors">
                <?php $db->getStops();?>
            </select>
        </div>
        <div class="send-btn-container">
            <button id="send-btn" type="button" class="btn btn-primary">Проложить маршрут</button>
        </div>
        <pre class="result-container">
        </pre>
    </main>
    <!-- Подключаем JS библиотеки -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
    crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="./index.js"></script> 
</body>
</html>