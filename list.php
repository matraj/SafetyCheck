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
	<input type="checkbox" id="list-checkbox">
  
  	<nav role="navigation" class="mobile-menu">
    	<ul>
      		<a href="index.php"><li><img src="Images/home.png">Dashboard</li></a>
      		<a href="list.php"><li><img src="Images/mysite.png">My Site</li></a>
      		<a href="myhistory.php"><li><img src="Images/history.png">My History</li></a>
      		<a href="sites.html"><li><img src="Images/list.png">Site List</li></a>
      		<a href="#"><li><img src="Images/settings.png">Settings</li></a>
    	</ul>
 	</nav>

 	<label for="list-checkbox" id="list-overlay"></label>

 	<input type="checkbox" id="worker-checkbox">
  
  	<nav role="navigation" class="worker-menu">
  		<h2>Workers on Site</h2>
  		<div class="icon-right">
  			<img src="Images/group.png">
  		</div>
    	<ul>
      		<li>
      			<img class="dp" src="Images/aaron.png">
      			Aaron Bennett
      			<div class="message">
      				<img src="Images/message.png">
      			</div>
      			<div class="phone">
      				<img src="Images/phone.png">
      			</div>
      		</li>
      		<li>
      			<img class="dp" src="Images/janet.png">
      			Janet Perkins
      			<div class="message">
      				<img src="Images/message.png">
      			</div>
      			<div class="phone">
      				<img src="Images/phone.png">
      			</div>
      		</li>
      		<li>
      			<img class="dp" src="Images/mary.png">
      			Mary Johnson
      			<div class="message">
      				<img src="Images/message.png">
      			</div>
      			<div class="phone">
      				<img src="Images/phone.png">
      			</div>
      		</li>
      		<li>
      			<img class="dp" src="Images/peter.png">
      			Peter Carlsson
      			<div class="message">
      				<img src="Images/message.png">
      			</div>
      			<div class="phone">
      				<img src="Images/phone.png">
      			</div>
      		</li>
      		<li>
      			<img class="dp" src="Images/trevor.png">
      			Trevor Hansen
      			<div class="message">
      				<img src="Images/message.png">
      			</div>
      			<div class="phone">
      				<img src="Images/phone.png">
      			</div>
      		</li>
    	</ul>
 	</nav>

 	<label for="worker-checkbox" id="worker-overlay"></label>

	<header>
		<div class="navbar tabtop">
			<div class="container">
				<label for="list-checkbox" id="list-menu-btn">
					<img src="Images/menu.png">
				</label>
				<h1>Site #1</h1>
				<label for="worker-checkbox" id="worker-btn" class="worker-icon icon-right">
					<img src="Images/workers.png">
				</label>
				<div class="filter-icon icon-left">
					<img src="Images/filter.png">
				</div>	
			</div>
		</div>
		<div class="tabs">
			<div class="list-tab tab">
				<h2 class="selected">LIST</h2>
				<div class="selected-tip"></div>
			</div>
			<div class="map-tab tab">
        <a href="map.html">
				<h2 class="unselected">MAP</h2>
        </a>
			</div>
			<div class="graph-tab tab">
        <a href="graphs.html">
				<h2 class="unselected">GRAPHS</h2>
        </a>
			</div>
		</div>
	</header>

	<div class="hazard-list">

    <!-- Data Base Connection Authentication -->
<?php
  // // Lagrange Methods
  // function L($i, $x){
  //  $v = array(0.4, 1.0, 1.8, 2.6, 4.0, 6.0, 8.0, 10.0);
  //  $result = 1;
  //  for($j = 0; $j< 8; $j++){
 //       if($j != $i){
 //           $result = $result * (($x-$v[$j])/($v[$i]-$v[$j]));
 //       }
 //     }
 //     //echo "result: {$result}";
 //     return $result;
  // }
  // function ppm($x){
  //  $p = array(0.4, 1.0, 1.8, 2.6, 4.0, 6.0, 8.0, 10.0);
  //  $y = 0;
  //  for($i = 0; $i < 8; $i++){
 //       $y = $y + $p[$i] * L($i,$x);
 //     }
 //     return $y;
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
				<h5 class="time">2:25 pm</h5>
				<h4>H2S Detected</h4>
				<h5 class="ppm">0.034234 ppm</h5>
			</div>
		</div>

		<div class="hazard">
			<div class="color yellow"></div>
			<div class="info">	
				<h5 class="time">1:34 pm</h5>
				<h4>Carbon Monoxide Detected</h4>			
				<h5 class="ppm">3.1415928 ppm</h5>
			</div>
		</div>

		<div class="hazard">
			<div class="color green"></div>
			<div class="info">	
				<h5 class="time">Jul 11</h5>
				<h4>Methane Detected</h4>			
				<h5 class="ppm">5.344263 ppm</h5>
			</div>
		</div>
	</div>
	
</body>
</html>