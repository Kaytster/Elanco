<?php


try {
    $db = new SQLite3('Elanco-Final.db');
    // echo "Connected to the SQLite3 database!";

    

	$dateID = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
	$query = $db->prepare("SELECT strftime('%d-%m-%Y', ?)");
    $query->bindValue(1, $dateID);
    $result = $query->execute();
    $row = $result->fetchArray(SQLITE3_NUM);
    $formattedDate = $row[0];

    // echo "Selected Date: " . $formattedDate . "<br>";
	
    
    $dogID = $db->query('SELECT Dog_ID FROM Activity WHERE Dog_ID="CANINE001" LIMIT 1 ');
	$rowDOG = $dogID->fetchArray(SQLITE3_ASSOC); // Get the data

	$weightID = $db->query("SELECT round(avg(Weight), 1) AS 'Weight_ID' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate'");
	$rowWEIGHT = $weightID->fetchArray(SQLITE3_ASSOC);

	$normalID = $db->query("SELECT count(Behaviour_ID) AS 'Normal' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate' AND Behaviour_ID='1'");
	$rowNORMAL = $normalID->fetchArray(SQLITE3_ASSOC);

	$walkingID = $db->query("SELECT count(Behaviour_ID) AS 'Walking' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate' AND Behaviour_ID='2'");
	$rowWALKING = $walkingID->fetchArray(SQLITE3_ASSOC);

	$eatingID = $db->query("SELECT count(Behaviour_ID) AS 'Eating' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate' AND Behaviour_ID='3'");
	$rowEATING = $eatingID->fetchArray(SQLITE3_ASSOC);

	$sleepingID = $db->query("SELECT count(Behaviour_ID) AS 'Sleeping' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate' AND Behaviour_ID='4'");
	$rowSLEEPING = $sleepingID->fetchArray(SQLITE3_ASSOC);

	$playingID = $db->query("SELECT count(Behaviour_ID) AS 'Playing' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate' AND Behaviour_ID='5'");
	$rowPLAYING = $playingID->fetchArray(SQLITE3_ASSOC);

	$barkID = $db->query("SELECT round(avg(Frequency_ID), 1) AS 'Frequency_ID' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate'");
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

	$stepsID = $db->query("SELECT sum(Activity_Level) AS 'Steps_ID' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate'");
	$rowSTEPS = $stepsID->fetchArray(SQLITE3_ASSOC);

	$heartID = $db->query("SELECT round(avg(Heart_Rate), 1) AS 'Heart_ID' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate'");
	$rowHEART = $heartID->fetchArray(SQLITE3_ASSOC);

	$tempID = $db->query("SELECT round(avg(Temperature) , 1) AS 'Temp_ID' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate'");
	$rowTEMP = $tempID->fetchArray(SQLITE3_ASSOC);

	$breathID = $db->query("SELECT round(avg(Breath_Rate) , 1) AS 'Breath_ID' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate'");
	$rowBREATH = $breathID->fetchArray(SQLITE3_ASSOC);

	$foodID = $db->query("SELECT sum(Food_Intake) AS 'Food_ID' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate'");
	$rowFOOD = $foodID->fetchArray(SQLITE3_ASSOC);

	$calID = $db->query("SELECT sum(Calorie_Burnt) AS 'Cal_ID' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate'");
	$rowCAL = $calID->fetchArray(SQLITE3_ASSOC);

	$waterID = $db->query("SELECT sum(Water_Intake) AS 'Water_ID' FROM Activity WHERE Dog_ID='CANINE001' AND D_Date='$formattedDate'");
	$rowWATER = $waterID->fetchArray(SQLITE3_ASSOC);
	
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
		<!-- SVG Gradients for progress circles -->
		<svg class="svg-defs">
			<defs>
				<linearGradient id="gradient-blue" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" stop-color="#4992FF" />
					<stop offset="100%" stop-color="#0067B1" />
				</linearGradient>
				<linearGradient id="gradient-red" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" stop-color="#FF6B6B" />
					<stop offset="100%" stop-color="#EF476F" />
				</linearGradient>
				<linearGradient id="gradient-yellow" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" stop-color="#FFD166" />
					<stop offset="100%" stop-color="#FFA500" />
				</linearGradient>
				<linearGradient id="gradient-green" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" stop-color="#06D6A0" />
					<stop offset="100%" stop-color="#079670" />
				</linearGradient>
			</defs>
		</svg>

		<div class="container">
			<h1 class="page-title">Pet Health Dashboard</h1>
			<p class="section-subtitle">Overview of your pet's health metrics for <?php echo $formattedDate;?></p>
		
			<!-- Date selector -->
			<div class="date-selector">
				<form action="dashboard.php" method="get" class="date-form">
					<div class="form-group">
						<label for="date">Select Date:</label>
						<input type="date" id="date" name="date" value="<?php echo $dateID; ?>" class="form-control">
					</div>
					<button type="submit" class="ui-button">
						<span><i class="fas fa-calendar-check"></i> Update</span>
					</button>
				</form>
			</div>

			<!-- Pet Info -->
			<section class="pet-info">
				<div class="row">
					<div class="card">
						<div class="card-body">
							<h3 class="card-title">Date</h3>
							<p class="card-value"><?php echo $formattedDate;?></p>
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<h3 class="card-title">Pet Name</h3>
							<p class="card-value">Snoopy</p>
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<h3 class="card-title">ID</h3>
							<p class="card-value"><?php echo $rowDOG['Dog_ID']?></p>
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<h3 class="card-title">Weight</h3>
							<p class="card-value"><?php echo $rowWEIGHT['Weight_ID']?> kg</p>
						</div>
					</div>
				</div>
			</section>

		<!-- Activity Overview -->
		<section>
			<h2 class="section-title">Activity Overview</h2>
			<div class="row">
				<div class="card">
					<div class="card-body">
						<h3 class="card-title">Activity Level</h3>
						<div class="progress-circle-container">
							<svg class="progress-circle" width="150" height="150" viewBox="0 0 36 36">
								<path class="progress-circle-bg"
									d="M18 2.0845
									a 15.9155 15.9155 0 0 1 0 31.831
									a 15.9155 15.9155 0 0 1 0 -31.831"
									fill="none"
									stroke="#E6E6E6"
									stroke-width="2"
								/>
								<path class="progress-circle-bar"
									d="M18 2.0845
									a 15.9155 15.9155 0 0 1 0 31.831
									a 15.9155 15.9155 0 0 1 0 -31.831"
									fill="none"
									stroke="url(#gradient-green)"
									stroke-width="2"
									stroke-dasharray="100, 100"
									stroke-dashoffset="<?php echo 100 - min(($rowSTEPS['Steps_ID'] / 10000) * 100, 100); ?>"
								/>
								<text x="18" y="18" text-anchor="middle" dominant-baseline="middle" class="progress-text"><?php echo $rowSTEPS['Steps_ID']?></text>
								<text x="18" y="23" text-anchor="middle" dominant-baseline="middle" class="progress-label">steps</text>
							</svg>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<h3 class="card-title">Energy Balance</h3>
						<div class="energy-comparison">
							<div class="energy-item">
								<div class="energy-label">Calories In</div>
								<div class="energy-value"><?php echo $rowFOOD['Food_ID']?> cal</div>
							</div>
							<div class="energy-divider"></div>
							<div class="energy-item">
								<div class="energy-label">Calories Burnt</div>
								<div class="energy-value"><?php echo $rowCAL['Cal_ID']?> cal</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<h3 class="card-title">Behavior Distribution</h3>
						<canvas id="myChart" width="100%" height="180px"></canvas>
						<script>
						var xValues = ["Normal", "Walking", "Eating", "Sleeping", "Playing"];
						var yValues = [<?php echo $rowNORMAL['Normal']?>, <?php echo $rowWALKING['Walking']?>, <?php echo $rowEATING['Eating']?>, <?php echo $rowSLEEPING['Sleeping']?>, <?php echo $rowPLAYING['Playing']?>];
						var barColors = ["#0067B1", "#06D6A0", "#FFD166", "#EF476F", "#118AB2"];

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
							display: false
							},
							responsive: true,
							maintainAspectRatio: false,
							legend: {
								position: 'bottom',
								labels: {
									padding: 15,
									fontColor: '#0067B1',
									fontSize: 11
								}
							}
						}
						});
					</script>
				</div>
			</div>
			
			<div class="row">
				<div class="card">
					<a href="barking_chart.php?date=<?php echo $dateID; ?>">
					<div class="card-body">
						<h3 class="card-title">Barking Frequency</h3>
						<div class="frequency-indicator">
							<div class="frequency-bar <?php echo strtolower($barkingFREQ); ?>">
								<div class="frequency-level"></div>
							</div>
							<div class="frequency-value"><?php echo $barkingFREQ?></div>
						</div>
					</div>
					</a>
				</div>
			</div>
		</section>

		<!-- Vital Signs -->
		<section>
			<h2 class="section-title">Vital Signs</h2>
			<div class="row">
				<div class="card">
					<a href="heart_chart.php?date=<?php echo $dateID; ?>">
					<div class="card-body">
						<h3 class="card-title">Heart Rate</h3>
						<div class="progress-circle-container">
							<svg class="progress-circle" width="150" height="150" viewBox="0 0 36 36">
								<path class="progress-circle-bg"
									d="M18 2.0845
									a 15.9155 15.9155 0 0 1 0 31.831
									a 15.9155 15.9155 0 0 1 0 -31.831"
									fill="none"
									stroke="#E6E6E6"
									stroke-width="2"
								/>
								<path class="progress-circle-bar"
									d="M18 2.0845
									a 15.9155 15.9155 0 0 1 0 31.831
									a 15.9155 15.9155 0 0 1 0 -31.831"
									fill="none"
									stroke="url(#gradient-red)"
									stroke-width="2"
									stroke-dasharray="100, 100"
									stroke-dashoffset="<?php echo 100 - min(($rowHEART['Heart_ID'] / 180) * 100, 100); ?>"
								/>
								<text x="18" y="18" text-anchor="middle" dominant-baseline="middle" class="progress-text"><?php echo $rowHEART['Heart_ID']?></text>
								<text x="18" y="23" text-anchor="middle" dominant-baseline="middle" class="progress-label">bpm</text>
							</svg>
						</div>
					</div>
					</a>
				</div>

				<div class="card">
					<a href="temperature_chart.php?date=<?php echo $dateID; ?>">
					<div class="card-body">
						<h3 class="card-title">Temperature</h3>
						<div class="progress-circle-container">
							<svg class="progress-circle" width="150" height="150" viewBox="0 0 36 36">
								<path class="progress-circle-bg"
									d="M18 2.0845
									a 15.9155 15.9155 0 0 1 0 31.831
									a 15.9155 15.9155 0 0 1 0 -31.831"
									fill="none"
									stroke="#E6E6E6"
									stroke-width="2"
								/>
								<path class="progress-circle-bar"
									d="M18 2.0845
									a 15.9155 15.9155 0 0 1 0 31.831
									a 15.9155 15.9155 0 0 1 0 -31.831"
									fill="none"
									stroke="url(#gradient-yellow)"
									stroke-width="2"
									stroke-dasharray="100, 100"
									stroke-dashoffset="<?php echo 100 - min((($rowTEMP['Temp_ID'] - 35) / 7) * 100, 100); ?>"
								/>
								<text x="18" y="18" text-anchor="middle" dominant-baseline="middle" class="progress-text"><?php echo $rowTEMP['Temp_ID']?></text>
								<text x="18" y="23" text-anchor="middle" dominant-baseline="middle" class="progress-label">°C</text>
							</svg>
						</div>
					</div>
					</a>
				</div>

				<div class="card">
					<a href="breath_chart.php?date=<?php echo $dateID; ?>">
					<div class="card-body">
						<h3 class="card-title">Breathing Rate</h3>
						<div class="progress-circle-container">
							<svg class="progress-circle" width="150" height="150" viewBox="0 0 36 36">
								<path class="progress-circle-bg"
									d="M18 2.0845
									a 15.9155 15.9155 0 0 1 0 31.831
									a 15.9155 15.9155 0 0 1 0 -31.831"
									fill="none"
									stroke="#E6E6E6"
									stroke-width="2"
								/>
								<path class="progress-circle-bar"
									d="M18 2.0845
									a 15.9155 15.9155 0 0 1 0 31.831
									a 15.9155 15.9155 0 0 1 0 -31.831"
									fill="none"
									stroke="url(#gradient-blue)"
									stroke-width="2"
									stroke-dasharray="100, 100"
									stroke-dashoffset="<?php echo 100 - min(($rowBREATH['Breath_ID'] / 50) * 100, 100); ?>"
								/>
								<text x="18" y="18" text-anchor="middle" dominant-baseline="middle" class="progress-text"><?php echo $rowBREATH['Breath_ID']?></text>
								<text x="18" y="23" text-anchor="middle" dominant-baseline="middle" class="progress-label">brpm</text>
							</svg>
						</div>
					</div>
					</a>
				</div>
			</div>
		</section>

		<!-- Nutrition -->
		<section>
			<h2 class="section-title">Nutrition & Hydration</h2>
			<div class="row">
				<div class="card">
					<a href="food_intake_chart.php?date=<?php echo $dateID; ?>">
					<div class="card-body">
						<h3 class="card-title">Food Intake</h3>
						<div class="nutrition-indicator">
							<img src="Food.png" alt="Food" class="nutrition-icon">
							<div class="nutrition-value"><?php echo $rowFOOD['Food_ID']?> <span>grams</span></div>
						</div>
					</div>
					</a>
				</div>

				<div class="card">
					<a href="water_intake_chart.php?date=<?php echo $dateID; ?>">
					<div class="card-body">
						<h3 class="card-title">Water Intake</h3>
						<div class="nutrition-indicator">
							<img src="Water.png" alt="Water" class="nutrition-icon">
							<div class="nutrition-value"><?php echo $rowWATER['Water_ID']?> <span>ml</span></div>
						</div>
					</div>
					</a>
				</div>
			</div>
		</section>

		<div class="dashboard-actions">
			<a href="trends.php" class="ui-button">
				<span><i class="fas fa-chart-line"></i> View Long-term Trends</span>
			</a>
		</div>
	</div>

