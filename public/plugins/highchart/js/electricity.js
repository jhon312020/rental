$(document).ready(function() {
  //console.log(x_axis, y_axis);

yearChart = Highcharts.chart('incomeYear', {

    title: {
        text: 'Electricity report for the year of ' + year
    },

    /*subtitle: {
        text: 'Source: thesolarfoundation.com'
    },*/

    yAxis: {
        title: {
            text: 'Electricity bill amount'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    xAxis : {
      categories: x_axis
   },

    series: [{
        name: 'Electricity bill amount',
        data: y_axis
    }]

});

$(document).on('click', '.bill_between_month', function ( event ) {
    var form_data = {  }
    loadAndSave.post()
})
  
})