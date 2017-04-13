$(document).ready(function() {

  monthChart = Highcharts.chart('incomeMonth', {

    title: {
        text: 'Expense report for the month of ' + month_year
    },

    /*subtitle: {
        text: 'Source: thesolarfoundation.com'
    },*/

    yAxis: {
        title: {
            text: 'Expense'
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

    series: [{
        name: 'Expense',
        data: y_axis
    }]

});

yearChart = Highcharts.chart('incomeYear', {

    title: {
        text: 'Expense report for the year of ' + year
    },

    /*subtitle: {
        text: 'Source: thesolarfoundation.com'
    },*/

    yAxis: {
        title: {
            text: 'Expense amount'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    xAxis : {
      categories: yearly_x_axis
   },

    series: [{
        name: 'Expense',
        data: yearly_y_axis
    }]

});


  
})