<?php
class dbController {
    private $host = 'localhost';
    private $port = '5432';
    private $dbname = 'bus';
    private $user = 'max';
    private $password = '12345';

    // Отправка запроса к БД
    private function sendQuery ($sql) {
        // Подключаемся
        $conn_string = "host={$this->host} port={$this->port} dbname={$this->dbname} user={$this->user} password={$this->password}";

        $conn = pg_connect($conn_string);

        if (!$conn) {
            throw new Exception("Ошибка подключения!");
        }
        
        // Выполнение запроса
        $result = pg_query($conn, $sql);

        if (!$result) {
            $err = "Ошибка выполнения SQL-запроса: " . pg_last_error($conn);
            pg_close($conn);
            throw new Exception($err);
        }

        if(pg_affected_rows($result) < 1) {
            pg_close($conn);
            throw new Exception('Не внесено изменений!');
        }

        $rows = [];
        while ($row = pg_fetch_assoc($result)) {
            $rows[] = $row;
        }
        
        // Закрытие соединения
        pg_close($conn);

        return $rows;
    } 
    // Получаем остановки 
    public function getStops() {
        try {
            $res = $this->sendQuery("SELECT * FROM buses.stops
            ORDER BY stop_name ASC");
            
            foreach ($res as $stop) {
                echo "<option value=\"{$stop['stop_id']}\">Ул. {$stop['stop_name']}</option>";
            }
        } catch (Exception $e) {
            echo 'Ошибка: ' .$e->getMessage();
        }
    }
    // Получаем маршрут автобуса
    public function getRoute($start, $stop) {

        try {
            $res = $this->sendQuery("SELECT (b.bus_name || ' в сторону ост. ' || s.stop_name) AS route,
                                    (
                                        SELECT array_to_json(arr)
                                        FROM (
                                            SELECT ARRAY(
                                                SELECT arrival
                                                FROM UNNEST(r3.arrival) AS arrival
                                                WHERE arrival::time > CURRENT_TIME
                                                ORDER BY arrival::time ASC
                                                LIMIT 3
                                            ) as arr
                                            FROM buses.routes r3
                                            WHERE r3.bus = r.bus AND r3.dir = r1.dir AND r3.stop = $start
                                        )
                                    ) AS next_arrivals
                                    FROM buses.routes r
                                    JOIN buses.routes r1 ON r1.bus = r.bus AND r1.dir = r.dir AND r1.stop = $start
                                    JOIN buses.routes r2 ON r2.bus = r.bus AND r2.dir = r.dir AND r2.stop = $stop
                                    JOIN buses.buses b ON r.bus = b.bus_id
                                    JOIN buses.stops s ON s.stop_id = r.stop
                                    WHERE r1.stop_num < r2.stop_num
                                    AND r.stop_num = (SELECT MAX(r4.stop_num) FROM buses.routes r4 WHERE r4.bus = r.bus AND r4.dir = r.dir)
                                    ORDER BY r.bus");

            if (empty($res)) {
                return 'Нет таких автобусов!';
            }
            
            // Форматируем ответ
            $formattedResult = [];

            foreach ($res as $row) {
                // Преобразуем прибытия в массив
                $arrivals = json_decode($row['next_arrivals']);
                // Проверяем пустой ли массив
                if(count($arrivals) < 1) {
                    $row['next_arrivals'] = "На сегодня больше нет прибытий.";
                } else {
                    $row['next_arrivals'] = $arrivals;
                }
                $formattedResult[] = $row;
            }
            return $formattedResult;
        } catch (Exception $e) {
            return 'Ошибка: ' .$e->getMessage();
        }
    }
    // Получаем все существующие маршруты
    public function getWholeRoutes() {
        try {    
            $res = $this->sendQuery("SELECT json_agg(grouped_data) AS grouped_results
                                    FROM (
                                        SELECT bus, dir,
                                            (SELECT bus_name FROM buses.buses WHERE buses.bus_id = routes.bus LIMIT 1) AS bus_name,
                                            json_agg(
                                                json_build_object(
                                                    'route_id', routes.route_id,
                                                    'stop_num', routes.stop_num,
                                                    'stop', routes.stop,
                                                    'stop_name', stops.stop_name
                                                ) ORDER BY routes.stop_num
                                            ) AS data
                                        FROM buses.routes AS routes
                                        JOIN buses.stops ON routes.stop = stops.stop_id
                                        GROUP BY bus, dir
                                        ORDER BY bus
                                    ) AS grouped_data");

            if (empty($res)) {
                return 'Нет путей!';
            }
            
            // Предполагаем, что получается только json 1-ой строке
            $formattedResult = $res[0]['grouped_results'];

            return $formattedResult;
        } catch (Exception $e) {
            return 'Ошибка: ' .$e->getMessage();
        }
    }
    // Добавляем остановку в маршрут
    public function addStop($bus, $stop, $dir) {
        try {
            // Проверка на колиззии есть внутри запроса
            $res = $this->sendQuery("INSERT INTO buses.routes (bus, stop, dir, stop_num)
                                    SELECT $bus, $stop, '$dir', 
                                    (
                                        SELECT MAX(stop_num)+1 
                                        FROM buses.routes 
                                        WHERE bus = $bus AND dir = '$dir'
                                    ) WHERE NOT EXISTS 
                                    (
                                        SELECT * 
                                        FROM buses.routes
                                        WHERE bus = $bus AND dir = '$dir' AND stop = $stop
                                    )");
            return 'Успех!'; 
        } catch (Exception $e) {
            return 'Ошибка: ' .$e->getMessage();
        }
    }
    // Обновляем порядок станций в маршруте
    public function updStop ($route) {
        try {
            $route = json_decode($route, true);

            if(count($route) < 1) {
                return 'Нет остановок!';
            }
            // Формируем запрос
            $updates = '';
            $ids = '';
            foreach ($route as $index => $id){
                $updates .= "when route_id = $id then $index+1 \n";
                $ids .= "$id,"; 
            }
            // Убираем лишнюю запятую
            $ids = trim($ids, ",");

            $res = $this->sendQuery ("UPDATE buses.routes
                                    SET stop_num = CASE 
                                        $updates
                                        ELSE 9999
                                    END
                                    WHERE route_id IN ($ids)");

            return $res;
        } catch (Exception $e) {
            return 'Ошибка: ' .$e->getMessage();
        }
    }
    // Удаляем остановку из маршрута
    public function delStop ($routeId) {
        try {
            // Можно заменить UPDATE на триггер
            $res = $this->sendQuery("UPDATE buses.routes SET stop_num = stop_num - 1 WHERE bus = 1 and dir = 'forward' 
                                    and stop_num > (select stop_num from buses.routes where route_id = $routeId);
                                    DELETE FROM buses.routes WHERE route_id = $routeId;");
            return 'Успех!'; 
        } catch (Exception $e) {
            return 'Ошибка: ' .$e->getMessage();
        }
    }
}
?>
