// first chart
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
      rotation: -90,
      centerY: am5.p50,
      centerX: am5.p100,
      paddingRight: 15
    });
  
    xRenderer1.grid.template.setAll({
      location: 1
    });
  
    var xAxis1 = chart1.xAxes.push(am5xy.CategoryAxis.new(root1, {
      maxDeviation: 0.3,
      categoryField: "country",
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
      categoryXField: "country",
      tooltip: am5.Tooltip.new(root1, {
        labelText: "{valueY}"
      })
    }));
  
    series1.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
    series1.columns.template.adapters.add("fill", function (fill, target) {
      return chart1.get("colors").getIndex(series1.columns.indexOf(target));
    });
  
    series1.columns.template.adapters.add("stroke", function (stroke, target) {
      return chart1.get("colors").getIndex(series1.columns.indexOf(target));
    });
  
    // Set data for the first chart
    var data1 = [{
        country: "USA",
        value: 2025
      }, {
        country: "China",
        value: 1882
      }, {
        country: "Japan",
        value: 1809
      }, {
        country: "Germany",
        value: 1322
      }, {
        country: "UK",
        value: 1122
      }, {
        country: "France",
        value: 1114
      }, {
        country: "India",
        value: 984
      }, {
        country: "Spain",
        value: 711
      }, {
        country: "Netherlands",
        value: 665
      }, {
        country: "South Korea",
        value: 443
      }, {
        country: "Canada",
        value: 441
      }];
  
    xAxis1.data.setAll(data1);
    series1.data.setAll(data1);
  
    // Make stuff animate on load for the first chart
    series1.appear(1000);
    chart1.appear(1000, 100);
  
  
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
  
    xRenderer2.labels.template.setAll({
      rotation: -90,
      centerY: am5.p50,
      centerX: am5.p100,
      paddingRight: 15
    });
  
    xRenderer2.grid.template.setAll({
      location: 1
    });
  
    var xAxis2 = chart2.xAxes.push(am5xy.CategoryAxis.new(root2, {
      maxDeviation: 0.3,
      categoryField: "country",
      renderer: xRenderer2,
      tooltip: am5.Tooltip.new(root2, {})
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
      name: "Series 1",
      xAxis: xAxis2,
      yAxis: yAxis2,
      valueYField: "value",
      sequencedInterpolation: true,
      categoryXField: "country",
      tooltip: am5.Tooltip.new(root2, {
        labelText: "{valueY}"
      })
    }));
  
    series2.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
    series2.columns.template.adapters.add("fill", function (fill, target) {
      return chart2.get("colors").getIndex(series2.columns.indexOf(target));
    });
  
    series2.columns.template.adapters.add("stroke", function (stroke, target) {
      return chart2.get("colors").getIndex(series2.columns.indexOf(target));
    });
  
    // Set data for the second chart
    var data2 = [{
        country: "USA",
        value: 2025
      }, {
        country: "China",
        value: 1882
      }, {
        country: "Japan",
        value: 1809
      }, {
        country: "Germany",
        value: 1322
      }, {
        country: "UK",
        value: 1122
      }, {
        country: "France",
        value: 1114
      }, {
        country: "India",
        value: 984
      }, {
        country: "Spain",
        value: 711
      }, {
        country: "Netherlands",
        value: 665
      }, {
        country: "South Korea",
        value: 443
      }, {
        country: "Canada",
        value: 441
      }];
  
    xAxis2.data.setAll(data2);
    series2.data.setAll(data2);
  
    // Make stuff animate on load for the second chart
    series2.appear(1000);
    chart2.appear(1000, 100);
  
  }); // end am5.ready()
  
  