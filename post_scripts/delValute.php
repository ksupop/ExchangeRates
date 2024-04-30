<?php

include "../db.php";

// Проверяем, что переменная $_POST['ValuteID'] установлена и является числом
if(isset($_POST['ValuteID']) && is_numeric($_POST['ValuteID'])) {
    $uid = $_POST['ValuteID'];

    $stmt = pg_prepare($dbconn, "delete_valute", 'delete from valute where key = $1');
    $result = pg_execute($dbconn, "delete_valute", array($uid));

    if ($result) {
        echo json_encode(array('answer' => "Удалено"));
    } else {
        echo json_encode(array('error' => "Ошибка при удалении"));
    }
} else {
    echo json_encode(array('error' => "Неверные данные"));
}
?>
