<?

include "db.php";

$id = $_POST['ValID'];
$numcode= $_POST['ValNumcode'];
$charcode= $_POST['ValCharcode'];
$nominal = $_POST['ValNominal'];
$name = $_POST['ValName'];
$value = $_POST['ValValue'];
$vunitrate = $_POST['ValVunitrate'];
$valdate= $_POST['ValDate'];

$addResult = pg_query($dbconn,"insert into valute (id, numcode, charcode, nominal, name, value, vunitrate, valdate) values ('$id', '$numcode', '$charcode', '$nominal', '$name', '$value', '$vunitrate', '$valdate') ") or die ('Ошибка'); 



?>