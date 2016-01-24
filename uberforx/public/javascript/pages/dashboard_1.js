/*! ========================================================================
 * dashboard.js
 * Page/renders: index.html
 * Plugins used: flot, sparkline
 * ======================================================================== */


var value = [];
var male_new = [];
var female_new = [];
$(function () {
    // Sparkline
    // ================================

    (function () {
        $(".sparklines").sparkline("html", {
            enableTagOptions: true
        });
    })();
    
    // Area Chart - Spline
    // ================================
(function () {
   
 $.ajax({
    type: 'POST',
    url: base_url+'index.php/data_controller/new_users_count',
    data: 'data1=testdata1&data2=testdata2&data3=testdata3',
    cache: false,
     async:false,
    success: function(result) {
      if(result){
        resultObj = eval (result);
         var result = eval(resultObj);
        for (var index in result){
             value[index]=result[index];
        }
      }
    }  
});








         $.plot("#chart-audience", [{
                
                
            label: "Number of new requests",
            color: "#2c3b67",
            data: [
                ["Day 7", value[0]],
                ["Day 6", value[1]],
                ["Day 5", value[2]],
                ["Day 4", value[3]],
                ["Day 3", value[4]],
                ["Day 2", value[5]],
                ["Today", value[6]]
               
            ]
        }], {
            series: {
                lines: {
                    show: false
                },
                splines: {
                    show: true,
                    tension: 0.4,
                    lineWidth: 2,
                    fill: 0.8
                },
                points: {
                    show: true,
                    radius: 4
                }
            },
            grid: {
                borderColor: "rgba(0, 0, 0, 0.05)",
                borderWidth: 1,
                hoverable: true,
                backgroundColor: "transparent"
            },
            tooltip: true,
            tooltipOpts: {
                content: "%x : %y",
                defaultTheme: false
            },
            xaxis: {
                tickColor: "rgba(0, 0, 0, 0.05)",
                mode: "categories"
            },
            yaxis: {
                tickColor: "rgba(0, 0, 0, 0.05)"
            },
            shadowSize: 0
        });
    })();
});