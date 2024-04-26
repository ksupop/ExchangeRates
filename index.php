<?
include "db.php";
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Курсы валют</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" type="text/css" href="/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/site.css"> 
	<!-- Подключение Chartist.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chartist/dist/chartist.min.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartist/dist/chartist.min.js"></script>
</head>
<body>
<?

  $svg_arrow_up = file_get_contents('images/arrow-up.svg');  //svg файлы для отображения направления сортировка
  $svg_arrow_down = file_get_contents('images/arrow-down.svg');


/* Установка Cookie, которая отвечает за направление сортировки */
if (!isset($_COOKIE['sort'])) {
    setcookie("sort", "asc", time() + 3600, "/");
}
/* Установка Cookie, которая отвечает за столбец сортировки */
if (!isset($_COOKIE['column'])) {
    setcookie("column", "key", time() + 3600, "/");
}

if (isset($_COOKIE['sort']) && isset($_COOKIE['column'])) {
    $direction = $_COOKIE['sort'];
    $column = $_COOKIE['column'];
} else {
    // Обработка случая, когда ключи не установлены
    $direction = 'asc';  // Устанавливаем значения по умолчанию
    $column = 'key';
}


?> 
<div class="container site">
<header>
	<div class="container">
        <div class="row nav_head align-items-center">
				<div class="col-8 logo"><a href="/"><h1><img src="/images/exchange_rate.png" alt="Логотип">&nbsp;&nbsp;Курсы валют</h1></a></div>
				<div class="col-4 testing"><span>Система создана для тестового задания</span></div>
        </div>
    </div>
	<div class="row mt-3 mb-3">
    <div class="col text-center">
		<!-- Кнопка-триггер модального окна -->
		<button id="myInput" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
		Добавить валюту
		</button>
		<button id="myInput" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
		Построить график
		</button>
	</div>
</div>
</header>

<!-- Модальное окно -->
<div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Добавление валюты</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
	  <form>
      <div class="modal-body">
		<div class="mb-3">
			<div class="row">
				<div class="col-md-5 mb-1"><label for="ValID" class="col-form-label">ID</label></div>
				<div class="col-md-7 mb-1"><input type="text" class="form-control col-md-10" id="ValID" required></div>
				<div class="col-md-5 mb-1"><label for="NumCode" class="col-form-label ">Цифровой код</label></div>
				<div class="col-md-7 mb-1"><input type="text" class="form-control col-md-10" id="NumCode"></div>
				<div class="col-md-5 mb-1"><label for="CharCode" class="col-form-label ">Буквенный код</label></div>
				<div class="col-md-7 mb-1"><input type="text" class="form-control col-md-10" id="CharCode"></div>
				<div class="col-md-5 mb-1"><label for="Nominal" class="col-form-label ">Номинал</label></div>
				<div class="col-md-7 mb-1"><input type="text" class="form-control col-md-10" id="Nominal"></div>
				<div class="col-md-5 mb-1"><label for="ValName" class="col-form-label ">Валюта</label></div>
				<div class="col-md-7 mb-1"><input type="text" class="form-control col-md-10" id="ValName"></div>
				<div class="col-md-5 mb-1"><label for="ValValue" class="col-form-label ">Курс</label></div>
				<div class="col-md-7 mb-1"><input type="text" class="form-control col-md-10" id="ValValue"></div>
				<div class="col-md-5 mb-1"><label for="VunitRate" class="col-form-label ">VunitRate</label></div>
				<div class="col-md-7 mb-1"><input type="text" class="form-control col-md-10" id="VunitRate"></div>
				<div class="col-md-5 mb-1"><label for="ValDate" class="col-form-label ">Дата</label></div>
				<div class="col-md-7"><input type="date" class="form-control col-md-10" id="ValDate"></div>
			 </div>
          </div>
  
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
        <button id="SaveValute" type="submit" class="btn btn-primary"><i class="fa-solid fa-circle-plus"></i>Сохранить</button>
      </div>
	  </form>
	  
    </div>
  </div>
