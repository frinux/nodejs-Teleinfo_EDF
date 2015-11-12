<?php

// Script which cleans up database


function validateData($trame) {
  if (!array_key_exists('ADCO', $trame) || empty ($trame['ADCO'])) { return false; }
  if (!array_key_exists('OPTARIF', $trame) || empty ($trame['OPTARIF'])) { return false; }
  if (!array_key_exists('ISOUSC', $trame) || empty ($trame['ISOUSC'])) { return false; }
  if (!array_key_exists('HCHC', $trame) || empty ($trame['HCHC'])) { return false; }
  if (!array_key_exists('HCHP', $trame) || empty ($trame['HCHP'])) { return false; }
  if (!array_key_exists('PTEC', $trame) || empty ($trame['PTEC'])) { return false; }
  if (!array_key_exists('IINST', $trame) || empty ($trame['IINST'])) { return false; }
  if (!array_key_exists('IMAX', $trame) || empty ($trame['IMAX'])) { return false; }
  if (!array_key_exists('HHPHC', $trame) || empty ($trame['HHPHC'])) { return false; }

  return true;
}


$db = new PDO('mysql:host=127.0.0.1;dbname=teleinfo', 'root', 'root');
set_time_limit(0); //No time limit, because this script can be long, depending on the amount of data
ob_implicit_flush(1); //Display progression

$rowToRemove = array();

$row = $db->query("SELECT id, ADCO, OPTARIF, ISOUSC, HCHC, HCHP, PTEC, IINST, IMAX, HHPHC, timestamp FROM raw_data ORDER BY id ASC");
$isFirstRow = true;
foreach ($row as $row) {
	if ($isFirstRow) {
		$isFirstRow = false;
		$lastValidRow = $row;
		continue; //skipping first row
	}

	if (validateData($row)) {

		//Row is valid, we update delta values

		$HCHC_delta = $row['HCHC'] - $lastValidRow['HCHC'];
		$HCHP_delta = $row['HCHP'] - $lastValidRow['HCHP'];

		$db->query("UPDATE raw_data SET HCHC_delta=$HCHC_delta, HCHP_delta=$HCHP_delta WHERE id=".$row['id'].";");

		echo "id ".$row['id']." updated"."<br/>";

		$lastValidRow = $row;

	} else {

		//Data is not valid, we must delete it
		$rowToRemove[] = $row['id'];

		echo "id ".$row['id']." must be deleted"."<br/>";
	}
	
}

//Delete invalid rows
$idToDelete = implode(',', $rowToRemove);
$db->query("DELETE FROM raw_data WHERE id IN ($idToDelete)");
echo "ids ".$idToDelete." deleted"."<br/>";

echo "<br/>end.";
