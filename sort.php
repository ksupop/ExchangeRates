<?php
include "db.php";

if (isset($_POST['column'])) {
	$cookie_value = $_COOKIE['sort']; //Получаем направление сортировки
    $column = $_POST['column']; // Получаем колонку для сортировки
	setcookie("column",  $column, time() + 3600, "/");
	if ($cookie_value == 'asc'){  //Меняем направление сортировки 
		setcookie("sort", "desc", time() + 3600, "/");
		$cookie_value = 'desc'; 
	} else {
		setcookie("sort", "asc", time() + 3600, "/");
		$cookie_value = 'asc'; 
	}
	
	$list = $_POST['list'];
	$quantity = $_POST['quantity'];	
    $sql = "select * from valute order by $column $cookie_value limit $quantity offset $list ";
    $resultSort = pg_query($dbconn, $sql);

   while ($ValuteRow = pg_fetch_array($resultSort))
		{
			echo "<tr>";
			echo "<td>".$ValuteRow['numcode']."</td>";
			echo "<td>".$ValuteRow['charcode']."</td>";
			echo "<td>".$ValuteRow['nominal']." ".$ValuteRow['name']."</td>";
			echo "<td>".$ValuteRow['value']."</td>";
			echo "<td>".$ValuteRow['valdate']."</td>";
			echo "<td><button id='".$ValuteRow['key']."' onclick='DelValute(this)' class='btn btn-sm btn-light'>Удалить</button></td>";
			echo "</tr>";
		}
}
?>