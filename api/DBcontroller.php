<?php
class dbController {
    private $host = 'localhost';
    private $port = '5432';
    private $dbname = 'postgres';
    private $user = 'max';
    private $password = '12345';


    function sendQuery ($sql) {
        // Подключаемся
        $conn_string = "host={$this->host} port={$this->port} dbname={$this->dbname} user={$this->user} password={$this->password}";

        $conn = pg_connect($conn_string);

        if (!$conn) {
            echo "Ошибка подключения к базе данных.";
            exit;
        }
        
        // Выполнение запроса
        $result = pg_query($conn, $sql);

        if (!$result) {
            echo "Ошибка выполнения SQL-запроса: " . pg_last_error($conn);
            pg_close($conn);
            exit;
        }

        $rows = [];
        while ($row = pg_fetch_assoc($result)) {
            $rows[] = $row;
        }
        
        // Закрытие соединения
        pg_close($conn);

        return $rows;
    } 

    public function getStops() {
        $res = $this->sendQuery("SELECT * FROM buses.stops
        ORDER BY stop_name ASC");
        
        foreach ($res as $stop) {
            echo "<option value=\"{$stop['stop_id']}\">Ул. {$stop['stop_name']}</option>";
        }
    }
    
    public function getRoute($start, $stop) {
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
    }
}
?>
