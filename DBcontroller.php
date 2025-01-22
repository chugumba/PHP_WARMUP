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
    
    public function getRoute($start, $stop, $startName, $stopName) {
        $res = $this->sendQuery("SELECT distinct r.bus, b.bus_name, s.stop_name as \"From\", s1.stop_name as \"To\", r1.dir, 
                                (SELECT ARRAY(
                                    SELECT arrival
                                    FROM UNNEST(r3.arrival) AS arrival
                                    WHERE arrival::time > CURRENT_TIME
                                    ORDER BY arrival::time ASC
                                    LIMIT 3
                                )
                                FROM buses.routes r3 
                                WHERE r3.bus = r.bus AND r3.dir = r.dir AND r3.stop = $stop
                                ) AS next_arrivals


                                FROM buses.routes r
                                join buses.routes r1 on r1.bus = r.bus and r1.dir=r.dir and r1.stop = $stop
                                join buses.routes r2 on r2.bus = r.bus and r2.dir=r.dir and r2.stop = $start
                                join buses.buses b on r.bus = b.bus_id
                                join buses.stops s on s.stop_id = r1.stop
                                join buses.stops s1 on s1.stop_id = r2.stop
                                where r1.stop_num < r2.stop_num");

        $formattedResult = [];
        foreach ($res as $row) {
            $formattedResult[] = $row;
        }

        // Возвращаем JSON
        return json_encode($formattedResult, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
?>
