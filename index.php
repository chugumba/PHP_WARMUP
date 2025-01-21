<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовое задание (автобусы)</title>
    <link rel="stylesheet" href="./index.css">
</head>
<body>
    <h1>
        <?php
            $host = 'localhost';
            $port = '5432';
            $dbname = 'postgres';
            $user = 'postgres';
            $password = '1234'; 
            // Строка для подключения
            $conn = "pgsql:host=$host;port=$port;dbname=$dbname";

            try {
                // Пробуем подключить
                $pdo = new PDO($conn, $user, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                echo "Подключено!";
            } catch (PDOException $e) {
                // Ошибка подключения
                echo "Ошибка подключения: " . $e->getMessage();
            }
        ?>
    </h1>

    <main>
        <div class="starting-stop">
            <label for="first-stop-select">Посадка</label>
            <select id="first-stop-select" class="form-select">
                <option value="1">Остановка 1</option>
                <option value="2">Остановка 1</option>
                <option value="3">Остановка 1</option>
            </select>
        </div>
        <div class="last-stop">
            <label for="second-stop-select">Конечная</label>
            <select id="second-stop-select" class="form-select">
                <option value="1">Остановка 1</option>
                <option value="2">Остановка 1</option>
                <option value="3">Остановка 1</option>
            </select>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
    crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>