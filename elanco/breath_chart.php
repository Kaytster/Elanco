<?php
// Get the date parameter from the URL
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Redirect to the correctly named file
header("Location: breathing_rate_chart.php?date=$date");
exit;
?> 