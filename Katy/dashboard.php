<!DOCTYPE html>
<html>
<link rel="stylesheet" href="dashboard.css">

<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

	<body>
		<section>
			<div class="row">
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
			</div>
		</section>

		<!-- <section>
			<svg width="250" height="250" viewBox="0 0 250 250"
				 class="circular-progress" style="--progress: 50"
			>
				<circle class="bg"></circle>
				<circle class="fg"></circle>
			</svg>
		</section> -->
	</body>
</html>