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

<!-- Data Base Connection Authentication -->
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
	SELECT * FROM H2S_SENSOR_DB2.SensorData WHERE SensorID = 10725 ORDER BY ID DESC LIMIT 2;
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
		$sensorValue = (double)$row['SensorValue'];
		$ppmValue = ppm($sensorValue/1000);

		array_push($sensorValueArray, $row);//$sensorValueArray = $row['ID']; // Populate Array

		$sensorDate = date('h:i a', strtotime($row['ValueTimestamp']));//date_format($row['ValueTimestamp'], 'h:i a');
		// echo "Time: {$sensorDate}"; // TEST
		// echo "Concentration: {$ppmValue}"; // TEST
		// var_dump($sensorValueArray);
		//echo "ID'S: {$sensorValueArray}";
		//echo "</p>"; // TEST
	}

	$result->close();
?>


<body>
	<input type="checkbox" id="dashboard-checkbox">
  
  	<nav role="navigation" class="mobile-menu">
    	<ul>
      		<a href="index.php"><li><img src="Images/home.png">Dashboard</li></a>
      		<a href="list.php"><li><img src="Images/mysite.png">My Site</li></a>
      		<a href="myhistory.php"><li><img src="Images/history.png">My History</li></a>
      		<a href="sites.html"><li><img src="Images/list.png">Site List</li></a>
      		<a href="#"><li><img src="Images/settings.png">Settings</li></a>
    	</ul>
 	</nav>

 	<label for="dashboard-checkbox" id="dashboard-overlay"></label>

	<header class="navbar">
		<div class="container">
			<label for="dashboard-checkbox" id="dashboard-menu-btn">
				<img src="Images/menu.png">
			</label>
			<h1>Dashboard</h1>	
		</div>
	</header>

	<div class="dashboard-top">
<!-- DYNAMIC ALERT STATUS -->
<?php
			//$ppmValue = 2.6;  // For testing
			if ($ppmValue <= 1.5) {
				echo <<< EOT
				<div class="safety">
					<img src="Images/safe-status.png">
					<div class="status">
						<h3>Safety Status:</h3>
						<h2 class="safe">SAFE</h2>
						<p>0 hazards detected</p>
					</div>
				</div>
EOT;
			} elseif (($ppmValue > 1.5)  && ($ppmValue < 2.5)) {
				echo <<< EOT
				<div class="safety">
					<img src="Images/caution-status.png">
					<div class="status">
						<h3>Safety Status:</h3>
						<h2 class="caution">CAUTION</h2>
						<p>2 hazards detected</p>
					</div>
				</div>
				<div class="hazard">
					<div class="color orange"></div>
					<a href="hazard.html">
					<div class="info">
						<h5 class="time">{$sensorDate}</h5>
						<h4>H2S Detected</h4>
						<h5 class="ppm">{$ppmValue} ppm</h5>
					</div>
					</a>
				</div>
EOT;
			} else {
				echo <<< EOT
				<div class="safety">
					<img src="Images/danger-status.png">
					<div class="status">
						<h3>Safety Status:</h3>
						<h2 class="danger">DANGER</h2>
						<p>4 hazards detected</p>
					</div>
				</div>
				<div class="hazard">
					<div class="color orange"></div>
					<a href="hazard.html">
					<div class="info">
						<h5 class="time">{$sensorDate}</h5>
						<h4>H2S Detected</h4>
						<h5 class="ppm">{$ppmValue} ppm</h5>
					</div>
					</a>
				</div>

				<div class="hazard">
					<div class="color yellow"></div>
					<a href="hazard.html">
					<div class="info">
						<h5 class="time">1:34 pm</h5>
						<h4>Carbon Monoxide Detected</h4>
						<h5 class="ppm">3.1415928 ppm</h5>
					</div>
					</a>
				</div>	
EOT;
			}
