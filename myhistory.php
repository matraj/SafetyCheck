<?php
	// Refresh Request every 5 sec
	$page = $_SERVER['PHP_SELF'];
	$sec = "5";
	header("Refresh: $sec; url=$page");
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto:500,400' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Safety Check</title>
</head>

<body>
	<input type="checkbox" id="history-checkbox">
  
  	<nav role="navigation" class="mobile-menu">
    	<ul>
      		<a href="index.php"><li><img src="Images/home.png">Dashboard</li></a>
      		<a href="list.php"><li><img src="Images/mysite.png">My Site</li></a>
      		<a href="myhistory.php"><li><img src="Images/history.png">My History</li></a>
      		<a href="sites.html"><li><img src="Images/list.png">Site List</li></a>
      		<a href="#"><li><img src="Images/settings.png">Settings</li></a>
    	</ul>
 	</nav>

 	<label for="history-checkbox" id="history-overlay"></label>

	<header class="navbar">
		<div class="container">
			<label for="history-checkbox" id="history-menu-btn">
				<img src="Images/menu.png">
			</label>
				<h1>My History</h1>		
				<div class="filter-icon icon-right">
					<img src="Images/filter.png">
				</div>	
			</div>
		</nav>
	</header>

	<div class="hazard-list">
	<!-- Data Base Connection Authentication -->
<?php
	// // Lagrange Methods
	// function L($i, $x){
	// 	$v = array(0.4, 1.0, 1.8, 2.6, 4.0, 6.0, 8.0, 10.0);
	// 	$result = 1;
	// 	for($j = 0; $j< 8; $j++){
 //    		if($j != $i){
 //      			$result = $result * (($x-$v[$j])/($v[$i]-$v[$j]));
 //    		}
 //  		}
 //  		//echo "result: {$result}";
 //  		return $result;
	// }
	// function ppm($x){
	// 	$p = array(0.4, 1.0, 1.8, 2.6, 4.0, 6.0, 8.0, 10.0);
	// 	$y = 0;
	// 	for($i = 0; $i < 8; $i++){
 //    		$y = $y + $p[$i] * L($i,$x);
 //  		}
 //  		return $y;
	// }

	$conn = new mysqli('107.180.4.71', 'datareciever', 'Password123', 'H2S_SENSOR_DB2', '3306');
	$conn->query('SET NAMES "utf8"');

	$query = <<<SQL
	SELECT * FROM H2S_SENSOR_DB2.HazardEvent ORDER BY idHazardEvent DESC;
SQL;
	//echo "query:{$query}"; // TEST
	$sensorValue;
	$result = $conn->query($query);
	if ($conn->errno != 0)
		die('mysql error:'.$conn->error);

	$sensorValueArray = array();
	while (($row = $result->fetch_assoc()) != null)
	{
		//echo "<p>"; // TEST
		
		// echo "Row:{$row}";
		//echo "result:{$result}";
		//echo $row['SensorValue']; // TEST
		$sensorValue = $row['HazardPPM'];
		$sensorDate = $row['HazardTime'];
		$hazardLevel = $row['HazardLevel'];

		// echo "{$sensorValue}";
		// echo "{$sensorDate}";
		// echo "{$hazardLevel}";

		if ($hazardLevel == 'caution') {
			echo <<< EOT
			<div class="hazard">
				<div class="color yellow"></div>
				<div class="info">
					<h5 class="time">{$sensorDate}</h5>
					<h4>Alcohol Detected </h4>
					<h5 class="sitenum">Site #1</h5>
					<h5 class="ppm">{$sensorValue} ppm</h5>
				</div>
			</div>
EOT;
		} else {
			echo <<< EOT
			<div class="hazard">
				<div class="color orange"></div>
				<div class="info">
					<h5 class="time">{$sensorDate}</h5>
					<h4>Alcohol Detected </h4>
					<h5 class="sitenum">Site #1</h5>
					<h5 class="ppm">{$sensorValue} ppm</h5>
				</div>
			</div>
EOT;
		}

		array_push($sensorValueArray, $row);//$sensorValueArray = $row['ID']; // Populate Array

		$sensorDate = $row['HazardTime'];//date('h:i a', strtotime($row['ValueTimestamp']));//date_format($row['ValueTimestamp'], 'h:i a');
		// echo "Time: {$sensorDate}"; // TEST
		// echo "Concentration: {$ppmValue}"; // TEST
		// echo '<pre>'; print_r($sensorValueArray); echo '</pre>';
		//echo "ID'S: {$sensorValueArray}";
		//echo "</p>"; // TEST
	}

	$result->close();
?>
		<div class="hazard">
			<div class="color orange"></div>
			<div class="info">
				<h5 class="time">2:34 pm</h5>
				<h4>H2S Detected</h4>
				<h5 class="sitenum">Site #1</h5>
				<h5 class="ppm">7 ppm</h5>
			</div>
		</div>

		<div class="hazard">
			<div class="color yellow"></div>
			<div class="info">	
				<h5 class="time">2:34 pm</h5>
				<h4>H2S Detected</h4>	
				<h5 class="sitenum">Site #1</h5>		
				<h5 class="ppm">7 ppm</h5>
			</div>
		</div>

		<div class="hazard">
			<div class="color green"></div>
			<div class="info">	
				<h5 class="time">2:34 pm</h5>
				<h4>H2S Detected</h4>
				<h5 class="sitenum">Site #1</h5>		
				<h5 class="ppm">7 ppm</h5>
			</div>
		</div>
	</div>
</body>
</html>