</div>
<div class="modal fade" id="staticBackdrop2" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel2">Построение графика</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
	  <form>
      <div class="modal-body">
			<div class="container">
		<div class="row">
			<div class="col-12">
			Выберите валюту
			<select id="SelectName" class="form-control input-sm form-select form-select-sm">
				 <? $query2 ="select DISTINCT name, nominal from valute";
					$result2 = pg_query($dbconn, $query2);
				 while ($row = pg_fetch_assoc($result2)) { ?>
				<option value="<?=$row['name'];?>"><?=$row['nominal'];?> <?=$row['name'];?></option>
				 <?}?>
			</select>
			</div>
			<div class="col-6">
			<label for="start">Начальная дата:</label>
			<input class="form-control input-sm" type="date" id="start">
			</div>
			<div class="col-6">
			<label for="end">Конечная дата:</label>
			<input class="form-control input-sm" type="date" id="end">
			</div>
		</div>
	
		<div class="ct-chart" id="chart"></div>
		
		</div>
		
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
        <button id="saveChartBtn" class="btn btn-danger">Сохранить в JSON</button>
      </div>
	  </form>
	  
    </div>
  </div>
</div>

	<table id="ValuteTable" class="table table-bordered table-hover">
	<? 
	if (isset($_GET['page'])){
		$page = $_GET['page'];
	} else $page = 1;
	$quantity = 30; // Устанавливаем количество строк, которые будут выводиться на одной странице
	
	if(!is_numeric($page)){ $page = 1;} // Если значение $page не является числом, то показываем пользователю первую страницу
	
	$limit = 2; // Ограничиваем количество ссылок, которые будут выводиться перед и после текущей страницы
	
	if ($page < 1) { $page = 1;} // Если пользователь вручную поменяет в адресной строке значение $page на ноль, то отправляем его на первую страницу, чтобы избежать ошибки
	
	/* Узнаем количество всех строк в таблице  */
	$result = pg_query($dbconn,'select count(*) from valute;');
	$num0 = pg_fetch_row($result);
	$num = $num0[0];
	//echo $num;
	
	$pages = $num/$quantity; // Вычисляем количество страниц, чтобы знать сколько ссылок выводить
	$pages = ceil($pages); // Округляем число страниц в большую сторону
	//echo $pages;
	// Здесь мы увеличиваем число страниц на единицу чтобы начальное значение было равно единице, а не нулю. Значение $page будет совпадать с цифрой в ссылке, которую будут видеть посетители
	$pages++; 

	if ($page>$pages) {$page = 1;} // Если значение $page больше числа страниц, то выводим первую страницу
						
	if (!isset($list)) { $list = 0;} // Переменная $list указывает с какой записи начинать выводить данные, если это число не определено, то будем выводить с нулевой записи
	$list=--$page*$quantity;
	$result2 = pg_query("select * from valute order by $column $direction limit $quantity offset $list") or die ('Ошибка'); 
	?>
	<thead>
	<tr>
	<th id="numcode"><a onclick="SortTable('numcode')">Цифр. код<?=$svg_arrow_up?><?=$svg_arrow_down?></a></th>
	<th id="charcode"><a onclick="SortTable('charcode')">Букв. код<?=$svg_arrow_up?><?=$svg_arrow_down?></a></th>
	<th id="name"><a onclick="SortTable('name')">Валюта<?=$svg_arrow_up?><?=$svg_arrow_down?></a></th>
	<th id="value"><a onclick="SortTable('value')">Курс<?=$svg_arrow_up?><?=$svg_arrow_down?></a></th>
	<th id="valdate"><a onclick="SortTable('valdate')">Дата<?=$svg_arrow_up?><?=$svg_arrow_down?></a></th>
	</tr>
	</thead>
	<tbody>
	<?
	// Вывод таблицы с данными  


		while ($ValuteRow = pg_fetch_array($result2))
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
	
	 ?>
	 </tbody>
	 
	</table>
	
