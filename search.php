<?

include "db.php";

$name = $_POST['selectedValue'];
$start = $_POST['startDate'];
$end = $_POST['endDate'];
$result = pg_query($dbconn,"select valdate, value from valute where (name = '$name') and (valdate between '$start' and '$end') order by valdate"); 


$data = pg_fetch_all($result);

echo json_encode($data);
?>