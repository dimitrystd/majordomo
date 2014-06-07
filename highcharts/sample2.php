<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Highstock Example</title>
  <script type="text/javascript" src="./js/jquery-1.7.1.min.js"></script>
  <script type="text/javascript" language="javascript">


    var chart_period = 7; //days
    // TODO : Remove chart_interval
    var chart_interval = 1200; //seconds (interval);

    var dateNow = new Date();
    var startDate = new Date(dateNow.getTime() - chart_period * 24 * 60 * 60 * 1000);
    startDate.setHours(0);

    $(function () {

      url = '/pChart/?p=IPSensor1.temp&op=timed&start=' + startDate.getFullYear() + '/' + (startDate.getMonth() + 1) + '/' + (startDate.getDate()) + '&interval=' + chart_interval;


      $.getJSON(url, function (data) {

        var start = +new Date();
        var new_data = new Array();
        for (var i = 0; i < data.TIMES.length; i++) {
          new_data.push(new Array(new Date(data.TIMES[i] * 1000), parseFloat(data.VALUES[i])));
        }

        // Create the chart
        var chart = new Highcharts.StockChart({
          chart: {
            renderTo: 'container',
            type: 'spline',
            events: {
              load: function (chart) {
                this.setTitle(null, {
                  text: 'Built chart at ' + (new Date() - start) + 'ms'
                });
              }
            },
            zoomType: 'x'
          },

          rangeSelector: {
            buttons: [
              {
                type: 'hour',
                count: 1,
                text: '1h'
              },
              {
                type: 'day',
                count: 1,
                text: '1d'
              },
              {
                type: 'week',
                count: 1,
                text: '1w'
              },
              {
                type: 'month',
                count: 1,
                text: '1m'
              },
              {
                type: 'month',
                count: 6,
                text: '6m'
              },
              {
                type: 'all',
                text: 'All'
              }
            ],
            selected: 1
          },

          yAxis: {
            title: {
              text: 'Temperature (°C)'
            }
          },

          title: {
            text: 'Температура в г. Харькове'
          },
          subtitle: {
            text: 'Built chart at...' // dummy text to reserve space for dynamic subtitle
          },

          series: [
            {
              name: 'Temperature',
              data: new_data,
              tooltip: {
                valueDecimals: 1,
                valueSuffix: '°C'
              }
            }
          ]

        });
      });
    });

  </script>
</head>
<body>
  <script src="./js/highstock.js"></script>
  <script src="./js/modules/exporting.js"></script>

  <div id="container" style="height: 500px; min-width: 500px"></div>
</body>
</html>