<style>
	.page-title {
		margin-bottom: 5px;
	}
	
	.section-subtitle {
		color: var(--text-secondary);
		margin-bottom: 20px;
	}
	
	.section-title {
		color: var(--primary);
		font-size: 1.5rem;
		margin: 25px 0 15px;
		position: relative;
		padding-left: 15px;
	}
	
	.section-title::before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		height: 100%;
		width: 5px;
		background: var(--gradient);
		border-radius: 3px;
	}
	
	.date-selector {
		margin-bottom: 20px;
	}
	
	.date-form {
		display: flex;
		align-items: flex-end;
		gap: 15px;
	}
	
	.card-value {
		font-size: 1.5rem;
		font-weight: 600;
		color: var(--primary);
		margin-top: 5px;
	}
	
	.progress-circle-container {
		display: flex;
		justify-content: center;
		align-items: center;
		margin-top: 10px;
	}
	
	.progress-circle {
		transform: rotate(-90deg);
	}
	
	.progress-text {
		transform: rotate(90deg);
		font-size: 6px;
		font-weight: bold;
		fill: var(--primary);
	}
	
	.progress-label {
		transform: rotate(90deg);
		font-size: 3px;
		fill: var(--text-secondary);
	}
	
	.energy-comparison {
		display: flex;
		justify-content: space-around;
		align-items: center;
		padding: 10px 0;
	}
	
	.energy-item {
		text-align: center;
	}
	
	.energy-label {
		font-size: 0.9rem;
		color: var(--text-secondary);
		margin-bottom: 5px;
	}
	
	.energy-value {
		font-size: 1.5rem;
		font-weight: 600;
		color: var(--primary);
	}
	
	.energy-divider {
		height: 50px;
		width: 1px;
		background-color: #e0e0e0;
	}
	
	.frequency-indicator {
		display: flex;
		flex-direction: column;
		align-items: center;
		margin-top: 15px;
	}
	
	.frequency-bar {
		width: 80%;
		height: 20px;
		background-color: #e0e0e0;
		border-radius: 10px;
		overflow: hidden;
		margin-bottom: 10px;
	}
	
	.frequency-bar.none .frequency-level {
		width: 0%;
		background: linear-gradient(to right, #06D6A0, #06D6A0);
	}
	
	.frequency-bar.low .frequency-level {
		width: 25%;
		background: linear-gradient(to right, #06D6A0, #FFD166);
	}
	
	.frequency-bar.medium .frequency-level {
		width: 50%;
		background: linear-gradient(to right, #FFD166, #FFA500);
	}
	
	.frequency-bar.high .frequency-level {
		width: 75%;
		background: linear-gradient(to right, #FFA500, #EF476F);
	}
	
	.frequency-level {
		height: 100%;
		transition: width 0.5s ease;
	}
	
	.frequency-value {
		font-size: 1.2rem;
		font-weight: 600;
		color: var(--primary);
	}
	
	.nutrition-indicator {
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 15px;
		margin-top: 15px;
	}
	
	.nutrition-icon {
		width: 50px;
		height: 50px;
		object-fit: contain;
	}
	
	.nutrition-value {
		font-size: 1.5rem;
		font-weight: 600;
		color: var(--primary);
	}
	
	.nutrition-value span {
		font-size: 0.9rem;
		color: var(--text-secondary);
	}
	
	.dashboard-actions {
		display: flex;
		justify-content: center;
		margin: 30px 0;
	}
	
	@media (max-width: 992px) {
		.date-form {
			flex-wrap: wrap;
		}
		
		.date-form .form-group {
			width: 100%;
		}
	}
</style>
	</body>
</html>