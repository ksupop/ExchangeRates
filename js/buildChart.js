function buildChart(dates, values) {  //функция построения графика
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
			url: 'getGraphData.php',
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
			url: 'getGraphData.php',
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
			url: 'getGraphData.php',
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