<div class="pagination justify-content-center">	
<nav aria-label="Page navigation example">
  <ul class="pagination">
	
	<? 

		/* Выводим ссылки "назад" и "на первую страницу"  */
		if ($page>=1) {
			echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page=1">&laquo;</a></li>'; // Значение $page для первой страницы всегда равно единице
			echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$page.'">пред. </a></li>';
		}
		// На данном этапе номер текущей страницы = $page+1
		$this1 = $page+1;
		$start = $this1-$limit; // Узнаем с какой ссылки начинать вывод	
		$end = $this1+$limit; // Узнаем номер последней ссылки для вывода
		// Выводим ссылки на все страницы
		for ($j = 1; $j<$pages; $j++) {
			// Выводим ссылки только в том случае, если их номер больше или равен начальному значению, и меньше или равен конечному значению
			if ($j>=$start && $j<=$end) {
				// Ссылка на текущую страницу выделяется жирным
				if ($j==($page+1)) echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$j.'"> <strong style="color: #FF4A4A">'.$j. 
				'</strong></a></li>';
				// Ссылки на остальные страницы
				else echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$j.'">'.$j.'</a></li>';
			}
		}

		/*  Выводим ссылки "вперед" и "на последнюю страницу" */
		if ($j>$page && ($page+2)<$j) {

			// Чтобы попасть на следующую страницу нужно увеличить $pages на 2
			echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.($page+2).'">след</a></li>';

			// Так как у нас $j = количество страниц + 1, то теперь уменьшаем его на единицу и получаем ссылку на последнюю страницу
			echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.($j-1).'">&raquo; </a></li>';
		}

						
?>
  </ul>
</nav>
</div>

