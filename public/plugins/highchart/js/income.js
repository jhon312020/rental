$(document).ready(function() {
  //console.log(x_axis, y_axis);

  monthChart = Highcharts.chart('incomeMonth', {

    title: {
        text: 'Income report for the month of ' + month_year
    },

    /*subtitle: {
        text: 'Source: thesolarfoundation.com'
    },*/

    yAxis: {
        title: {
            text: 'Income'
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
        name: 'Income',
        data: y_axis
    }]

});

yearChart = Highcharts.chart('incomeYear', {

    title: {
        text: 'Income report for the year of ' + year
    },

    /*subtitle: {
        text: 'Source: thesolarfoundation.com'
    },*/

    yAxis: {
        title: {
            text: 'Income amount'
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
        name: 'Income',
        data: yearly_y_axis
    }]

});

  
})