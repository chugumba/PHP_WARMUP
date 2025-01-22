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
            echo "<option value=\"{$stop['stop_id']}\">{$stop['stop_name']}</option>";
        }
    }
    
    public function getRoute($start, $stop) {
        $res = $this->sendQuery("SELECT distinct r.bus, b.bus_name
                        FROM buses.routes r
                        join buses.routes r1 on r1.bus = r.bus and r1.dir=r.dir and r1.stop = $start
                        join buses.routes r2 on r2.bus = r.bus and r2.dir=r.dir and r2.stop = $stop
                        join buses.buses b on r.bus = b.bus_id
                        where r1.stop_num < r2.stop_num");
        
        return json_encode($res, JSON_UNESCAPED_UNICODE);   
    }
}
?>
