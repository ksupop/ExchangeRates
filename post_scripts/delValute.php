<?

include "../db.php";

$uid = $_POST['ValuteID'];

$DeleteResult = pg_query($dbconn,'delete from valute where key = '.$uid.'') or die ('Ошибка'); 

echo json_encode(array('answer'=>"Удалено"));
?>