?>
	<!-- END OF DYNAMIC ALERT STATUS -->
	</div>

	<div class="divider">
		<a href="list.php"><img src="Images/forward-arrow.png"></a>
		<h3>My Site</h3>

	</div>

	<div class="feed">
		<div class="hazard">
			<div class="color orange"></div>
			<a href="hazard.html">
			<div class="info">
				<h5 class="time"><?php echo "{$sensorDate}"; ?></h5>
				<h4>H2S Detected</h4>
				<h5 class="ppm"><?php echo "{$ppmValue}"; ?> ppm</h5>
			</div>
			</a>
		</div>

		<div class="hazard">
			<div class="color yellow"></div>
			<a href="hazard.html">
			<div class="info">	
				<h5 class="time">1:34 pm</h5>
				<h4>Carbon Monoxide Detected</h4>			
				<h5 class="ppm">3.1415928 ppm</h5>
			</div>
			</a>
		</div>

		<div class="hazard">
			<div class="color green"></div>
			<a href="hazard.html">
			<div class="info">	
				<h5 class="time">Jul 11</h5>
				<h4>Methane Detected</h4>			
				<h5 class="ppm">5.5435 ppm</h5>
			</div>
			</a>
		</div>
	</div>
	<!-- SAVE EVENT TO DATABASE -->
<?php
	// echo '<pre>'; print_r($sensorValueArray); echo '</pre>';
	$row1CurrentVal = ppm($sensorValueArray[0]['SensorValue']/1000);
	$row2PrevVal = ppm($sensorValueArray[1]['SensorValue']/1000);
	// echo "Row current Value: {$row1CurrentVal}";
	// echo "Row prev Value: {$row2PrevVal}";

	// $row1CurrentVal = 2.7;// TESTING
	// $row2PrevVal =  0.5;// TEStING

	//	in if else cond set the sql
	// on success alert in js ALERT: site 1: Status is now caution

	if (($row2PrevVal <= 1.0) && ($row1CurrentVal > 1.0)){
		// Save Caution event to DB
		echo "SAVE Caurtion!";
		// Create a conn with update query
		$conn = new mysqli('107.180.4.71', 'datareciever', 'Password123', 'H2S_SENSOR_DB2', '3306');
		$conn->query('SET NAMES "utf8"');

		$query = <<<SQL
		INSERT INTO H2S_SENSOR_DB2.HazardEvent (HazardEventType, HazardLevel, HazardPPM, HazardTime)
		VALUES ('Alcohol Detected','caution', $ppmValue, '$sensorDate');
SQL;
		
		$sensorValue;
		$result = $conn->query($query);
		if ($conn->errno != 0)
			die('mysql error:'.$conn->error);
		$message = 'SITE 1: Safety Status changed from SAFE to CAUTION';
		echo "<script type='text/javascript'>alert('$message');</script>";
		// while (($row = $result->fetch_assoc()) != null)
		// {
		// 	echo "Row:{$row}";
		// 	echo "result:{$result}";
		// }

		//$result->close();
	} elseif (($row2PrevVal > 1.5)  && ($row2PrevVal < 2.5) && ($row1CurrentVal >= 2.5)) {
		// Save Hazard event 
		echo "SAVE Warning!!";
		// Create a conn with update query
		$conn = new mysqli('107.180.4.71', 'datareciever', 'Password123', 'H2S_SENSOR_DB2', '3306');
		$conn->query('SET NAMES "utf8"');

		$query = <<<SQL
		INSERT INTO H2S_SENSOR_DB2.HazardEvent (HazardEventType, HazardLevel, HazardPPM, HazardTime)
		VALUES ('Alcohol Detected','warning', $ppmValue, '$sensorDate');
SQL;
		
		$sensorValue;
		$result = $conn->query($query);
		if ($conn->errno != 0)
			die('mysql error:'.$conn->error);
		$message = 'SITE 1: Safety Status changed from CAUTION to WARNING';
		echo "<script type='text/javascript'>alert('$message');</script>";
	}
?>
</body>
</html>