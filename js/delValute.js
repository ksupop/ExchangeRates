/* Функция удаления валюты из таблицы */
		function DelValute(obj) {
			var idv = obj.id;
			console.log(idv);
			$.ajax({
					url:"post_scripts/delValute.php",
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