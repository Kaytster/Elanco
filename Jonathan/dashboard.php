<?php
$dbFilePath = 'Elanco-Final.db'; 

try {
    $db = new SQLite3('Elanco-Final.db');
    // echo "Connected to the SQLite3 database!";

    
    
    $dogID = $db->query('SELECT Dog_ID FROM Activity WHERE Dog_ID="CANINE001" LIMIT 1 ');
	$rowDOG = $dogID->fetchArray(SQLITE3_ASSOC); // Get the data

	$weightID = $db->query('SELECT round(avg(Weight), 1) AS "Weight_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	$rowWEIGHT = $weightID->fetchArray(SQLITE3_ASSOC);

	// $behavID = $db->query('SELECT avg(Behaviour_ID) AS "Behaviour_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	// $rowBEHAV = $behavID->fetchArray(SQLITE3_ASSOC);

	$normalID = $db->query('SELECT count(Behaviour_ID) AS "Normal" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021" AND Behaviour_ID="1"');
	$rowNORMAL = $normalID->fetchArray(SQLITE3_ASSOC);

	$walkingID = $db->query('SELECT count(Behaviour_ID) AS "Walking" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021" AND Behaviour_ID="2"');
	$rowWALKING = $walkingID->fetchArray(SQLITE3_ASSOC);

	$eatingID = $db->query('SELECT count(Behaviour_ID) AS "Eating" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021" AND Behaviour_ID="3"');
	$rowEATING = $eatingID->fetchArray(SQLITE3_ASSOC);

	$sleepingID = $db->query('SELECT count(Behaviour_ID) AS "Sleeping" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021" AND Behaviour_ID="4"');
	$rowSLEEPING = $sleepingID->fetchArray(SQLITE3_ASSOC);

	$playingID = $db->query('SELECT count(Behaviour_ID) AS "Playing" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021" AND Behaviour_ID="5"');
	$rowPLAYING = $playingID->fetchArray(SQLITE3_ASSOC);

	$barkID = $db->query('SELECT round(avg(Frequency_ID), 1) AS "Frequency_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	$rowBARK = $barkID->fetchArray(SQLITE3_ASSOC);

	if ($rowBARK['Frequency_ID'] < 2) {
		$rowBARK="None";
	} elseif ($rowBARK['Frequency_ID'] >= 2 AND $rowBARK['Frequency_ID'] < 3 ) {
		echo "Low";
	} elseif ($rowBARK['Frequency_ID'] >= 3 AND $rowBARK['Frequency_ID'] < 4) {
		echo "Medium";
	} elseif ($rowBARK['Frequency_ID'] >= 4 AND $rowBARK['Frequency_ID'] < 5) {
		echo "High";
	}

	$stepsID = $db->query('SELECT sum(Activity_Level) AS "Steps_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	$rowSTEPS = $stepsID->fetchArray(SQLITE3_ASSOC);

	$heartID = $db->query('SELECT round(avg(Heart_Rate), 1) AS "Heart_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	$rowHEART = $heartID->fetchArray(SQLITE3_ASSOC);

	$tempID = $db->query('SELECT round(avg(Temperature), 1) AS "Temp_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	$rowTEMP = $tempID->fetchArray(SQLITE3_ASSOC);

	$breathID = $db->query('SELECT round(avg(Breath_Rate), 1) AS "Breath_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	$rowBREATH = $breathID->fetchArray(SQLITE3_ASSOC);

	$foodID = $db->query('SELECT sum(Food_Intake) AS "Food_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	$rowFOOD = $foodID->fetchArray(SQLITE3_ASSOC);

	$calID = $db->query('SELECT sum(Calorie_Burnt) AS "Cal_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	$rowCAL = $calID->fetchArray(SQLITE3_ASSOC);

	$waterID = $db->query('SELECT sum(Water_Intake) AS "Water_ID" FROM Activity WHERE Dog_ID="CANINE001" AND D_Date="01-01-2021"');
	$rowWATER = $waterID->fetchArray(SQLITE3_ASSOC);

	$anomalies = [];

    if ($rowWEIGHT['Weight_ID'] < 5 || $rowWEIGHT['Weight_ID'] > 50) {
        $anomalies[] = "Warning: Unusual weight detected!";
    }
    if ($rowHEART['Heart_ID'] < 40 || $rowHEART['Heart_ID'] > 180) {
        $anomalies[] = "Warning: Heart rate out of normal range!";
    }
    if ($rowTEMP['Temp_ID'] < 35 || $rowTEMP['Temp_ID'] > 40) {
        $anomalies[] = "Warning: Temperature is abnormal!";
    }
    if ($rowBREATH['Breath_ID'] < 10 || $rowBREATH['Breath_ID'] > 50) {
        $anomalies[] = "Warning: Breathing rate is unusual!";
    }

    // Convert anomalies to JSON for JavaScript
    $anomaliesJSON = json_encode($anomalies);

} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
	<!-- <link rel="stylesheet" href="dashboard.css"> -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="stylesheet" href="new.css">
	<?php include 'navbar.php';?>
	<title>Dashboard</title>
</head>

<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

	<body>
	<script>
    let anomalies = <?php echo $anomaliesJSON; ?>;
    if (anomalies.length > 0) {
        Swal.fire({
            title: "Anomaly Detected!",
            text: anomalies.join("\n"),
            icon: "warning",
            confirmButtonText: "OK"
        });
    }
</script>
		<section>

			<!-- <div class="row">
				<div class="coldate">
					<h3>01/01/2020</h3>
				</div>
				<div class="colname">
					<h3>Snoopy</h3>
				</div>
				<div class="colID">
					<h3>CANINE001</h3>
				</div>
			</div>

			<div class="row">
				<div class="colweight">
					<h3>Weight (kg)</h3>
					<p style="font-family: Arial, Helvetica, sans-serif; font-size: 35px;">7.2kg</p>
				</div>
				<div class="colbehav">
					<h3>Behaviour Pattern</h3>
				</div>
				<div class="colbark">
					<h3>Barking Frequency</h3>
					<img src="Frequency.png" alt="Frequency" width="150" height="150"> 
					<p style="font-family: Arial, Helvetica, sans-serif; font-size: 35px;">High</p>
				</div>
			</div>

			<div class="row">
				<div class="colactivity">
					<h3>Activity Level (steps)</h3>
					<svg width="150" height="150" viewBox="0 0 250 250"
				 		class="activity-progress" style="--progress: 60"
					>
						<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle">7960 Steps</text>    
						<circle class="bgA"></circle>
						<circle class="fgA"></circle>
					</svg>
				</div>
				<div class="colheart">
					<h3>Heart Rate (bpm)</h3>
					<svg width="150" height="150" viewBox="0 0 250 250"
				 		class="heart-progress" style="--progress: 25"
					>
						<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle">110 BPM</text>    
						<circle class="bgH"></circle>
						<circle class="fgH"></circle>
					</svg>
				</div>
				<div class="coltemp">
					<h3>Temperature (C)</h3>
					<svg width="150" height="150" viewBox="0 0 250 250"
				 		class="temp-progress" style="--progress: 25"
					>
						<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle">27.2C</text>    
						<circle class="bgT"></circle>
						<circle class="fgT"></circle>
					</svg>
				</div>
			</div>

			<div class="row">
				<div class="colbreath">
					<h3>Breathing Rate</h3>
					<h3>(breaths/min)</h3>
					<svg width="150" height="150" viewBox="0 0 250 250"
				 		class="breath-progress" style="--progress: 50"
					>
						<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle">20</text>    
						<circle class="bgB"></circle>
						<circle class="fgB"></circle>
					</svg>
				</div>
				<div class="colfood">
					<h3>Food Intake (calories)</h3>
					<div class="container">
						<img src="Food.png" alt="Food" width="150" height="150"> 
						<div class="centered">261.3</div>
					</div>
				</div>
				<div class="colcalorie">
					<h3>Calorie Burn</h3>
					<div class="container">
						<img src="Food.png" alt="Foof" width="150" height="150"> 
						<div class="centered">278.4</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="colwater">
					<h3>Water Intake (ml)</h3>
					<div class="container">
						<img src="Water.png" alt="Water" width="150" height="150"> 
						<div class="centered">458.1</div>
					</div>
				</div>
			</div> -->
		<div class="row">
				<div class="card" style="background-color: #ecf6fd; width: 28%; margin-left:10px; text-align:center; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
					<div class="card-body">
						<h3 class="card-title">01/01/2021</h3>
					</div>
				</div>
			<div class="card" style="background-color: #ecf6fd; width: 28%; margin-left:60px; text-align:center; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
				<div class="card-body">
					<h3 class="card-title">Snoopy</h3>
				</div>
			</div>
			<div class="card" style="background-color: #ecf6fd; width: 28%; margin-left:60px; text-align:center; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
				<div class="card-body">
					<h3 class="card-title"><?php echo $rowDOG['Dog_ID']?></h3>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:10px;">
				<a href="Weight_chart.php">
					<div class="card-body">
						<h3 class="card-title" style="text-decoration: underline;">Weight (kg)</h3>
						<p class="card-text" style="text-align: center; font-size: 35px;"><?php echo $rowWEIGHT['Weight_ID']?></p>
					</div>
				</a>
			</div>
			<!-- <div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:60px;">
				<div class="card-body">
					<h3 class="card-title" style="text-decoration: underline;">Behaviour Pattern</h3>
					<p class="card-text" style="text-align: center; font-size: 35px;"><?php echo $rowBEHAV['Behaviour_ID']?></p>
				</div>
			</div> -->

			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:60px;">
				<div class="card-body">
					<canvas id="myChart" style="width:100%;max-width:600px; margin:auto;"></canvas>
					<script>
						var xValues = ["Normal", "Walking", "Eating", "Sleeping", "Playing"];
						var yValues = [<?php echo $rowNORMAL['Normal']?>, <?php echo $rowWALKING['Walking']?>, <?php echo $rowEATING['Eating']?>, <?php echo $rowSLEEPING['Sleeping']?>, <?php echo $rowPLAYING['Playing']?>];
						var barColors = [
						"#fa72a4",
						"#f8e857",
						"#d97df8",
						"#8bf87d",
						"#97fcf2"
						];

						new Chart("myChart", {
						type: "pie",
						data: {
							labels: xValues,
							datasets: [{
							backgroundColor: barColors,
							data: yValues
							}]
						},
						options: {
							title: {
							display: true,
							text: "Behaviour"
							}
						}
						});
					</script>
				</div>
			</div>

			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:60px;">
				<a href="barking_chart.php">
				<div class="card-body">
					<h3 class="card-title" style="text-decoration: underline;">Barking Frequency</h3>
						<img src="Frequency.png" alt="Frequency" width="150" height="150">
					<p class="card-text" style="text-align: center; font-size: 35px;"><?php echo $rowBARK?></p>
				</a>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:10px;">
				<a href="activity_chart.php">
				<div class="card-body">
					<h3 class="card-title" style="text-decoration: underline;">Activity Level (steps)</h3>
					<svg width="150" height="150" viewBox="0 0 250 250"
				 		class="activity-progress" style="--progress: 60; display: block; margin: auto;"
					>
						<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"><?php echo $rowSTEPS['Steps_ID']?></text>    
						<circle class="bgA"></circle>
						<circle class="fgA"></circle>
					</svg>
				</div>
				</a>
			</div>
			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:60px;">
				<a href="heart_rate_chart.php">
				<div class="card-body">
					<h3 class="card-title" style="text-decoration: underline;">Heart Rate (bpm)</h3>
					<svg width="150" height="150" viewBox="0 0 250 250"
				 		class="heart-progress" style="--progress: 25; display: block; margin: auto;"
					>
						<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"><?php echo $rowHEART['Heart_ID']?></text>    
						<circle class="bgH"></circle>
						<circle class="fgH"></circle>
					</svg>
				</div>
				</a>
			</div>
			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:60px;">
				<a href="temperature_chart.php">
				<div class="card-body">
					<h3 class="card-title" style="text-decoration: underline;">Temperature (C)</h3>
					<svg width="150" height="150" viewBox="0 0 250 250"
				 		class="temp-progress" style="--progress: 25; display: block; margin: auto;"
					>
						<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"><?php echo $rowTEMP['Temp_ID']?>C</text>    
						<circle class="bgT"></circle>
						<circle class="fgT"></circle>
					</svg>
				</div>
				</a>
			</div>
		</div>

		<div class="row">
			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:10px;">
				<a href="breathing_rate_chart.php">
				<div class="card-body">
					<h3 class="card-title" style="text-decoration: underline;">Breathing Rate (breaths/min)</h3>
					<svg width="150" height="150" viewBox="0 0 250 250"
				 		class="breath-progress" style="--progress: 50; display: block; margin: auto;"
					>
						<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"><?php echo $rowBREATH['Breath_ID']?></text>    
						<circle class="bgB"></circle>
						<circle class="fgB"></circle>
					</svg>
				</div>
				</a>
			</div>
			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:60px;">
				<a href="food_intake_chart.php">
				<div class="card-body">
					<h3 class="card-title" style="text-decoration: underline;">Food Intake (calories)</h3>
						<img src="Food.png" alt="Food" width="150" height="150">
						<p class="centered"><?php echo $rowFOOD['Food_ID']?></p>
				</div>
				</a>
			</div>
			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:60px;">
				<a href="calorie_chart.php">
				<div class="card-body">
					<h3 class="card-title" style="text-decoration: underline;">Calorie Burn</h3>
						<img src="Food.png" alt="Food" width="150" height="150">
						<p class="centered"><?php echo $rowCAL['Cal_ID']?></p>
				</div>
				</a>
			</div>
		</div>

		<div class="row">
			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin:auto;">
				<a href="water_intake_chart.php">
				<div class="card-body">
					<h3 class="card-title" style="text-decoration: underline;">Water Intake (ml)</h3>
						<img src="Water.png" alt="Water" width="150" height="150"> 
						<p class="centered"><?php echo $rowWATER['Water_ID']?></p>
				</div>
				</a>
			</div>
		</div>

		</section>

		<section>
		<canvas id="myChart" style="width:100%; margin:auto;"></canvas>
			<script>
				var xValues = ["Normal", "Walking", "Eating", "Sleeping", "Playing"];
				var yValues = [<?php echo $rowNORMAL['Normal']?>, <?php echo $rowWALKING['Walking']?>, <?php echo $rowEATING['Eating']?>, <?php echo $rowSLEEPING['Sleeping']?>, <?php echo $rowPLAYING['Playing']?>];
				var barColors = [
				"#fa72a4",
				"#f8e857",
				"#d97df8",
				"#8bf87d",
				"#97fcf2"
				];

				new Chart("myChart", {
				type: "pie",
				data: {
					labels: xValues,
					datasets: [{
					backgroundColor: barColors,
					data: yValues
					}]
				},
				options: {
					title: {
					display: true,
					text: "Behaviour"
					}
				}
				});
			</script>
		</section>
	</body>
</html>