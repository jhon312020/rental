$(document).ready(function() {
  //console.log(x_axis, y_axis);

  incomeChart = Highcharts.chart('incomeChart', {

    title: {
        text: 'Income and expense report for last 30 days'
    },

    /*subtitle: {
        text: 'Source: thesolarfoundation.com'
    },*/

    yAxis: {
        title: {
            text: 'Income / Expense'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    /*plotOptions: {
        series: {
            pointStart: 2010
        }
    },*/
    xAxis : {
      categories: x_axis
   },
	 tooltip: {
        formatter: function() {
            var s = [];
						s.push('<span>' + this.x + '</span>');
            $.each(this.points, function(i, point) {
                s.push('<span style="font-weight:bold;">'+ point.series.name +' : '+
                    point.y +'<span>');
            });

            return s.join('<br>');
        },
        shared: true
    },

    series: [{
        name: 'Income',
        data: income_y_axis
    },{
        name: 'Expense',
        data: expense_y_axis
    }]

});


  
})