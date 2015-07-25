<?php
	// Lagrange Methods
	function L($i, $x){
		$v = array(0.4, 1.0, 1.8, 2.6, 4.0, 6.0, 8.0, 10.0);
		$result = 1;
		for($j = 0; $j< 8; $j++){
    		if($j != $i){
      			$result = $result * (($x-$v[$j])/($v[$i]-$v[$j]));
    		}
  		}
  		//echo "result: {$result}";
  		return $result;
	}
	function ppm($x){
		$p = array(0.4, 1.0, 1.8, 2.6, 4.0, 6.0, 8.0, 10.0);
		$y = 0;
		for($i = 0; $i < 8; $i++){
    		$y = $y + $p[$i] * L($i,$x);
  		}
  		return $y;
	}

	$conn = new mysqli('107.180.4.71', 'datareciever', 'Password123', 'H2S_SENSOR_DB2', '3306');
	$conn->query('SET NAMES "utf8"');

	$query = <<<SQL
	SELECT * FROM H2S_SENSOR_DB2.SensorData ORDER BY ID DESC LIMIT 1;
SQL;
	//echo "query:{$query}"; // TEST
	$sensorValue;
	$result = $conn->query($query);
	if ($conn->errno != 0)
		die('mysql error:'.$cann->error);
	$echoresult = "";
	while (($row = $result->fetch_assoc()) != null)
	{
		//echo "<p>"; // TEST
		//echo "Row:{$row}";
		//echo "result:{$result}";
		//echo $row['SensorValue']; // TEST
		$sensorValue = (double)$row['SensorValue'];
		$sensorid = (string)$row['SensorID'];
		$ppmValue = ppm($sensorValue/1000);
		$timeValue = (string)$row['ValueTimestamp'];
		$echoresult .= (string)$ppmValue . "," . (string)$sensorid . "," . (string)$timeValue . ",";
		//echo "Concentration: {$ppmValue}"; // TEST
		//echo "</p>"; // TEST
	}

	$result->close();
	echo $echoresult;
?>