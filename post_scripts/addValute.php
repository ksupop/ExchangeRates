<?php

include "../db.php";

if(isset($_POST['ValID'], $_POST['ValNumcode'], $_POST['ValCharcode'], $_POST['ValNominal'], $_POST['ValName'], $_POST['ValValue'], $_POST['ValVunitrate'], $_POST['ValDate'])) {
    $id = pg_escape_string($_POST['ValID']);
    $numcode = pg_escape_string($_POST['ValNumcode']);
    $charcode = pg_escape_string($_POST['ValCharcode']);
    $nominal = pg_escape_string($_POST['ValNominal']);
    $name = pg_escape_string($_POST['ValName']);
    $value = pg_escape_string($_POST['ValValue']);
    $vunitrate = pg_escape_string($_POST['ValVunitrate']);
    $valdate = pg_escape_string($_POST['ValDate']);
    
    $query = "INSERT INTO valute (id, numcode, charcode, nominal, name, value, vunitrate, valdate) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
    $params = array($id, $numcode, $charcode, $nominal, $name, $value, $vunitrate, $valdate);
    
    $addResult = pg_query_params($dbconn, $query, $params) or die ('Ошибка');
} else {
    die('Отсутствуют необходимые данные');
}

?>

