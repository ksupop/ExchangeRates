<?
// Скрипт для заполнения базы данных за определенный период

include "db.php";

$CurrentDate = '28/04/2024'; // Дата, до которой необходимо получить данные

$days = 100; //Количество дней, за которые возьмем данные

for($j=0; $j< $days; $j++){
	$CurrentDateType = DateTime::createFromFormat('d/m/Y', $CurrentDate);
	$CurrentDateType->modify('-1 day'); 
	$CurrentDate = $CurrentDateType->format('d/m/Y');
	//echo $CurrentDate;

	$url="http://www.cbr.ru/scripts/XML_daily.asp?date_req=".$CurrentDate;  //Cсылка страницы для получения данных
	//echo $url;
	$GetData = file_get_contents($url);

	if ($GetData !== false) {
		$GetDataConverted = mb_convert_encoding($GetData, 'UTF-8', 'Windows-1251'); // Преобразование кодировки в UTF-8
		echo $GetDataConverted; // Вывод содержимого страницы с тегами

		echo "<pre>";
		preg_match("/Date=\"(\d{2})\.(\d{2})\.(\d{4})\"/is", $GetDataConverted, $DateData); //Получение даты со страницы

		$DateDataString = $DateData[1]."/".$DateData[2]."/".$DateData[3]; 

		//echo $DateDataString;
		//echo $CurrentDate;
		$CurrentDate = $DateDataString;
		/*//if ($CurrentDate == $DateDataString){    Если текущая дата соответствует дате на странице, то собираем данные для загрузки в базу данных,
														проверка нужна из-за того, что (date_req) может отсутствовать на сегодняшнее число, тогда делать ничего не нужно */
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
			
			for($i=0; $i<$amount; $i++) {
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
			
		//}
		echo "</pre>";
	} 
}
?>