<!--<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script> -->
	<script>
	$(document).ready(function() {
		$('#SaveValute').click(function() {
			var value1 = $('#ValID').val();
			var value2 = $('#NumCode').val();
			var value3 = $('#CharCode').val();
			var value4 = $('#Nominal').val();
			var value5 = $('#ValName').val();
			var value6 = $('#ValValue').val();
			var value7 = $('#VunitRate').val();
			var value8 = $('#ValDate').val();
			$.ajax({
				type: 'POST',
				url: 'addValute.php',
				data: {
					
					ValID: value1,
					ValNumcode: value2,
					ValCharcode: value3,
					ValNominal: value4,
					ValName: value5,
					ValValue: value6,
					ValVunitrate: value7,
					ValDate: value8
				},
				success: function(data) {
					//alert('Данные успешно добавлены в базу данных!');
				}
			});
		});
	});
	</script>
	<script type="text/javascript">
	/* Функция удаления валюты из таблицы */
		function DelValute(obj) {
			var idv = obj.id;
			console.log(idv);
			$.ajax({
					url:"delValute.php",
					type: "POST",
					data: {ValuteID: idv},
					dataType: 'json',
					beforeSend: function(data){
				},
					success: function(data){
						alert(data.answer);
						location.reload(); 
					}
				})
			
		}
	</script>
	<script>
		function getCookie(name) {
			let cookieArr = document.cookie.split("; ");
			for(let i = 0; i < cookieArr.length; i++) {
				let cookiePair = cookieArr[i].split("=");
				if (name == cookiePair[0]) {
					return cookiePair[1];
			}
		}
		return null;
		}
		function SortTable(column) {
			var list = '<?php echo $list; ?>'; 
			var quantity = '<?php echo $quantity; ?>'; 
			$.ajax({
				url: 'sort.php',
				method: 'POST',
				data: { column: column, list: list, quantity: quantity},
				success: function(data) {
					$('#ValuteTable tbody').html(data);
			
				}
			});
			var directionGl = getCookie('sort'); 
			$('th svg').css('display', 'none');
			const thElement = document.getElementById(column);
			// Получаем элемент <svg> внутри элемента <th>
			console.log(directionGl);
			if (directionGl=='asc'){
			const svgElement = thElement.querySelector('svg.bi-arrow-down');
			// Присваиваем элементу <svg> свойство display: inline-block;
				svgElement.style.display = 'inline-block';
			} else {
				const svgElement = thElement.querySelector('svg.bi-arrow-up');
				svgElement.style.display = 'inline-block';
			}
		}
	 </script>
	<script type="text/javascript" src="/bootstrap.bundle.min.js"></script>
	<script>
    function buildChart(dates, values) {
        var data = {
            labels: dates,
            series: [values]
        };
		var minNumDatesToShowAll = 10; 

		var options = {
			axisX: {
				labelInterpolationFnc: function(value, index) {
					if (dates.length < minNumDatesToShowAll) {
						return value; // Выводим каждую дату, если их количество меньше 10
					} else {
						// Если количество дат больше 10, отображаем каждую десятую дату
						if (index % 10 === 0) {
							return value;
						} else {
							return null; // Не выводим остальные даты
						}
					}
				}
			}
		};

        new Chartist.Line('#chart', data, options);
    }

    //var dates = <?php echo json_encode($dates); ?>;
    //var values = <?php echo json_encode($values); ?>;
    //buildChart(dates, values);
	</script>
	
	<script>
	document.getElementById('SelectName').addEventListener('change', function() {
		var selectedValue = this.value;
		//var selectedText = this.options[this.selectedIndex].text;
		var startDate	= document.getElementById('start').value;
		var endDate	= document.getElementById('end').value;
		$.ajax({
			url: 'search.php',
			type: 'POST',
			data: { startDate: startDate, endDate: endDate, selectedValue: selectedValue },
			success: function(response) {
				var responseData = JSON.parse(response);
				myCustomFunction(responseData);
			},
			error: function() {
				console.log('Ошибка при выполнении AJAX запроса');
			}
		});
	});

	
    document.getElementById('start').addEventListener('change', function() {
        //alert('Начальная дата изменена на: ' + this.value);
		
		var startDate = this.value;
		var endDate	= document.getElementById('end').value;
		var selectedValue =  document.getElementById('SelectName').value;
		$.ajax({
			url: 'search.php',
			type: 'POST',
			data: { startDate: startDate, endDate: endDate, selectedValue: selectedValue},
			success: function(response) {
				var responseData = JSON.parse(response);
				myCustomFunction(responseData);
			},
			error: function() {
				console.log('Ошибка при выполнении AJAX запроса');
			}
		});
    });

    document.getElementById('end').addEventListener('change', function() {
        //alert('Конечная дата изменена на: ' + this.value);
		var selectedValue =  document.getElementById('SelectName').value;
		var endDate = this.value;
		var startDate	= document.getElementById('start').value;
		$.ajax({
			url: 'search.php',
			type: 'POST',
			data: { startDate: startDate, endDate: endDate, selectedValue: selectedValue },
			success: function(response) {
				var responseData = JSON.parse(response);
				myCustomFunction(responseData);
			},
			error: function() {
				console.log('Ошибка при выполнении AJAX запроса');
			}
		});
    });
	
	function myCustomFunction(data) {
    
    var allValdates = [];
    var allValues = [];

    data.forEach(function(item) {
        //console.log('Дата: ' + item.valdate);
        //console.log('Значение: ' + item.value);
        allValdates.push(item.valdate);
        allValues.push(item.value);
		values =  allValues;
		dates = allValdates;
    });
		buildChart(allValdates, allValues);
		$('button#saveChartBtn').css('display', 'block');
	}
	
	document.getElementById('saveChartBtn').addEventListener('click', function() {
    var dates1 = dates;
    var values1 = values;

    var chartData = { 
        dates: dates1, 
        values: values1
    };

    var jsonData = JSON.stringify(chartData);
    var blob = new Blob([jsonData], { type: 'application/json' });
    var url = URL.createObjectURL(blob);

    var a = document.createElement('a');
    a.href = url;
    a.download = 'chart_data.json';
    document.body.appendChild(a);
    a.click();

    URL.revokeObjectURL(url);
});

</script>
</body>

</html>

