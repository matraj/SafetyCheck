<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto:500,400' rel='stylesheet' type='text/css'>
	<title>Safety Check</title>
</head>
<body>

	<form action="<?=$_SERVER['PHP_SELF'];?>" method="post"> 
		<input type="submit" name="show" value="Show"> 
		<input type="submit" name="hide" value="Hide"> 
	</form>
	<header>
		<nav class="navbar">
			<div class="container">
				<a href="#"><img src="Images/menu.png"></a>
				<h1>Dashboard</h1>	
			</div>
		</nav>
	</header>

	<div class="dashboard-top">
<?php 
	$show = false;
	if(isset($_POST['hide'])) { 
		$show = false;
	} elseif (isset($_POST['show'])) {
		$show = true;
	} elseif (isset($_POST['arrow1'])) {
		$show = true;
		echo "test";
	} 
	if ($show) {
		echo <<< EOT
		<div class="safety">
			<img src="Images/caution-status.png">
			<div class="status">
				<h3>Safety Status:</h3>
				<h2>CAUTION</h2>
				<p>2 hazards detected</p>
			</div>
		</div>
EOT;
	}

	$conn = new mysqli('107.180.4.71:3306', 'datareciever', 'Password123', 'H2S_SENSOR_DB2');
	$conn->query('SET NAMES "utf8"');

	$query = <<<SQL
	SELECT * FROM H2S_SENSOR_DB2.SensorData ORDER BY ID DESC LIMIT 1;
SQL;
	echo "query:{$query}";
	$sensorValue;
	$result = $conn->query($query);
	if ($conn->errno != 0)
		die('mysql error:'.$cann->error);

	while (($row = $result->fetch_assoc()) != null)
	{
		echo "<p>";
		//echo "Row:{$row}";
		//echo "result:{$result}";
		echo $row['SensorValue'];
		$sensorValue = (int)$row['SensorValue'];
		echo "</p>";
	}

	$result->close();
?>
		<?php 
			echo "Test String: {$sensorValue}";
			if ($sensorValue >= 1000) {
				echo <<< EOT
		<div class="safety">
			<img src="Images/caution-status.png">
			<div class="status">
				<h3>Safety Status:</h3>
				<h2>CAUTION</h2>
				<p>2 hazards detected</p>
			</div>
		</div>
EOT;
			} else {
				echo <<< EOT
		<div class="safety">
			<img src="Images/caution-status.png">
			<div class="status">
				<h3>Safety Status:</h3>
				<h2>SAFE</h2>
				<p>0 hazards detected</p>
			</div>
		</div>
EOT;
			}
		?>
		<div class="hazard">
			<div class="color orange"></div>
			<div class="info">
				<h5 class="time">2:34 pm</h5>
				<h4>H2S Detected</h4>
				<h5 class="ppm">7 ppm</h5>
			</div>
		</div>

		<div class="hazard">
			<div class="color yellow"></div>
			<div class="info">
				<h5 class="time">2:34 pm</h5>
				<h4>H2S Detected</h4>
				<h5 class="ppm">7 ppm</h5>
			</div>
		</div>
	</div>

	<div class="divider">
		<h3>My Site</h3>
		<form action="<?=$_SERVER['PHP_SELF'];?>" method="post">  
			<input type="Submit" name="arrow">
		</form> 
	</div>

	<div class="feed">
		<div class="hazard">
			<div class="color orange"></div>
			<div class="info">
				<h5 class="time">2:34 pm</h5>
				<h4>H2S Detected</h4>
				<h5 class="ppm">7 ppm</h5>
			</div>
		</div>

		<div class="hazard">
			<div class="color yellow"></div>
			<div class="info">	
				<h5 class="time">2:34 pm</h5>
				<h4>H2S Detected</h4>			
				<h5 class="ppm">7 ppm</h5>
			</div>
		</div>

		<div class="hazard">
			<div class="color green"></div>
			<div class="info">	
				<h5 class="time">2:34 pm</h5>
				<h4>H2S Detected</h4>			
				<h5 class="ppm">7 ppm</h5>
			</div>
		</div>
	</div>
	
</body>
</html>