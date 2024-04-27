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
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> 
	<link rel="stylesheet" type="text/css" href="css/site.css"> 
	
	<!-- Подключение Chartist.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chartist/dist/chartist.min.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartist/dist/chartist.min.js"></script>
	<!-- Подключение DataTables -->
	<link href="https://cdn.datatables.net/v/bs5/dt-2.0.5/datatables.min.css" rel="stylesheet">
	<script src="https://cdn.datatables.net/v/bs5/dt-2.0.5/datatables.min.js"></script>
</head>
<body>

<div class="container">
<?
include "header.php";
?> 

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

	<table id="ValuteTable" class="table table-bordered table-hover" style="width: 100%;">
		<?php
		$result2 = pg_query("select * from valute") or die('Ошибка');
		?>
		<thead>
			<tr>
				<th id="numcode">Цифр. код</th>
				<th id="charcode">Букв. код</th>
				<th id="name">Валюта</th>
				<th id="value">Курс</th>
				<th id="valdate">Дата</th>
				<th id="valdate"></th>
			</tr>
		</thead>
		<tbody>
			<?php
			// Вывод таблицы с данными  
			while ($ValuteRow = pg_fetch_array($result2)) {
				echo "<tr>";
				echo "<td>" . $ValuteRow['numcode'] . "</td>";
				echo "<td>" . $ValuteRow['charcode'] . "</td>";
				echo "<td>" . $ValuteRow['nominal'] . " " . $ValuteRow['name'] . "</td>";
				echo "<td>" . $ValuteRow['value'] . "</td>";
				echo "<td>" . $ValuteRow['valdate'] . "</td>";
				echo "<td><button id='".$ValuteRow['key']."' onclick='DelValute(this)' class='btn btn-sm btn-light'>Удалить</button></td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>


	<script type="text/javascript">
	$(document).ready(function() {
		 $('#ValuteTable').DataTable({
		   "paging": true,
		   "pagingType": "full_numbers",
		   "language": {
		   "url": "https://cdn.datatables.net/plug-ins/2.0.5/i18n/ru.json"}
		 });
	   });
	</script>
	<script type="text/javascript">
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
	<script type="text/javascript" src="/bootstrap.bundle.min.js"></script>
	<script type="text/javascript">
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

