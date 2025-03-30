<?php
session_start();

try {
    $db = new SQLite3('Elanco-Final.db');
    // echo "Connected to the SQLite3 database!";
	if (!$db) {
        echo "Error: Database connection failed! Check the file path.";
        exit; // Stop the script if the connection failed
    }
    

	$dateID = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
	$query = $db->prepare("SELECT strftime('%d-%m-%Y', ?)");
    $query->bindValue(1, $dateID);
    $result = $query->execute();
    $row = $result->fetchArray(SQLITE3_NUM);
    $formattedDate = $row[0];

    // echo "Selected Date: " . $formattedDate . "<br>";

	if (isset($_SESSION['selected_pet'])) {
		$Dog_Name = $_SESSION['selected_pet'];
	} else {
		$Dog_ID = "No pet selected";
	}

	if (isset($_SESSION['selected_ID'])) {
		$Dog_ID = $_SESSION['selected_ID'];
	} else {
		$Dog_Name = "No pet selected";
	}

	$weightID = $db->query("SELECT round(avg(Weight), 1) AS 'Weight_ID' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate'");
	$rowWEIGHT = $weightID->fetchArray(SQLITE3_ASSOC);

	$normalID = $db->query("SELECT count(Behaviour_ID) AS 'Normal' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate' AND Behaviour_ID='1'");
	$rowNORMAL = $normalID->fetchArray(SQLITE3_ASSOC);

	$walkingID = $db->query("SELECT count(Behaviour_ID) AS 'Walking' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate' AND Behaviour_ID='2'");
	$rowWALKING = $walkingID->fetchArray(SQLITE3_ASSOC);

	$eatingID = $db->query("SELECT count(Behaviour_ID) AS 'Eating' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate' AND Behaviour_ID='3'");
	$rowEATING = $eatingID->fetchArray(SQLITE3_ASSOC);

	$sleepingID = $db->query("SELECT count(Behaviour_ID) AS 'Sleeping' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate' AND Behaviour_ID='4'");
	$rowSLEEPING = $sleepingID->fetchArray(SQLITE3_ASSOC);

	$playingID = $db->query("SELECT count(Behaviour_ID) AS 'Playing' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate' AND Behaviour_ID='5'");
	$rowPLAYING = $playingID->fetchArray(SQLITE3_ASSOC);

	$barkID = $db->query("SELECT round(avg(Frequency_ID), 1) AS 'Frequency_ID' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate'");
	$rowBARK = $barkID->fetchArray(SQLITE3_ASSOC);

	$barkingFREQ = "";

	if ($rowBARK['Frequency_ID'] < 2) {
		$barkingFREQ="None";
	} elseif ($rowBARK['Frequency_ID'] >= 2 AND $rowBARK['Frequency_ID'] < 3 ) {
		$barkingFREQ="Low";
	} elseif ($rowBARK['Frequency_ID'] >= 3 AND $rowBARK['Frequency_ID'] < 4) {
		$barkingFREQ="Medium";
	} elseif ($rowBARK['Frequency_ID'] >= 4 AND $rowBARK['Frequency_ID'] < 5) {
		$barkingFREQ="High";
	}

	$stepsID = $db->query("SELECT sum(Activity_Level) AS 'Steps_ID' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate'");
	$rowSTEPS = $stepsID->fetchArray(SQLITE3_ASSOC);

	$heartID = $db->query("SELECT round(avg(Heart_Rate), 1) AS 'Heart_ID' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate'");
	$rowHEART = $heartID->fetchArray(SQLITE3_ASSOC);

	$tempID = $db->query("SELECT round(avg(Temperature) , 1) AS 'Temp_ID' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate'");
	$rowTEMP = $tempID->fetchArray(SQLITE3_ASSOC);

	$breathID = $db->query("SELECT round(avg(Breath_Rate) , 1) AS 'Breath_ID' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate'");
	$rowBREATH = $breathID->fetchArray(SQLITE3_ASSOC);

	$foodID = $db->query("SELECT sum(Food_Intake) AS 'Food_ID' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate'");
	$rowFOOD = $foodID->fetchArray(SQLITE3_ASSOC);

	$calID = $db->query("SELECT sum(Calorie_Burnt) AS 'Cal_ID' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate'");
	$rowCAL = $calID->fetchArray(SQLITE3_ASSOC);

	$waterID = $db->query("SELECT sum(Water_Intake) AS 'Water_ID' FROM Activity WHERE Dog_ID='$Dog_ID' AND D_Date='$formattedDate'");
	$rowWATER = $waterID->fetchArray(SQLITE3_ASSOC);

	$_SESSION['TotalSteps'] = $rowSTEPS;
	
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
	<link rel="stylesheet" href="new.css">
	<?php include 'navbar.php';?>
	<title>Dashboard</title>
</head>

<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

	<body>
		<!-- Widgets -->
		<section>
		<div class="row">
				<div class="card" style="background-color: #ecf6fd; width: 28%; margin-left:10px; text-align:center; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
					<div class="card-body">
						<h3 class="card-title"><?php echo $formattedDate;?></h3>
					</div>
				</div>
			<div class="card" style="background-color: #ecf6fd; width: 28%; margin-left:60px; text-align:center; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
				<div class="card-body">
					<h3 class="card-title"><?php echo $Dog_Name;?></h3>
				</div>
			</div>
			<div class="card" style="background-color: #ecf6fd; width: 28%; margin-left:60px; text-align:center; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
				<div class="card-body">
					<h3 class="card-title"><?php echo $Dog_ID?></h3>
				</div>
			</div>
		</div>

		<!-- Date Picker -->
		<section>
			<div class="row">
				<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:10px;">
					<div class="card-body">
						<h2 class="text-primary">Date Selector</h2>
						<input type="date" id="date" class="form-control date-picker" value="<?= date('Y-m-d') ?>">
					</div>
					<script>
                        document.getElementById('date').addEventListener('change', function() {
                            var selectedDate = this.value;
                            //alert(selectedDate);
                            window.location.href = '?date=' + selectedDate;
                        });
                    </script>
				</div>
			</div>
		</section>

		<div class="row">
			<div class="card" style="background-color: #ecf6fd; width: 28%; border-color:#0067B1; border-width:1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-left:10px;">
				<a href="Weight_chart.php">
					<div class="card-body">
						<h3 class="card-title" style="text-decoration: underline;">Weight (kg)</h3>
						<p class="card-text" style="text-align: center; font-size: 35px;"><?php echo $rowWEIGHT['Weight_ID']?></p>
					</div>
				</a>
			</div>

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
					<p class="card-text" style="text-align: center; font-size: 35px;"><?php echo $barkingFREQ?></p>
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

		<!-- Pie Chart -->
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