<?php


try {
    $db = new SQLite3('Elanco-Final.db');
    
    // Get the selected pet from URL parameter
    $selectedPet = isset($_GET['pet_id']) ? $_GET['pet_id'] : 'Basil';
	$dogID = isset($_GET['dog_id']) ? $_GET['dog_id'] : 'CANINE001';
	$dateID = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    
    // Map pet name to dog ID
    if ($selectedPet == 'Snoopy') {
        $dogID = 'CANINE002';
    } elseif ($selectedPet == 'Cooper') {
        $dogID = 'CANINE003';
    } else {
        // Default to Basil
        $dogID = 'CANINE001';
    }
    
    $dateID = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $query = $db->prepare("SELECT strftime('%d-%m-%Y', ?)");
    $query->bindValue(1, $dateID);
    $result = $query->execute();
    $row = $result->fetchArray(SQLITE3_NUM);
    $formattedDate = $row[0];
    
    $weightID = $db->query("SELECT round(avg(Weight), 1) AS 'Weight_ID' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate'");
    $rowWEIGHT = $weightID->fetchArray(SQLITE3_ASSOC);

    $normalID = $db->query("SELECT count(Behaviour_ID) AS 'Normal' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate' AND Behaviour_ID='1'");
    $rowNORMAL = $normalID->fetchArray(SQLITE3_ASSOC);

    $walkingID = $db->query("SELECT count(Behaviour_ID) AS 'Walking' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate' AND Behaviour_ID='2'");
    $rowWALKING = $walkingID->fetchArray(SQLITE3_ASSOC);

    $eatingID = $db->query("SELECT count(Behaviour_ID) AS 'Eating' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate' AND Behaviour_ID='3'");
    $rowEATING = $eatingID->fetchArray(SQLITE3_ASSOC);

    $sleepingID = $db->query("SELECT count(Behaviour_ID) AS 'Sleeping' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate' AND Behaviour_ID='4'");
    $rowSLEEPING = $sleepingID->fetchArray(SQLITE3_ASSOC);

    $playingID = $db->query("SELECT count(Behaviour_ID) AS 'Playing' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate' AND Behaviour_ID='5'");
    $rowPLAYING = $playingID->fetchArray(SQLITE3_ASSOC);

    $barkID = $db->query("SELECT round(avg(Frequency_ID), 1) AS 'Frequency_ID' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate'");
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

    $stepsID = $db->query("SELECT sum(Activity_Level) AS 'Steps_ID' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate'");
    $rowSTEPS = $stepsID->fetchArray(SQLITE3_ASSOC);

    $heartID = $db->query("SELECT round(avg(Heart_Rate), 1) AS 'Heart_ID' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate'");
    $rowHEART = $heartID->fetchArray(SQLITE3_ASSOC);

    $tempID = $db->query("SELECT round(avg(Temperature) , 1) AS 'Temp_ID' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate'");
    $rowTEMP = $tempID->fetchArray(SQLITE3_ASSOC);

    $breathID = $db->query("SELECT round(avg(Breath_Rate) , 1) AS 'Breath_ID' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate'");
    $rowBREATH = $breathID->fetchArray(SQLITE3_ASSOC);

    $foodID = $db->query("SELECT sum(Food_Intake) AS 'Food_ID' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate'");
    $rowFOOD = $foodID->fetchArray(SQLITE3_ASSOC);

    $calID = $db->query("SELECT sum(Calorie_Burnt) AS 'Cal_ID' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate'");
    $rowCAL = $calID->fetchArray(SQLITE3_ASSOC);

    $waterID = $db->query("SELECT sum(Water_Intake) AS 'Water_ID' FROM Activity WHERE Dog_ID='$dogID' AND D_Date='$formattedDate'");
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
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

		<!-- Container div is already provided by navbar.php -->
		<h1 class="page-title">Pet Health Dashboard</h1>
		<p class="section-subtitle">Overview of your pet's health metrics for <?php echo $formattedDate;?></p>
	
		<!-- Date selector -->
		<div class="section">
			<form action="dashboard.php" method="get" class="form">
				<div class="form-group">
					<label for="date" class="form-label">Select Date:</label>
					<input type="date" id="date" name="date" value="<?php echo $dateID; ?>" class="form-control">
				</div>
				<input type="hidden" name="pet_id" value="<?php echo $selectedPet; ?>">
				<button type="submit" class="ui-button">
					<span><i class="fas fa-calendar-check"></i> Update</span>
				</button>
			</form>
		</div>

		<!-- Pet Info -->
		<section class="section">
			<h2 class="section-title">Pet Information</h2>
			<div class="grid">
				<div class="card">
					<div class="card-body">
						<h3 class="card-title">Date</h3>
						<p class="card-value"><?php echo $formattedDate;?></p>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<h3 class="card-title">Pet Name</h3>
						<p class="card-value"><?php echo $selectedPet; ?></p>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<h3 class="card-title">ID</h3>
						<p class="card-value"><?php echo $dogID; ?></p>
					</div>
				</div>
				<div class="card">
					<a href="Weight_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
					<div class="card-body">
						<h3 class="card-title">Weight</h3>
						<p class="card-value"><?php echo $rowWEIGHT['Weight_ID']?> kg</p>
					</div>
					</a>
				</div>
			</div>
		</section>

		<!-- Activity Overview -->
		<section class="section">
			<h2 class="section-title">Activity Overview</h2>
			<div class="grid">
				<div class="card">
					<a href="activity_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
					<div class="card-body">
						<h3 class="card-title">Activity Level</h3>
						<div class="circular-progress-container">
							<div class="circular-progress" data-value="<?php echo min(($rowSTEPS['Steps_ID'] / 10000) * 100, 100); ?>" data-color="#06D6A0">
								<div class="inner-circle">
									<div class="percent-container">
										<span class="percent-value"><?php echo $rowSTEPS['Steps_ID']?></span>
										<span class="percent-label">steps</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					</a>
				</div>

				<div class="card">
					<a href="calorie_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
					<div class="card-body">
						<h3 class="card-title">Energy Balance</h3>
						<div class="energy-balance-container">
							<div class="energy-status">
								<?php 
								$caloriesIn = $rowFOOD['Food_ID'];
								$caloriesOut = $rowCAL['Cal_ID'];
								$energyBalance = $caloriesIn - $caloriesOut;
								$balanceClass = ($energyBalance > 0) ? 'surplus' : (($energyBalance < 0) ? 'deficit' : 'balanced');
								?>
								<div class="energy-balance-label <?php echo $balanceClass; ?>">
									<?php 
									if($energyBalance > 0) echo "Surplus";
									else if($energyBalance < 0) echo "Deficit";
									else echo "Balanced";
									?>
								</div>
								<div class="energy-balance-value <?php echo $balanceClass; ?>">
									<?php echo abs($energyBalance); ?> cal
								</div>
							</div>
							
							<div class="energy-meter">
								<div class="energy-bar-container">
									<div class="energy-item in">
										<div class="energy-icon">
											<svg viewBox="0 0 24 24" width="24" height="24">
												<path fill="#FF9A00" d="M19 13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
											</svg>
										</div>
										<div class="energy-info">
											<div class="energy-label">Calories In</div>
											<div class="energy-value"><?php echo $caloriesIn; ?> cal</div>
										</div>
									</div>
									
									<div class="energy-progress">
										<div class="energy-bar">
											<div class="calories-in" style="width: <?php echo min(($caloriesIn / max($caloriesIn, $caloriesOut)) * 100, 100); ?>%"></div>
											<div class="calories-out" style="width: <?php echo min(($caloriesOut / max($caloriesIn, $caloriesOut)) * 100, 100); ?>%"></div>
										</div>
									</div>
									
									<div class="energy-item out">
										<div class="energy-icon">
											<svg viewBox="0 0 24 24" width="24" height="24">
												<path fill="#06D6A0" d="M19 13H5V11H19V13Z" />
											</svg>
										</div>
										<div class="energy-info">
											<div class="energy-label">Calories Out</div>
											<div class="energy-value"><?php echo $caloriesOut; ?> cal</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					</a>
				</div>

				<div class="card">
					<a href="behaviour_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
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
								position: 'left',
								labels: {
									padding: 10,
									fontColor: '#0067B1',
									fontSize: 11,
									boxWidth: 15
								}
							},
							layout: {
								padding: {
									left: 0,
									right: 0,
									top: 0,
									bottom: 0
								}
							}
						}
						});
					</script>
					</div>
					</a>
				</div>
			</div>
			
			<div class="row">
				<div class="card">
					<a href="barking_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
					<div class="card-body">
						<h3 class="card-title">Barking Frequency</h3>
						<div class="barking-container">
							<div class="barking-meter" data-level="<?php echo strtolower($barkingFREQ); ?>">
								<div class="barking-icon">
									<svg viewBox="0 0 24 24" width="40" height="40">
										<path fill="#FF9A00" d="M14,2H6C4.9,2 4,2.9 4,4V20C4,21.1 4.9,22 6,22H18C19.1,22 20,21.1 20,20V8L14,2M18,20H6V4H13V9H18V20M15,13.58C15,14.3 14.31,15 13.58,15H13.17C13.54,16 13.09,17.14 12.15,17.54C11.26,17.96 10.13,17.5 9.76,16.61L9.03,14.97C8.17,15.12 7.31,14.83 6.69,14.21C6.08,13.59 5.78,12.73 5.93,11.88L7.62,12.5C7.8,12.59 8.04,12.45 8.04,12.26C8.04,12.14 7.95,12.05 7.86,12L6.17,11.37C6.68,10.67 7.33,10.12 8.08,9.87C9.45,9.4 11,9.89 11.9,11.03L11.16,12.35C10.9,12.79 11.25,13.05 11.5,13.13C11.8,13.23 12.24,13.11 12.5,12.67L15,13.58M13.38,11.64L12.53,11.36C12,10.9 11.31,10.37 10.5,10.29C10.09,10.24 9.69,10.31 9.35,10.44L10.36,10.86C10.57,10.93 10.72,11.15 10.66,11.35C10.61,11.56 10.39,11.71 10.19,11.65L9.11,11.21C9.04,11.38 9,11.56 9,11.75C9,11.86 9,11.97 9.03,12.07L10.17,12.5C10.47,12.57 10.67,12.88 10.6,13.18C10.53,13.5 10.22,13.68 9.92,13.62L8.81,13.19C8.92,13.35 9.05,13.49 9.21,13.63C9.58,14 10.07,14.22 10.58,14.22L11.95,14.22C12.23,14.22 12.45,13.97 12.45,13.7V13.53C12.45,13.25 12.23,13 11.95,13H11.78C11.5,13 11.27,12.71 11.27,12.45C11.27,12.18 11.5,11.89 11.78,11.89H12.68C12.92,11.89 13.13,11.78 13.27,11.61C13.36,11.5 13.43,11.31 13.38,11.64Z" />
									</svg>
							</div>
								<div class="barking-info">
									<div class="barking-label"><?php echo $barkingFREQ; ?></div>
									<div class="barking-bars">
										<div class="bar-level bar-1"></div>
										<div class="bar-level bar-2"></div>
										<div class="bar-level bar-3"></div>
										<div class="bar-level bar-4"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					</a>
				</div>
			</div>
		</section>

		<!-- Vital Signs -->
		<section class="section">
			<h2 class="section-title">Vital Signs</h2>
			<div class="grid">
				<div class="card">
					<a href="heart_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
					<div class="card-body">
						<h3 class="card-title">Heart Rate</h3>
						<div class="circular-progress-container">
							<div class="circular-progress" data-value="<?php echo min(($rowHEART['Heart_ID'] / 180) * 100, 100); ?>" data-color="#EF476F">
								<div class="inner-circle">
									<div class="percent-container">
										<span class="percent-value"><?php echo $rowHEART['Heart_ID']?></span>
										<span class="percent-label">bpm</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					</a>
				</div>

				<div class="card">
					<a href="temperature_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
					<div class="card-body">
						<h3 class="card-title">Temperature</h3>
						<div class="temperature-container">
							<div class="temperature-meter" data-value="<?php echo min((($rowTEMP['Temp_ID'] - 35) / 7) * 100, 100); ?>">
								<div class="temperature-icon">
									<svg viewBox="0 0 24 24" width="40" height="40">
										<path fill="#FF6B6B" d="M17,17A5,5 0 0,1 12,22A5,5 0 0,1 7,17C7,15.5 7.65,14.17 8.69,13.25C8.26,12.61 8,11.83 8,11V8C8,5.79 9.79,4 12,4C14.21,4 16,5.79 16,8V11C16,11.83 15.74,12.61 15.31,13.25C16.35,14.17 17,15.5 17,17M14,8H10V11A2,2 0 0,0 12,13A2,2 0 0,0 14,11V8Z" />
							</svg>
								</div>
								<div class="temperature-progress">
									<div class="temp-bar-container">
										<div class="temp-value-display">
											<div class="temp-text"><?php echo $rowTEMP['Temp_ID']?><span>°C</span></div>
										</div>
										<div class="temp-bar">
											<div class="temp-fill"></div>
										</div>
										<div class="temp-scale">
											<span class="scale-min">35°</span>
											<span class="scale-max">42°</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					</a>
				</div>

				<div class="card">
					<a href="breathing_rate_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
					<div class="card-body">
						<h3 class="card-title">Breathing Rate</h3>
						<div class="circular-progress-container">
							<div class="circular-progress" data-value="<?php echo min(($rowBREATH['Breath_ID'] / 50) * 100, 100); ?>" data-color="#0067B1">
								<div class="inner-circle">
									<div class="percent-container">
										<span class="percent-value"><?php echo $rowBREATH['Breath_ID']?></span>
										<span class="percent-label">brpm</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					</a>
				</div>
			</div>
		</section>

		<!-- Nutrition -->
		<section class="section">
			<h2 class="section-title">Nutrition & Hydration</h2>
			<div class="grid">
				<div class="card">
					<a href="food_intake_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
					<div class="card-body">
						<h3 class="card-title">Food Intake</h3>
						<div class="kibble-container">
							<div class="kibble-meter" data-value="<?php echo min(($rowFOOD['Food_ID'] / 500) * 100, 100); ?>">
								<div class="kibble-icon">
									<svg viewBox="0 0 24 24" width="40" height="40">
										<path fill="#d39650" d="M8,14A3,3 0 0,1 5,17A3,3 0 0,1 2,14C2,13.23 2.29,12.53 2.76,12C2.29,11.47 2,10.77 2,10A3,3 0 0,1 5,7A3,3 0 0,1 8,10C8,10.77 7.71,11.47 7.24,12C7.71,12.53 8,13.23 8,14M16,17A3,3 0 0,1 19,14C19,13.23 18.71,12.53 18.24,12C18.71,11.47 19,10.77 19,10A3,3 0 0,1 16,7A3,3 0 0,1 13,10C13,10.77 13.29,11.47 13.76,12C13.29,12.53 13,13.23 13,14A3,3 0 0,1 16,17M9.45,12L9.46,12C9.23,11.86 9.11,11.63 9.04,11.39C8.97,11.15 8.94,10.89 8.94,10.62C8.94,10.34 8.91,10.08 8.83,9.84C8.76,9.59 8.65,9.37 8.46,9.19C8.27,9 8.05,8.89 7.8,8.82C7.55,8.74 7.29,8.72 7,8.72C6.74,8.72 6.5,8.76 6.39,8.85C6.28,8.94 6.24,9.06 6.21,9.14C6.21,9.39 6.36,9.71 6.4,10.08C6.41,10.14 6.38,10.4 6.11,10.4C5.85,10.4 5.66,10.23 5.57,10.14C5.5,10.08 5.38,9.96 5.33,9.79C5.28,9.64 5.33,9.36 5.4,9.14C5.46,8.91 5.58,8.61 5.93,8.32C6.43,7.9 7.09,7.74 8.14,7.74C8.63,7.74 9.14,7.8 9.63,7.9C10.13,8 10.58,8.16 10.97,8.39C11.36,8.64 11.68,8.96 11.89,9.41C12.13,9.85 12.25,10.4 12.25,11.05C12.25,11.67 12.15,12.16 11.97,12.55C11.79,12.91 11.5,13.25 11.2,13.5C10.85,13.79 10.42,14 9.92,14.2C9.43,14.35 8.85,14.5 8.22,14.6L7.88,14.6C7.59,14.6 7.36,14.34 7.36,14.05C7.36,13.8 7.5,13.57 7.74,13.5C7.69,13.5 9.47,13.05 9.47,13.05L9.47,13.03C9.89,12.89 10.17,12.79 10.39,12.62C10.59,12.44 10.69,12.23 10.73,11.97C10.78,11.71 10.7,11.43 10.54,11.17C10.38,10.91 10.18,10.69 9.94,10.5L9.47,12M16,8A2,2 0 0,0 14,10A2,2 0 0,0 14.5,11.25L17.5,11.25A2,2 0 0,0 18,10A2,2 0 0,0 16,8M16,16A2,2 0 0,0 18,14A2,2 0 0,0 17.5,12.75L14.5,12.75A2,2 0 0,0 14,14A2,2 0 0,0 16,16M5,16A2,2 0 0,0 7,14A2,2 0 0,0 6.5,12.75L3.5,12.75A2,2 0 0,0 3,14A2,2 0 0,0 5,16M5,8A2,2 0 0,0 3,10A2,2 0 0,0 3.5,11.25L6.5,11.25A2,2 0 0,0 7,10A2,2 0 0,0 5,8Z"/>
									</svg>
								</div>
								<div class="kibble-progress-wrap">
									<div class="kibble-fill-bar">
										<div class="kibble-fill"></div>
										<div class="kibble-particles"></div>
									</div>
									<div class="kibble-value">
										<span class="value"><?php echo $rowFOOD['Food_ID']?></span>
										<span class="unit">grams</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					</a>
				</div>

				<div class="card">
					<a href="water_intake_chart.php?date=<?php echo $dateID; ?>&pet_id=<?php echo $selectedPet; ?>&dog_id=<?php echo $dogID; ?>">
					<div class="card-body">
						<h3 class="card-title">Water Intake</h3>
						<div class="droplet-container">
							<div class="droplet-meter" data-value="<?php echo min(($rowWATER['Water_ID'] / 1000) * 100, 100); ?>">
								<div class="droplet-icon">
									<svg viewBox="0 0 24 24" width="36" height="36">
										<path fill="#4facfe" d="M12,20C8.69,20 6,17.31 6,14C6,10 12,3.25 12,3.25C12,3.25 18,10 18,14C18,17.31 15.31,20 12,20Z" />
									</svg>
								</div>
								<div class="droplet-progress-wrap">
									<div class="droplet-fill-bar">
										<div class="droplet-fill"></div>
										<div class="droplet-ripples"></div>
									</div>
									<div class="droplet-value">
										<span class="value"><?php echo $rowWATER['Water_ID']?></span>
										<span class="unit">ml</span>
									</div>
								</div>
							</div>
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
		text-align: left;
	}
	
	.section-subtitle {
		color: var(--text-secondary);
		margin-bottom: 20px;
		text-align: left;
	}
	
	.section-title {
		color: var(--primary);
		font-size: 1.5rem;
		margin: 25px 0 15px;
		position: relative;
		padding-left: 15px;
		text-align: left;
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
		justify-content: flex-start;
	}
	
	.card-value {
		font-size: 1.5rem;
		font-weight: 600;
		color: var(--primary);
		margin-top: 5px;
		text-align: left;
	}
	
	.progress-circle-container {
		display: flex;
		justify-content: flex-start;
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
		justify-content: flex-start;
		align-items: center;
		padding: 10px 0;
		gap: 15px;
	}
	
	.energy-item {
		text-align: left;
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
		align-items: flex-start;
		margin-top: 15px;
	}
	
	.frequency-bar {
		width: 100%;
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
		align-items: center;
		gap: 15px;
		margin-top: 15px;
		justify-content: flex-start;
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
		text-align: left;
	}
	
	.nutrition-value span {
		font-size: 0.9rem;
		color: var(--text-secondary);
	}
	
	.dashboard-actions {
		display: flex;
		justify-content: flex-start;
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
	
	.metric-display {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
		margin-top: 15px;
	}
	
	.metric-value {
		font-size: 2.5rem;
		font-weight: 700;
		color: var(--primary);
		line-height: 1;
	}
	
	.metric-label {
		font-size: 1rem;
		color: var(--text-secondary);
		margin-top: 5px;
	}
	
	.circular-progress-container {
		display: flex;
		justify-content: flex-start;
		margin-top: 15px;
	}
	
	.circular-progress {
		position: relative;
		width: 120px;
		height: 120px;
		border-radius: 50%;
		background: #f5f5f5;
		display: flex;
		align-items: center;
		justify-content: center;
		transition: all 1s ease;
		box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
		overflow: hidden;
		transform-style: preserve-3d;
	}
	
	.circular-progress::after {
		content: '';
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 0%;
		background: linear-gradient(to top, var(--progress-color, #06D6A0) 0%, var(--progress-color-light, #08f8b8) 100%);
		border-radius: 0;
		z-index: 1;
		opacity: 0;
		box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1) inset;
		transition: height 1.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
	}
	
	.inner-circle {
		position: absolute;
		width: 75%;
		height: 75%;
		background: linear-gradient(145deg, #ffffff, #f0f0f0);
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1), 
					0 4px 8px rgba(255, 255, 255, 0.8);
		z-index: 2;
		transform: translateY(-2px);
	}
	
	.percent-container {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		transform: translateZ(10px);
		transition: transform 0.3s ease;
	}
	
	.circular-progress:hover .percent-container {
		transform: translateY(-2px) translateZ(10px);
	}
	
	.percent-value {
		font-size: 1.8rem;
		font-weight: 700;
		color: var(--primary);
		text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
		line-height: 1;
	}
	
	.percent-label {
		font-size: 0.8rem;
		color: var(--text-secondary);
		margin-top: 3px;
	}
	
	/* Water Glass Styling */
	.water-glass-container {
		display: flex;
		justify-content: flex-start;
		margin-top: 15px;
		perspective: 800px;
	}
	
	.water-glass {
		position: relative;
		width: 120px;
		height: 140px;
		display: flex;
		flex-direction: column;
		transform-style: preserve-3d;
		transform: rotateY(-15deg) rotateX(5deg);
		transition: transform 0.3s ease;
	}
	
	.water-glass:hover {
		transform: rotateY(-10deg) rotateX(3deg) scale(1.03);
	}
	
	.glass-top {
		height: 10px;
		width: 100%;
		background: rgba(255, 255, 255, 0.5);
		border-radius: 50% 50% 0 0 / 8px 8px 0 0;
		border: 2px solid rgba(255, 255, 255, 0.7);
		border-bottom: none;
		box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
	}
	
	.glass-body {
		flex: 1;
		position: relative;
		border-radius: 5px 5px 0 0;
		background: linear-gradient(to right, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.5));
		box-shadow: 
			inset 0 0 15px rgba(255, 255, 255, 0.5),
			0 5px 10px rgba(0, 0, 0, 0.1);
		overflow: hidden;
		backdrop-filter: blur(5px);
		-webkit-backdrop-filter: blur(5px);
		border: 1px solid rgba(255, 255, 255, 0.7);
		border-bottom: none;
	}
	
	.water-fill {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 0%;
		background: linear-gradient(to bottom, rgba(79, 172, 254, 0.8), rgba(0, 128, 255, 0.9));
		transition: height 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
		box-shadow: 
			0 0 10px rgba(79, 172, 254, 0.5),
			0 0 20px rgba(79, 172, 254, 0.3);
		border-radius: 0 0 3px 3px;
	}
	
	.water-level {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 10px;
		background: linear-gradient(to bottom, 
			rgba(255, 255, 255, 0.4), 
			rgba(255, 255, 255, 0.1));
		border-radius: 50% 50% 0 0;
		filter: blur(1px);
		transform: translateY(5px);
		opacity: 0;
		transition: opacity 0.5s ease, transform 0.5s ease;
	}
	
	.water-value {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		text-align: center;
		z-index: 2;
		text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
	}
	
	.water-value .value {
		display: block;
		font-size: 1.8rem;
		font-weight: 700;
		color: var(--primary);
		line-height: 1;
	}
	
	.water-value .unit {
		display: block;
		font-size: 0.8rem;
		color: var(--text-secondary);
		margin-top: 3px;
	}
	
	.glass-base {
		height: 8px;
		width: 120%;
		margin-left: -10%;
		border-radius: 0 0 50% 50% / 0 0 8px 8px;
		background: linear-gradient(to bottom, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.5));
		box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
		border: 1px solid rgba(255, 255, 255, 0.7);
		border-top: none;
	}

	/* Food Bowl Styling */
	.food-bowl-container {
		display: flex;
		justify-content: flex-start;
		margin-top: 15px;
		perspective: 800px;
	}
	
	.food-bowl {
		position: relative;
		width: 140px;
		height: 100px;
		display: flex;
		flex-direction: column;
		align-items: center;
		transform-style: preserve-3d;
		transform: rotateX(20deg);
		transition: transform 0.3s ease;
	}
	
	.food-bowl:hover {
		transform: rotateX(15deg) scale(1.03);
	}
	
	.bowl-outer {
		position: relative;
		width: 140px;
		height: 70px;
		background: linear-gradient(145deg, #f0f0f0, #e6e6e6);
		border-radius: 50% 50% 40% 40% / 100% 100% 60% 60%;
		box-shadow: 
			0 8px 15px rgba(0, 0, 0, 0.1),
			inset 0 -2px 5px rgba(0, 0, 0, 0.05);
		overflow: hidden;
		z-index: 1;
	}
	
	.bowl-inner {
		position: absolute;
		top: 10%;
		left: 10%;
		width: 80%;
		height: 80%;
		border-radius: 50% 50% 40% 40% / 100% 100% 60% 60%;
		background: linear-gradient(145deg, #ffffff, #f5f5f5);
		box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
		overflow: hidden;
	}
	
	.food-fill {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 0%;
		background: linear-gradient(to bottom, 
			rgba(210, 160, 80, 0.9),
			rgba(180, 130, 60, 0.95));
		transition: height 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
		box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
		border-radius: 0 0 40% 40% / 0 0 60% 60%;
	}
	
	.food-level {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 8px;
		background: linear-gradient(to bottom, 
			rgba(255, 255, 255, 0.3), 
			rgba(255, 255, 255, 0));
		border-radius: 50% 50% 0 0;
		filter: blur(1px);
		transform: translateY(4px);
		opacity: 0;
		transition: opacity 0.5s ease, transform 0.5s ease;
	}
	
	.food-value {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		text-align: center;
		z-index: 2;
		text-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
	}
	
	.food-value .value {
		display: block;
		font-size: 1.8rem;
		font-weight: 700;
		color: var(--primary);
		line-height: 1;
	}
	
	.food-value .unit {
		display: block;
		font-size: 0.8rem;
		color: var(--text-secondary);
		margin-top: 3px;
	}
	
	.bowl-base {
		width: 70%;
		height: 10px;
		margin-top: -5px;
		border-radius: 50%;
		background: linear-gradient(to bottom, rgba(230, 230, 230, 0.8), rgba(200, 200, 200, 0.6));
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		z-index: 0;
	}

	/* Kibble Food Meter Styling */
	.kibble-container {
		display: flex;
		align-items: center;
		margin-top: 15px;
	}
	
	.kibble-meter {
		display: flex;
		align-items: center;
		width: 100%;
	}
	
	.kibble-icon {
		margin-right: 15px;
		padding: 8px;
		background: linear-gradient(145deg, #ffefdc, #f8e0c7);
		border-radius: 12px;
		box-shadow: 0 4px 10px rgba(211, 150, 80, 0.2);
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
		overflow: hidden;
	}
	
	.kibble-icon svg {
		filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.15));
		position: relative;
		z-index: 2;
	}
	
	.kibble-progress-wrap {
		flex: 1;
		margin-left: 10px;
	}
	
	.kibble-fill-bar {
		height: 12px;
		background: #f1f1f1;
		border-radius: 6px;
		position: relative;
		overflow: hidden;
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
		margin-bottom: 8px;
	}
	
	.kibble-fill {
		position: absolute;
		top: 0;
		left: 0;
		height: 100%;
		width: 0;
		border-radius: 6px;
		background: linear-gradient(to right, #d39650, #e8b87a);
		box-shadow: 0 0 8px rgba(211, 150, 80, 0.5);
		transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
	}
	
	.kibble-particles {
		position: absolute;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
		opacity: 0;
		background-image: 
			radial-gradient(circle at 20% 30%, rgba(255,255,255,0.3) 2px, transparent 2px),
			radial-gradient(circle at 40% 70%, rgba(255,255,255,0.3) 3px, transparent 3px),
			radial-gradient(circle at 60% 40%, rgba(255,255,255,0.3) 2.5px, transparent 2.5px),
			radial-gradient(circle at 80% 60%, rgba(255,255,255,0.3) 2px, transparent 2px);
		pointer-events: none;
		transition: opacity 0.5s ease 0.2s;
	}
	
	.kibble-value {
		display: flex;
		align-items: baseline;
	}
	
	.kibble-value .value {
		font-size: 1.5rem;
		font-weight: 700;
		color: var(--primary);
		margin-right: 5px;
	}
	
	.kibble-value .unit {
		font-size: 0.85rem;
		color: var(--text-secondary);
	}
	
	/* Water Droplet Meter Styling */
	.droplet-container {
		display: flex;
		align-items: center;
		margin-top: 15px;
	}
	
	.droplet-meter {
		display: flex;
		align-items: center;
		width: 100%;
	}
	
	.droplet-icon {
		margin-right: 15px;
		padding: 8px;
		background: linear-gradient(145deg, #e0f2ff, #cce5f8);
		border-radius: 50%;
		box-shadow: 0 4px 10px rgba(79, 172, 254, 0.2);
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
		overflow: hidden;
	}
	
	.droplet-icon svg {
		filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.15));
		position: relative;
		z-index: 2;
	}
	
	.droplet-progress-wrap {
		flex: 1;
		margin-left: 10px;
	}
	
	.droplet-fill-bar {
		height: 12px;
		background: #f1f1f1;
		border-radius: 6px;
		position: relative;
		overflow: hidden;
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
		margin-bottom: 8px;
	}
	
	.droplet-fill {
		position: absolute;
		top: 0;
		left: 0;
		height: 100%;
		width: 0;
		border-radius: 6px;
		background: linear-gradient(to right, #4facfe, #00f2fe);
		box-shadow: 0 0 8px rgba(79, 172, 254, 0.5);
		transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
	}
	
	.droplet-ripples {
		position: absolute;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
		opacity: 0;
		background-image: 
			radial-gradient(circle at 30% 50%, rgba(255,255,255,0.5) 1px, transparent 1px),
			radial-gradient(circle at 50% 50%, rgba(255,255,255,0.5) 2px, transparent 2px),
			radial-gradient(circle at 70% 50%, rgba(255,255,255,0.5) 1px, transparent 1px);
		background-size: 10px 10px;
		pointer-events: none;
		transition: opacity 0.5s ease 0.2s;
	}
	
	.droplet-value {
		display: flex;
		align-items: baseline;
	}
	
	.droplet-value .value {
		font-size: 1.5rem;
		font-weight: 700;
		color: var(--primary);
		margin-right: 5px;
	}
	
	.droplet-value .unit {
		font-size: 0.85rem;
		color: var(--text-secondary);
	}

	/* Barking Frequency Meter Styling */
	.bark-meter-container {
		display: flex;
		margin-top: 15px;
	}
	
	.bark-meter {
		display: flex;
		align-items: center;
		width: 100%;
	}
	
	.bark-icon {
		margin-right: 15px;
		padding: 8px;
		background: linear-gradient(145deg, #fff5e0, #ffe8c4);
		border-radius: 12px;
		box-shadow: 0 4px 10px rgba(255, 154, 0, 0.2);
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
		overflow: hidden;
	}
	
	.bark-icon svg {
		filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.15));
	}
	
	.bark-progress-wrap {
		flex: 1;
		margin-left: 10px;
	}
	
	.bark-intensity {
		position: relative;
		height: 60px;
		padding-bottom: 10px;
	}
	
	.bark-wave-container {
		height: 40px;
		width: 100%;
		position: relative;
		border-radius: 6px;
		background: linear-gradient(to right, #f0f0f0, #f5f5f5);
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
		overflow: hidden;
	}
	
	.bark-wave {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-repeat: repeat-x;
		background-position: 0 center;
		opacity: 0;
		transition: opacity 0.5s ease;
	}
	
	.bark-wave.none {
		height: 5px;
		bottom: 5px;
		background: linear-gradient(to right, #06D6A0, #06D6A0);
		opacity: 0;
	}
	
	.bark-wave.low {
		background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 10' width='20' height='10'%3E%3Cpath d='M0,5 Q2.5,3 5,5 T10,5 T15,5 T20,5' fill='none' stroke='%2306D6A0' stroke-width='1.5'/%3E%3C/svg%3E");
		background-size: 20px 20px;
		animation: wave-animation 3s linear infinite;
		opacity: 0;
	}
	
	.bark-wave.medium {
		background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' width='20' height='20'%3E%3Cpath d='M0,10 Q2.5,5 5,10 T10,10 T15,10 T20,10' fill='none' stroke='%23FFD166' stroke-width='1.5'/%3E%3C/svg%3E");
		background-size: 20px 30px;
		animation: wave-animation 2s linear infinite;
		opacity: 0;
	}
	
	.bark-wave.high {
		background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30' width='30' height='30'%3E%3Cpath d='M0,15 Q3.75,5 7.5,15 T15,15 T22.5,15 T30,15' fill='none' stroke='%23EF476F' stroke-width='1.5'/%3E%3C/svg%3E");
		background-size: 30px 40px;
		animation: wave-animation 1s linear infinite;
		opacity: 0;
	}
	
	.bark-meter[data-level="none"] .bark-wave.none {
		opacity: 1;
	}
	
	.bark-meter[data-level="low"] .bark-wave.low {
		opacity: 1;
	}
	
	.bark-meter[data-level="medium"] .bark-wave.medium {
		opacity: 1;
	}
	
	.bark-meter[data-level="high"] .bark-wave.high {
		opacity: 1;
	}
	
	.bark-level-indicator {
		display: flex;
		align-items: center;
		margin-top: 8px;
	}
	
	.bark-current-level {
		height: 12px;
		border-radius: 6px;
		width: 0%;
		transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1), background-color 1s ease;
	}
	
	.bark-meter[data-level="none"] .bark-current-level {
		width: 0%;
		background: #06D6A0;
		box-shadow: 0 0 8px rgba(6, 214, 160, 0.5);
	}
	
	.bark-meter[data-level="low"] .bark-current-level {
		width: 25%;
		background: linear-gradient(to right, #06D6A0, #FFD166);
		box-shadow: 0 0 8px rgba(255, 209, 102, 0.5);
	}
	
	.bark-meter[data-level="medium"] .bark-current-level {
		width: 50%;
		background: linear-gradient(to right, #FFD166, #FF9A00);
		box-shadow: 0 0 8px rgba(255, 154, 0, 0.5);
	}
	
	.bark-meter[data-level="high"] .bark-current-level {
		width: 75%;
		background: linear-gradient(to right, #FF9A00, #EF476F);
		box-shadow: 0 0 8px rgba(239, 71, 111, 0.5);
	}
	
	.bark-label {
		font-size: 1.2rem;
		font-weight: 600;
		color: var(--primary);
		margin-left: 15px;
	}
	
	@keyframes wave-animation {
		0% {
			background-position-x: 0;
		}
		100% {
			background-position-x: 100px;
		}
	}

	/* Thermometer Styling */
	.thermometer-container {
		display: flex;
		justify-content: flex-start;
		margin-top: 15px;
		perspective: 800px;
	}
	
	.thermometer {
		display: flex;
		align-items: center;
		padding: 15px 0;
		transform-style: preserve-3d;
		transform: rotateY(-5deg);
		transition: transform 0.3s ease;
	}
	
	.thermometer:hover {
		transform: rotateY(-8deg) scale(1.02);
	}
	
	.thermometer-icon {
		margin-right: 15px;
		padding: 8px;
		background: linear-gradient(145deg, #fff5e0, #ffe8c4);
		border-radius: 50%;
		box-shadow: 0 4px 10px rgba(255, 209, 102, 0.2);
		display: flex;
		align-items: center;
		justify-content: center;
	}
	
	.thermometer-icon svg {
		filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.15));
	}
	
	.thermometer-body {
		display: flex;
		align-items: center;
		position: relative;
	}
	
	.thermometer-tube {
		width: 20px;
		height: 120px;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 10px;
		box-shadow: 
			inset 0 0 5px rgba(0, 0, 0, 0.1),
			0 5px 10px rgba(0, 0, 0, 0.1);
		position: relative;
		overflow: hidden;
		border: 2px solid #f5f5f5;
	}
	
	.temperature-fill {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 0%;
		background: linear-gradient(to top, #EF476F, #FFD166);
		border-radius: 8px 8px 0 0;
		transition: height 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
	}
	
	.temperature-glow {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 0%;
		background: radial-gradient(
			ellipse at center,
			rgba(255, 255, 255, 0.5) 0%,
			rgba(255, 255, 255, 0) 70%
		);
		transform: translateY(50%);
		opacity: 0.8;
		transition: height 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
	}
	
	.temperature-marks {
		position: absolute;
		left: 30px;
		top: 0;
		height: 100%;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}
	
	.temperature-marks .mark {
		position: absolute;
		left: 0;
		width: 30px;
		font-size: 0.75rem;
		color: var(--text-secondary);
		text-align: left;
		transform: translateY(50%);
	}
	
	.temperature-marks .mark::before {
		content: '';
		position: absolute;
		right: 35px;
		top: 50%;
		width: 5px;
		height: 1px;
		background-color: #ddd;
	}
	
	.temperature-value {
		position: absolute;
		left: 100%;
		top: 50%;
		transform: translateY(-50%);
		margin-left: 40px;
		background: linear-gradient(145deg, #ffffff, #f0f0f0);
		padding: 8px 15px;
		border-radius: 8px;
		box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
		text-align: center;
		min-width: 60px;
	}
	
	.temperature-value .value {
		display: block;
		font-size: 1.8rem;
		font-weight: 700;
		color: var(--primary);
		line-height: 1;
	}
	
	.temperature-value .unit {
		display: block;
		font-size: 0.8rem;
		color: var(--text-secondary);
		margin-top: 3px;
	}

	/* Replace the Thermometer Styling with this modern design */
	/* Temperature Meter Styling */
	.temperature-container {
		display: flex;
		margin-top: 15px;
	}
	
	.temperature-meter {
		display: flex;
		align-items: center;
		width: 100%;
	}
	
	.temperature-icon {
		margin-right: 20px;
		padding: 12px;
		background: linear-gradient(145deg, #fff5f5, #ffeaea);
		border-radius: 14px;
		box-shadow: 0 4px 15px rgba(255, 107, 107, 0.2);
		display: flex;
		align-items: center;
		justify-content: center;
		transform: rotate(0deg);
		transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
	}
	
	.temperature-icon svg {
		filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.15));
	}
	
	.temperature-progress {
		flex: 1;
	}
	
	.temp-bar-container {
		position: relative;
	}
	
	.temp-value-display {
		margin-bottom: 10px;
		display: flex;
		align-items: baseline;
	}
	
	.temp-text {
		font-size: 2rem;
		font-weight: 700;
		color: var(--primary);
		display: flex;
		align-items: baseline;
	}
	
	.temp-text span {
		font-size: 1rem;
		margin-left: 4px;
		font-weight: 500;
		color: var(--text-secondary);
	}
	
	.temp-bar {
		height: 14px;
		background: linear-gradient(to right, #f0f0f0, #f5f5f5);
		border-radius: 7px;
		overflow: hidden;
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
		position: relative;
	}
	
	.temp-fill {
		position: absolute;
		top: 0;
		left: 0;
		height: 100%;
		width: 0%;
		border-radius: 7px;
		background: linear-gradient(to right, #FFD166, #FF6B6B, #FF0D3B);
		box-shadow: 0 0 10px rgba(255, 107, 107, 0.5);
		transition: width 1.2s cubic-bezier(0.34, 1.56, 0.64, 1);
	}
	
	.temp-scale {
		display: flex;
		justify-content: space-between;
		margin-top: 5px;
		font-size: 0.75rem;
		color: var(--text-secondary);
	}
	
	.scale-min, .scale-max {
		padding: 0 5px;
	}

	/* Replace the Barking Frequency Meter Styling with this cleaner design */
	/* Barking Frequency Styling */
	.barking-container {
		display: flex;
		margin-top: 15px;
	}
	
	.barking-meter {
		display: flex;
		align-items: center;
		width: 100%;
	}
	
	.barking-icon {
		flex-shrink: 0;
		margin-right: 20px;
		padding: 12px;
		background: linear-gradient(145deg, #fff5e0, #ffe8c4);
		border-radius: 14px;
		box-shadow: 0 4px 15px rgba(255, 154, 0, 0.2);
		display: flex;
		align-items: center;
		justify-content: center;
		transform: scale(1);
		transition: transform 0.3s ease, box-shadow 0.3s ease;
		position: relative;
		overflow: hidden;
	}
	
	.barking-icon svg {
		filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.15));
	}
	
	.barking-info {
		flex: 1;
	}
	
	.barking-label {
		font-size: 1.8rem;
		font-weight: 700;
		color: var(--primary);
		margin-bottom: 12px;
	}
	
	.barking-bars {
		display: flex;
		align-items: flex-end;
		height: 40px;
		gap: 6px;
	}
	
	.bar-level {
		flex: 1;
		height: 10%;
		border-radius: 4px;
		background: #e0e0e0;
		transition: height 0.8s cubic-bezier(0.34, 1.56, 0.64, 1), 
					background-color 0.8s ease;
	}
	
	/* Bar states based on frequency level */
	.barking-meter[data-level="none"] .bar-level {
		background: #e0e0e0;
		height: 10%;
	}
	
	.barking-meter[data-level="low"] .bar-1 {
		background: #06D6A0;
		height: 40%;
	}
	
	.barking-meter[data-level="medium"] .bar-1 {
		background: #06D6A0;
		height: 40%;
	}
	
	.barking-meter[data-level="medium"] .bar-2 {
		background: #FFD166;
		height: 65%;
	}
	
	.barking-meter[data-level="high"] .bar-1 {
		background: #06D6A0;
		height: 40%;
	}
	
	.barking-meter[data-level="high"] .bar-2 {
		background: #FFD166;
		height: 65%;
	}
	
	.barking-meter[data-level="high"] .bar-3 {
		background: #FF9A00;
		height: 85%;
	}
	
	.barking-meter[data-level="high"] .bar-4 {
		background: #EF476F;
		height: 100%;
	}
</style>
	/* Replace the Barking animation with this cleaner code */
	    /* Barking frequency animation*/
<script>
		const barkingMeters = document.querySelectorAll('.barking-meter');
		
		barkingMeters.forEach(meter => {
			const level = meter.getAttribute('data-level');
			const icon = meter.querySelector('.barking-icon');
			
			// Initialize bars (in case we want to add animation entry)
			const bars = meter.querySelectorAll('.bar-level');
			bars.forEach(bar => {
				bar.style.height = '0%';
			});
			
			// Animate bars after a delay
			setTimeout(() => {
				// Animation will happen via CSS transitions
				addBarkingPulse(meter, level);
			}, 300);
		});
		
		// Add subtle animation for bark icon
		function addBarkingPulse(meter, level) {
			if (level === 'none') return;
			
			const icon = meter.querySelector('.barking-icon');
			let pulseIntensity = 0.01;
			
			if (level === 'medium') pulseIntensity = 0.015;
			if (level === 'high') pulseIntensity = 0.02;
			
			let scale = 1;
			let growing = true;
			
			const pulse = setInterval(() => {
				if (growing) {
					scale += pulseIntensity;
					if (scale >= 1.08) growing = false;
				} else {
					scale -= pulseIntensity;
					if (scale <= 0.96) growing = true;
				}
				
				icon.style.transform = `scale(${scale})`;
				
				// Add glow effect as it pulses
				let glowSize = Math.max(0, (scale - 1) * 60);
				icon.style.boxShadow = `0 4px 15px rgba(255, 154, 0, ${0.2 + (scale-1)*2}), 
										0 0 ${glowSize}px rgba(255, 154, 0, ${(scale-1)*2})`;
				
			}, 50);
			
			// Stop animation after a while
			setTimeout(() => {
				clearInterval(pulse);
				icon.style.transform = 'scale(1)';
				icon.style.boxShadow = '0 4px 15px rgba(255, 154, 0, 0.2)';
			}, 8000);
		}
</script>

<style>
	/* Additional style to help with the fill animation */
	.circular-progress.fill-visible::after {
		height: var(--progress-fill) !important;
		opacity: var(--fill-opacity, 0.9) !important;
		transition: height 1.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
	}
	
	.circular-progress::after {
		content: '';
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 0%;
		background: linear-gradient(to top, var(--progress-color, #06D6A0) 0%, var(--progress-color-light, #08f8b8) 100%);
		border-radius: 0;
		z-index: 1;
		opacity: 0;
		box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1) inset;
		transition: height 1.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
	}
	
	/* Create 3D fill effect with shadows and highlights */
	.circular-progress::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 100%);
		border-radius: 50%;
		z-index: 2;
		pointer-events: none;
	}
	
	/* Inner circle highlight for better depth */
	.inner-circle::after {
		content: '';
		position: absolute;
		top: 5%;
		left: 8%;
		width: 30%;
		height: 30%;
		border-radius: 50%;
		background: linear-gradient(135deg, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0) 100%);
		pointer-events: none;
	}
	
	/* Enhanced circular progress colors */
	.circular-progress[data-color="#06D6A0"]::after {
		background: linear-gradient(to top, 
			#06D6A0 0%, 
			#08f8b8 100%);
		box-shadow: inset 0 -10px 20px rgba(6, 214, 160, 0.6);
		--progress-color: #06D6A0;
		--progress-color-light: #08f8b8;
	}
	
	.circular-progress[data-color="#EF476F"]::after {
		background: linear-gradient(to top, 
			#EF476F 0%, 
			#ff7a9b 100%);
		box-shadow: inset 0 -10px 20px rgba(239, 71, 111, 0.6);
		--progress-color: #EF476F;
		--progress-color-light: #ff7a9b;
	}
	
	.circular-progress[data-color="#0067B1"]::after {
		background: linear-gradient(to top, 
			#0067B1 0%, 
			#0087e5 100%);
		box-shadow: inset 0 -10px 20px rgba(0, 103, 177, 0.6);
		--progress-color: #0067B1;
		--progress-color-light: #0087e5;
	}
	
	.circular-progress[data-color="#FFD166"]::after {
		background: linear-gradient(to top, 
			#FFD166 0%, 
			#ffe099 100%);
		box-shadow: inset 0 -10px 20px rgba(255, 209, 102, 0.6);
		--progress-color: #FFD166;
		--progress-color-light: #ffe099;
	}
	
	/* Add glowing circles */
	.circular-progress[data-color="#06D6A0"] {
		box-shadow: 0 0 25px rgba(6, 214, 160, 0.3);
	}
	
	.circular-progress[data-color="#EF476F"] {
		box-shadow: 0 0 25px rgba(239, 71, 111, 0.3);
	}
	
	.circular-progress[data-color="#0067B1"] {
		box-shadow: 0 0 25px rgba(0, 103, 177, 0.3);
	}
	
	.circular-progress[data-color="#FFD166"] {
		box-shadow: 0 0 25px rgba(255, 209, 102, 0.3);
	}
	
	/* Add inner glow effects */
	.circular-progress[data-color="#06D6A0"] .inner-circle {
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1),
				0 4px 8px rgba(255, 255, 255, 0.8),
				0 0 12px rgba(6, 214, 160, 0.2);
	}
	
	.circular-progress[data-color="#EF476F"] .inner-circle {
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1),
				0 4px 8px rgba(255, 255, 255, 0.8),
				0 0 12px rgba(239, 71, 111, 0.2);
	}
	
	.circular-progress[data-color="#0067B1"] .inner-circle {
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1),
				0 4px 8px rgba(255, 255, 255, 0.8),
				0 0 12px rgba(0, 103, 177, 0.2);
	}
	
	.circular-progress[data-color="#FFD166"] .inner-circle {
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1),
				0 4px 8px rgba(255, 255, 255, 0.8),
				0 0 12px rgba(255, 209, 102, 0.2);
	}

	/* Progress fill style */
	.progress-fill {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 0%;
		background: linear-gradient(to top, #06D6A0 0%, #08f8b8 100%);
		border-radius: 0;
		z-index: 1;
		box-shadow: inset 0 -10px 20px rgba(0, 0, 0, 0.1);
		transition: height 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
	}

	/* 3D effect for progress fill */
	.progress-fill::after {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 30%;
		background: linear-gradient(to bottom, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 100%);
		border-radius: 0;
		pointer-events: none;
	}
	
	/* Updated Energy Balance styles */
	.energy-balance-container {
		display: flex;
		flex-direction: column;
		margin-top: 15px;
	}
	
	.energy-status {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 20px;
	}
	
	.energy-balance-label {
		font-size: 1.1rem;
		font-weight: 600;
		padding: 6px 12px;
		border-radius: 20px;
		color: white;
	}
	
	.energy-balance-label.surplus {
		background-color: #FF9A00;
	}
	
	.energy-balance-label.deficit {
		background-color: #06D6A0;
	}
	
	.energy-balance-label.balanced {
		background-color: #0067B1;
	}
	
	.energy-balance-value {
		font-size: 1.5rem;
		font-weight: 700;
	}
	
	.energy-balance-value.surplus {
		color: #FF9A00;
	}
	
	.energy-balance-value.deficit {
		color: #06D6A0;
	}
	
	.energy-balance-value.balanced {
		color: #0067B1;
	}
	
	.energy-meter {
		margin-top: 10px;
	}
	
	.energy-bar-container {
		display: flex;
		flex-direction: column;
		gap: 15px;
	}
	
	.energy-item {
		display: flex;
		align-items: center;
		gap: 10px;
	}
	
	.energy-icon {
		padding: 8px;
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
	}
	
	.energy-item.in .energy-icon {
		background: linear-gradient(145deg, #fff5e0, #ffe8c4);
	}
	
	.energy-item.out .energy-icon {
		background: linear-gradient(145deg, #e0f8f0, #d0f0e6);
	}
	
	.energy-info {
		flex: 1;
	}
	
	.energy-progress {
		margin: 5px 0;
	}
	
	.energy-bar {
		height: 16px;
		background: #f5f5f5;
		border-radius: 8px;
		overflow: hidden;
		position: relative;
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
	}
	
	.calories-in, .calories-out {
		height: 50%;
		position: absolute;
		left: 0;
		transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
	}
	
	.calories-in {
		top: 0;
		background: linear-gradient(to right, #FFD166, #FF9A00);
		border-radius: 8px 8px 0 0;
	}
	
	.calories-out {
		bottom: 0;
		background: linear-gradient(to right, #06D6A0, #079670);
		border-radius: 0 0 8px 8px;
	}
</style>
	</body>
</html>

<script>
// Add pulsing effect to circular progress indicators and ensure fill is visible
document.addEventListener('DOMContentLoaded', function() {
    const circles = document.querySelectorAll('.circular-progress');
    
    circles.forEach(circle => {
        const color = circle.getAttribute('data-color');
        const rgbColor = hexToRgb(color);
        const value = parseFloat(circle.getAttribute('data-value'));
        
        // First set the fill height to 0
        setTimeout(() => {
            // Animate fill height based on the data-value
            circle.style.setProperty('--progress-height', `${value}%`);
            circle.style.setProperty('--fill-height', `${value}%`);
            circle.style.setProperty('--height-after', `${value}%`);
            circle.style.setProperty('--height-visible', '1');
            circle.classList.add('fill-visible');
            
            // Apply the height directly to the ::after pseudo-element
            const computedStyle = window.getComputedStyle(circle, '::after');
            if (computedStyle) {
                const afterElement = circle.querySelector('::after');
                if (afterElement) {
                    afterElement.style.height = `${value}%`;
                }
            }
            
            // Add stronger shadow effect for visual depth
            circle.style.boxShadow = `0 8px 25px rgba(${rgbColor}, 0.3)`;
        }, 300);
        
        // Only add pulse to indicators with significant values
        if (value > 30) {
            let glowSize = 15;
            let growing = true;
            
            const pulse = setInterval(() => {
                if (growing) {
                    glowSize += 0.5;
                    if (glowSize >= 25) growing = false;
                } else {
                    glowSize -= 0.5;
                    if (glowSize <= 15) growing = true;
                }
                
                // Update glow size
                circle.style.boxShadow = `0 0 ${glowSize}px rgba(${rgbColor}, 0.3)`;
                
                // Add slight scaling effect for more visual impact
                const scale = 1 + ((glowSize - 15) / 200);
                circle.style.transform = `scale(${scale})`;
                
            }, 80);
            
            // Stop animation after a while
            setTimeout(() => {
                clearInterval(pulse);
                circle.style.boxShadow = `0 0 25px rgba(${rgbColor}, 0.3)`;
                circle.style.transform = 'scale(1)';
            }, 8000);
        }
    });
    
    // Convert hex to RGB
    function hexToRgb(hex) {
        hex = hex.replace('#', '');
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        return `${r}, ${g}, ${b}`;
    }
});
</script>

<script>
// Simple, direct approach to circular progress
document.addEventListener('DOMContentLoaded', function() {
    // Fix for circular progress indicators
    const circles = document.querySelectorAll('.circular-progress');
    
    circles.forEach(circle => {
        const value = parseInt(circle.getAttribute('data-value')) || 0;
        const color = circle.getAttribute('data-color') || '#34c759';
        
        // Create and append a fill element instead of relying on ::after
        const fillElement = document.createElement('div');
        fillElement.className = 'progress-fill';
        fillElement.style.position = 'absolute';
        fillElement.style.bottom = '0';
        fillElement.style.left = '0';
        fillElement.style.width = '100%';
        fillElement.style.height = '0%';
        fillElement.style.transition = 'height 1.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
        fillElement.style.zIndex = '1';
        
        // Set color based on data-color
        if (color === '#06D6A0') {
            fillElement.style.background = 'linear-gradient(to top, #06D6A0 0%, #08f8b8 100%)';
            fillElement.style.boxShadow = 'inset 0 -10px 20px rgba(6, 214, 160, 0.6)';
        } else if (color === '#EF476F') {
            fillElement.style.background = 'linear-gradient(to top, #EF476F 0%, #ff7a9b 100%)';
            fillElement.style.boxShadow = 'inset 0 -10px 20px rgba(239, 71, 111, 0.6)';
        } else if (color === '#0067B1') {
            fillElement.style.background = 'linear-gradient(to top, #0067B1 0%, #0087e5 100%)';
            fillElement.style.boxShadow = 'inset 0 -10px 20px rgba(0, 103, 177, 0.6)';
        } else if (color === '#FFD166') {
            fillElement.style.background = 'linear-gradient(to top, #FFD166 0%, #ffe099 100%)';
            fillElement.style.boxShadow = 'inset 0 -10px 20px rgba(255, 209, 102, 0.6)';
        }
        
        // Add fill element before the inner circle
        const innerCircle = circle.querySelector('.inner-circle');
        circle.insertBefore(fillElement, innerCircle);
        
        // Animate the fill after a short delay
        setTimeout(() => {
            fillElement.style.height = `${value}%`;
        }, 300);
        
        // Add pulsing effect for higher values
        if (value > 30) {
            let pulseSize = value;
            let growing = true;
            
            const pulse = setInterval(() => {
                if (growing) {
                    pulseSize += 0.8;
                    if (pulseSize >= value + 5) growing = false;
                } else {
                    pulseSize -= 0.8;
                    if (pulseSize <= value) growing = true;
                }
                
                fillElement.style.height = `${pulseSize}%`;
                
            }, 50);
            
            // Stop the pulse after 8 seconds
            setTimeout(() => {
                clearInterval(pulse);
                fillElement.style.height = `${value}%`;
            }, 8000);
        }
    });
    
    // Initialize other elements from the existing code
    animateKibble();
    animateDroplets();
    animateTemperature();
    animateBarking();
    
    // Helper functions
    function animateKibble() {
        const kibbleMeters = document.querySelectorAll('.kibble-meter');
        
        kibbleMeters.forEach(meter => {
            const value = parseFloat(meter.getAttribute('data-value'));
            const fillElement = meter.querySelector('.kibble-fill');
            const particlesElement = meter.querySelector('.kibble-particles');
            
            if (fillElement) {
                // Animate the fill
                setTimeout(() => {
                    fillElement.style.width = `${value}%`;
                    
                    // Show particles
                    if (particlesElement) {
                        setTimeout(() => {
                            particlesElement.style.opacity = '1';
                        }, 500);
                    }
                }, 300);
            }
        });
    }
    
    function animateDroplets() {
        const dropletMeters = document.querySelectorAll('.droplet-meter');
        
        dropletMeters.forEach(meter => {
            const value = parseFloat(meter.getAttribute('data-value'));
            const fillElement = meter.querySelector('.droplet-fill');
            const ripplesElement = meter.querySelector('.droplet-ripples');
            
            if (fillElement) {
                // Animate the fill
                setTimeout(() => {
                    fillElement.style.width = `${value}%`;
                    
                    // Show ripples
                    if (ripplesElement) {
                        setTimeout(() => {
                            ripplesElement.style.opacity = '1';
                        }, 500);
                    }
                }, 300);
            }
        });
    }
    
    function animateTemperature() {
        const tempMeters = document.querySelectorAll('.temperature-meter');
        
        tempMeters.forEach(meter => {
            const value = parseFloat(meter.getAttribute('data-value'));
            const fillElement = meter.querySelector('.temp-fill');
            const icon = meter.querySelector('.temperature-icon');
            
            if (fillElement) {
                // Animate the fill
                setTimeout(() => {
                    fillElement.style.width = `${value}%`;
                    
                    // Rotate the icon based on temperature
                    if (icon && value > 50) {
                        const rotation = Math.min(value / 5, 15);
                        icon.style.transform = `rotate(${rotation}deg)`;
                    }
                }, 300);
            }
        });
    }
    
    function animateBarking() {
        const barkingMeters = document.querySelectorAll('.barking-meter');
        
        barkingMeters.forEach(meter => {
            const level = meter.getAttribute('data-level');
            const icon = meter.querySelector('.barking-icon');
            
            // Add pulse effect to the icon
            if (icon && level !== 'none') {
                let scale = 1;
                let growing = true;
                let pulseIntensity = level === 'high' ? 0.02 : level === 'medium' ? 0.015 : 0.01;
                
                const pulse = setInterval(() => {
                    if (growing) {
                        scale += pulseIntensity;
                        if (scale >= 1.08) growing = false;
                    } else {
                        scale -= pulseIntensity;
                        if (scale <= 0.96) growing = true;
                    }
                    
                    icon.style.transform = `scale(${scale})`;
                    
                    // Add glow effect
                    let glowSize = Math.max(0, (scale - 1) * 60);
                    icon.style.boxShadow = `0 4px 15px rgba(255, 154, 0, ${0.2 + (scale-1)*2}), 
                                         0 0 ${glowSize}px rgba(255, 154, 0, ${(scale-1)*2})`;
                }, 50);
                
                // Stop after 8 seconds
                setTimeout(() => {
                    clearInterval(pulse);
                    icon.style.transform = 'scale(1)';
                    icon.style.boxShadow = '0 4px 15px rgba(255, 154, 0, 0.2)';
                }, 8000);
            }
        });
    }
    
    // Animate Energy Balance
    animateEnergyBalance();
    
    // ... existing functions ...
    
    function animateEnergyBalance() {
        const caloriesIn = document.querySelector('.calories-in');
        const caloriesOut = document.querySelector('.calories-out');
        
        if (caloriesIn && caloriesOut) {
            // Start with zero width
            caloriesIn.style.width = '0%';
            caloriesOut.style.width = '0%';
            
            // Animate to target width after a delay
            setTimeout(() => {
                const targetWidthIn = caloriesIn.style.width;
                const targetWidthOut = caloriesOut.style.width;
                
                // Animate in with slight delay between each
                caloriesIn.style.width = '0%';
                caloriesOut.style.width = '0%';
                
                setTimeout(() => {
                    caloriesIn.style.width = targetWidthIn;
                    
                    setTimeout(() => {
                        caloriesOut.style.width = targetWidthOut;
                    }, 300);
                }, 300);
            }, 300);
        }
    }
});
</script>

<!-- Notification container -->
<div id="notification-container"></div>

<!-- Include notification script -->
<script src="notification.js"></script>

<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Check pet vital signs and show alerts if needed
        const heartRate = <?php echo $rowHEART['Heart_ID'] ?: 0; ?>;
        const temperature = <?php echo $rowTEMP['Temp_ID'] ?: 0; ?>;
        const steps = <?php echo $rowSTEPS['Steps_ID'] ?: 0; ?>;
        const waterIntake = <?php echo $rowWATER['Water_ID'] ?: 0; ?>;
        
        // Use the function from notification.js
        setTimeout(() => {
            checkVitalSigns(heartRate, temperature, steps, waterIntake);
        }, 1000);
    });
</script>

<script>
    // Function to fetch data for a specific hour
    function fetchHourData(hour) {
        fetch(`fetch_dog_data.php?hour=${hour}&date=${currentDate}&dog_id=<?php echo $dogID; ?>`)
            .then(response => response.json())
            .then(data => {
                // Process the data and update the dashboard
                updateDashboard(data);
            })
            .catch(error => console.error('Error:', error));
    }

    // Function to fetch data for a specific date
    function fetchDateData(date) {
        fetch(`fetch_dog_data.php?date=${date}&dog_id=<?php echo $dogID; ?>`)
            .then(response => response.json())
            .then(data => {
                // Process the data and update the dashboard
                updateDashboard(data);
            })
            .catch(error => console.error('Error:', error));
    }
</script>
</body>
</html>