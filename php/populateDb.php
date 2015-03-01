<?php

// Script which builds the delta columns from data in raw_data table. Can be usefull for developments

$db = new PDO('mysql:host=127.0.0.1;dbname=teleinfo', 'root', 'root');
set_time_limit(0); //No time limit, because this script can be long, depending on the amount of data
ob_implicit_flush(1); //Display progression

$row = $db->query("SELECT id, HCHC, HCHP, timestamp FROM raw_data ORDER BY id ASC");
$isFirstRow = true;
foreach ($row as $row) {
	if ($isFirstRow) {
		$isFirstRow = false;
		$lastRow = $row;
		continue; //skipping first row
	}

	$HCHC_delta = $row['HCHC'] - $lastRow['HCHC'];
	$HCHP_delta = $row['HCHP'] - $lastRow['HCHP'];

	$db->query("UPDATE raw_data SET HCHC_delta=$HCHC_delta, HCHP_delta=$HCHP_delta WHERE id=".$row['id'].";");

	echo "id ".$row['id']." updated".PHP_EOL;

	$lastRow = $row;
}

die("end");
