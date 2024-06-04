am5.ready(function() {
 // Create root element for the first chart
 var root1 = am5.Root.new("chartdiv");

 // Set themes for the first chart
 root1.setThemes([
   am5themes_Animated.new(root1)
 ]);

 // Create the first chart
 var chart1 = root1.container.children.push(am5xy.XYChart.new(root1, {
   panX: true,
   panY: true,
   wheelX: "panX",
   wheelY: "zoomX",
   pinchZoomX: true,
   paddingLeft: 0,
   paddingRight: 1
 }));

 // Add cursor for the first chart
 var cursor1 = chart1.set("cursor", am5xy.XYCursor.new(root1, {}));
 cursor1.lineY.set("visible", false);

 // Create axes for the first chart
 var xRenderer1 = am5xy.AxisRendererX.new(root1, { 
   minGridDistance: 30, 
   minorGridEnabled: true
 });

 xRenderer1.labels.template.setAll({
   rotation: 0,
   centerY: am5.p50,
   centerX: am5.p50,
   paddingRight: 15
 });

 xRenderer1.grid.template.setAll({
   location: 1
 });

 var xAxis1 = chart1.xAxes.push(am5xy.CategoryAxis.new(root1, {
   maxDeviation: 0.3,
   categoryField: "label",
   renderer: xRenderer1,
   tooltip: am5.Tooltip.new(root1, {})
 }));

 var yRenderer1 = am5xy.AxisRendererY.new(root1, {
   strokeOpacity: 0.1
 });

 var yAxis1 = chart1.yAxes.push(am5xy.ValueAxis.new(root1, {
   maxDeviation: 0.3,
   renderer: yRenderer1
 }));

 // Create series for the first chart
 var series1 = chart1.series.push(am5xy.ColumnSeries.new(root1, {
   name: "Series 1",
   xAxis: xAxis1,
   yAxis: yAxis1,
   valueYField: "value",
   sequencedInterpolation: true,
   categoryXField: "label",
   tooltip: am5.Tooltip.new(root1, {
     labelText: "{valueY}"
   })
 }));

 series1.columns.template.setAll({ 
   cornerRadiusTL: 5, 
   cornerRadiusTR: 5, 
   strokeOpacity: 1,
   fill: am5.color(0x313131),  // Set the fill color to black
   stroke: am5.color(0x313131) // Set the stroke color to black
 });

 // Fetch data from PHP script
 fetch('fetch_dbChartOne.php')
   .then(response => response.json())
   .then(data1 => {
     console.log(data1);
     // Set data for the first chart
     data1 = data1.map(item => ({ ...item, value: parseFloat(item.value) }));
     xAxis1.data.setAll(data1);
     series1.data.setAll(data1);

     // Make stuff animate on load for the first chart
     series1.appear(1000);
     chart1.appear(1000, 100);
   })
   .catch(error => console.error('Error fetching data chart 1:', error));

   var chartTitle = am5.Label.new(root1, {
    text: "Service Association Chart (Conviction)",
    fontSize: 24,
    fontWeight: "700",
    fontFamily: "Montserrat",
    textAlign: "center",
    x: am5.percent(50),
    y: -55,
    centerX: am5.percent(50),
    paddingTop: 52,
    paddingBottom: 20,
    marginTop: 30
});

    chart1.children.push(chartTitle);
    chartTitle.toFront();

      // Create root element for the second chart
      var root2 = am5.Root.new("chartdiv2");
  
      // Set themes for the second chart
      root2.setThemes([
          am5themes_Animated.new(root2)
      ]);
  
      // Create the second chart
      var chart2 = root2.container.children.push(am5xy.XYChart.new(root2, {
          panX: true,
          panY: true,
          wheelX: "panX",
          wheelY: "zoomX",
          pinchZoomX: true,
          paddingLeft: 0,
          paddingRight: 1
      }));
  
      // Add cursor for the second chart
      var cursor2 = chart2.set("cursor", am5xy.XYCursor.new(root2, {}));
      cursor2.lineY.set("visible", false);
  
      // Create axes for the second chart
      var xRenderer2 = am5xy.AxisRendererX.new(root2, { 
          minGridDistance: 30, 
          minorGridEnabled: true
      });
  
      // Customize labels for x-axis
      xRenderer2.labels.template.setAll({
          rotation: 0,
          centerY: am5.p50,
          centerX: am5.p50,
          paddingRight: 15
      });
  
      xRenderer2.grid.template.setAll({
          location: 1
      });
  
      var xAxis2 = chart2.xAxes.push(am5xy.CategoryAxis.new(root2, {
          maxDeviation: 0.3,
          categoryField: "service",
          renderer: xRenderer2,
          tooltip: am5.Tooltip.new(root2, {}),
      }));
  
      var yRenderer2 = am5xy.AxisRendererY.new(root2, {
          strokeOpacity: 0.1
      });
  
      var yAxis2 = chart2.yAxes.push(am5xy.ValueAxis.new(root2, {
          maxDeviation: 0.3,
          renderer: yRenderer2
      }));
  
      // Create series for the second chart
      var series2 = chart2.series.push(am5xy.ColumnSeries.new(root2, {
          name: "Series 2",
          xAxis: xAxis2,
          yAxis: yAxis2,
          valueYField: "count", // Changed to 'count' from 'value'
          sequencedInterpolation: true,
          categoryXField: "service", // Changed to 'service' from 'country'
          tooltip: am5.Tooltip.new(root2, {
              labelText: "{valueY}"
          })
      }));
  
      series2.columns.template.setAll({ 
          cornerRadiusTL: 5, 
          cornerRadiusTR: 5, 
          strokeOpacity: 1,
          fill: am5.color(0x313131),  // Set the fill color to black
          stroke: am5.color(0x313131) // Set the stroke color to black
      });
  
    // Fetch data from PHP script
    fetch('fetch_dbChartTwo.php')
      .then(response => response.json())
      .then(data2 => {
          console.log(data2);
          
          xAxis2.data.setAll(data2);
          series2.data.setAll(data2);


          // Make stuff animate on load for the second chart
          series2.appear(1000);
          chart2.appear(1000, 100);
      })
      .catch(error => console.error('Error fetching data chart 2:', error));

  
      var chartTitle = am5.Label.new(root2, {
          text: "Total Number per Services",
          fontSize: 24,
          fontWeight: "700",
          fontFamily: "Montserrat",
          textAlign: "center",
          x: am5.percent(50),
          y: -55,
          centerX: am5.percent(50),
          paddingTop: 52,
          paddingBottom: 20,
          marginTop: 30
      });
  
      chart2.children.push(chartTitle);
      chartTitle.toFront();
});