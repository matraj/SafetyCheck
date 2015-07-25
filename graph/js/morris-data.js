// <!-- Data Base Connection Authentication -->
// <?php
//     // Lagrange Methods
//     function L($i, $x){
//         $v = array(0.4, 1.0, 1.8, 2.6, 4.0, 6.0, 8.0, 10.0);
//         $result = 1;
//         for($j = 0; $j< 8; $j++){
//             if($j != $i){
//                 $result = $result * (($x-$v[$j])/($v[$i]-$v[$j]));
//             }
//         }
//         //echo "result: {$result}";
//         return $result;
//     }
//     function ppm($x){
//         $p = array(0.4, 1.0, 1.8, 2.6, 4.0, 6.0, 8.0, 10.0);
//         $y = 0;
//         for($i = 0; $i < 8; $i++){
//             $y = $y + $p[$i] * L($i,$x);
//         }
//         return $y;
//     }

//     $conn = new mysqli('107.180.4.71', 'datareciever', 'Password123', 'H2S_SENSOR_DB2', '3306');
//     $conn->query('SET NAMES "utf8"');

//     $query = <<<SQL
//     SELECT * FROM H2S_SENSOR_DB2.SensorData ORDER BY ID DESC LIMIT 1;
// SQL;
//     //echo "query:{$query}"; // TEST
//     $sensorValue;
//     $result = $conn->query($query);
//     if ($conn->errno != 0)
//         die('mysql error:'.$cann->error);

//     //$sensorValueArray = array();
//     while (($row = $result->fetch_assoc()) != null)
//     {
//         //echo "<p>"; // TEST
        
//         // echo "Row:{$row}";
//         //echo "result:{$result}";
//         //echo $row['SensorValue']; // TEST
//         $sensorValue = (double)$row['SensorValue'];
//         $ppmValue = ppm($sensorValue/1000);

//        // array_push($sensorValueArray, $row['ID']);//$sensorValueArray = $row['ID']; // Populate Array

//         //$sensorDate = date('h:i a', strtotime($row['ValueTimestamp']));//date_format($row['ValueTimestamp'], 'h:i a');
//         //echo "Time: {$sensorDate}"; // TEST
//         //echo "Concentration: {$ppmValue}"; // TEST
//         //var_dump($sensorValueArray);
//         //echo "ID'S: {$sensorValueArray}";
//         //echo "</p>"; // TEST
//     }

//     $result->close();
// ?>

function callEvery5Sec(){

            var sensorValues = new Array();
            var xmlhttp;
            if (window.XMLHttpRequest)
              {// code for IE7+, Firefox, Chrome, Opera, Safari
              xmlhttp=new XMLHttpRequest();
              }
            else
              {// code for IE6, IE5
              xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
              }
            xmlhttp.onreadystatechange=function()
              {
              if ((xmlhttp.readyState==4) && (xmlhttp.status==200))
                {
                    var data = xmlhttp.responseText;
                    data = data.substring(0, data.length - 1);
                    var sensor = data.split(",");
                    for(var i = 0; i < sensor.length; i += 3)
                    {
                        // alert(sensor[i]);
                        sensorValues[i] = sensor[i];
                    }
                }
              }
            xmlhttp.open("GET","361.php",true);
            xmlhttp.send();
            
            return sensorValues;
            //setTimeout(callEvery5Sec, 5000);
            
}   

$(function() {

    var sensorValues = new Array();
    // sensorValues = setInterval(function() { callEvery5Sec() }, 10000);
    sensorValues =  callEvery5Sec();
    setTimeout(callEvery5Sec, 15000);
    // sensorValues = sensorValues.substring(0, sensorValues.length - 1);
    // var dataPoints = sensorValues.split(",");
    // alert(String(sensorValues[0]));

    var dataPoint = 23;//parseInt(sensorValues[0]);

    Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2010 Q1',
            iphone: 86,
            ipad: null,
            itouch: 2
        }, {
            period: '2010 Q2',
            iphone: 78,
            ipad: 22,
            itouch: 9
        }, {
            period: '2010 Q3',
            iphone: 75,
            ipad: 19,
            itouch: 8
        }, {
            period: '2010 Q4',
            iphone: 89,
            ipad: 35,
            itouch: 5
        }, {
            period: '2011 Q1',
            iphone: 88,
            ipad: 19,
            itouch: 2
        }, {
            period: '2011 Q2',
            iphone: 77,
            ipad: 42,
            itouch: 8
        }, {
            period: '2011 Q3',
            iphone: 65,
            ipad: 37,
            itouch: 15
        }, {
            period: '2011 Q4',
            iphone: 90,
            ipad: 5,
            itouch: 1
        }, {
            period: '2012 Q1',
            iphone: 98,
            ipad: 4,
            itouch: 2
        }, {
            period: '2012 Q2',
            iphone: 74,
            ipad: 13,
            itouch: 7
        }],
        xkey: 'period',
        ykeys: ['itouch', 'ipad', 'iphone'],
        labels: ['H2S', 'Alcohol', 'Oxygen'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });

    Morris.Donut({
        element: 'morris-donut-chart',
        data: [{
            label: "H2S Levels (ppm)",
            value: 0.4
        }, {
            label: "Oxygen Levels (ppm)",
            value: 80
        }, {
            label: "Alcohol Levels (ppm)",
            value: dataPoint
        }],
        resize: true
    });

    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            y: '2006',
            a: 100,
            b: 9
        }, {
            y: '2007',
            a: 75,
            b: 16
        }, {
            y: '2008',
            a: 50,
            b: 20
        }, {
            y: '2009',
            a: 75,
            b: 20
        }, {
            y: '2010',
            a: 50,
            b: 4
        }, {
            y: '2011',
            a: 75,
            b: 19
        }, {
            y: '2012',
            a: 100,
            b: 5
        }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Oxygen (ppm)', 'H2S (ppm)'],
        hideHover: 'auto',
        resize: true
    });

});
