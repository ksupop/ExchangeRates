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
				url: 'post_scripts/addValute.php',
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