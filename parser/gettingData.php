<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../db.php";
//$CurrentDate = "02/03/2002";
$CurrentDate = date('d/m/Y');

$url="http://www.cbr.ru/scripts/XML_daily.asp?date_req=".$CurrentDate;  //Cсылка страницы для получения данных

$GetData = file_get_contents($url);

if ($GetData !== false) {


	$GetDataConverted = mb_convert_encoding($GetData, 'UTF-8', 'Windows-1251'); // Преобразование кодировки в UTF-8
	//echo $GetDataConverted; // Вывод содержимого страницы с тегами

	//echo "<pre>";
	preg_match("/Date=\"(\d{2})\.(\d{2})\.(\d{4})\"/is", $GetDataConverted, $DateData); //Получение даты со страницы

	$DateDataString = $DateData[1]."/".$DateData[2]."/".$DateData[3]; 

	//echo $DateDataString;
	//echo $CurrentDate;
	
	$needCopy = 0;
	$query="select valdate from valute order by key desc limit 1";
	$lastDate = pg_query($dbconn, $query); // Находим в таблице базы данных последнюю дату
	
	if ($lastDate) {
    $result = pg_fetch_assoc($lastDate); // Значение столбца valdate последней строки
    
    if ($result) {
			if ($result['valdate'] == $DateDataString) {
				$needCopy = 1;
			}
		} else {
			$needCopy = 1;
    }
	
	
	if (($CurrentDate == $DateDataString)&&($needCopy==1)){   /* Если текущая дата соответствует дате на странице, то собираем данные для загрузки в базу данных,
													проверка нужна из-за того, что (date_req) может отсутствовать на сегодняшнее число, тогда делать ничего не нужно, также нужно предусмотреть, чтобы данные, которые уже были загружены за сегодняшнее число, не загрузились заново													*/
		/*  Получение содержимого тегов */
		preg_match_all('/<Valute ID="([^"]+)">/is', $GetDataConverted, $IDData); 
		//print_r($IDData[1]);
		
		$amount = count($IDData[1]); // Получаем количество валют на странице
		
		preg_match_all('#<NumCode>(.*?)</NumCode>#is', $GetDataConverted, $NumCodeData); 
		//print_r($NumCodeData[1]);
		preg_match_all('#<CharCode>(.*?)</CharCode>#is', $GetDataConverted, $CharCodeData);
		//print_r($CharCodeData[1]);
		preg_match_all('#<Nominal>(.*?)</Nominal>#is', $GetDataConverted, $NominalData);
		//print_r($NominalData[1]);
		preg_match_all('#<Name>(.*?)</Name>#is', $GetDataConverted, $NameData);
		//print_r($NameData[1]);
		preg_match_all('#<Value>(.*?)</Value>#is', $GetDataConverted, $ValueData);
		//print_r($ValueData[1]);
		preg_match_all('#<VunitRate>(.*?)</VunitRate>#is', $GetDataConverted, $VunitRateData);
		//print_r($VunitRateData[1]);
		
		for($i = 0; $i < $amount; $i++) {
			$id = $IDData[1][$i];
			$numcode = $NumCodeData[1][$i];
			$charcode = $CharCodeData[1][$i];
			$nominal = $NominalData[1][$i];
			$name = $NameData[1][$i];
			$value = str_replace(',', '.', $ValueData[1][$i]);
			$vunitrate =str_replace(',', '.',$VunitRateData[1][$i]);
			$valdate = DateTime::createFromFormat('d/m/Y', $DateDataString);
			$valdate = $valdate->format('Y-m-d'); // Новый формат даты гггг.мм.дд для занесения в базу данных 
			$query="insert into valute (id, numcode, charcode, nominal, name, value, vunitrate, valdate) values ('$id', '$numcode', '$charcode', '$nominal', '$name', '$value', '$vunitrate', '$valdate')";
			$result = pg_query($dbconn, $query);
		
		}
		
	}
	//echo "</pre>";
	}
} 





?>