<?php

include "../db.php";

// Проверка наличия POST параметров
if(isset($_POST['selectedValue'], $_POST['startDate'], $_POST['endDate'])) {
    // Фильтрация ввода данных
    $name = pg_escape_string($_POST['selectedValue']);
    $start = pg_escape_string($_POST['startDate']);
    $end = pg_escape_string($_POST['endDate']);
    
    $query = "SELECT valdate, value FROM valute WHERE name = $1 AND valdate BETWEEN $2 AND $3 ORDER BY valdate";
    $result = pg_query_params($dbconn, $query, array($name, $start, $end));

    if ($result) {
        $data = pg_fetch_all($result);
        echo json_encode($data);
    }
} else {
    die('Отсутствуют необходимые данные');
}

?>
