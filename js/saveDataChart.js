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
