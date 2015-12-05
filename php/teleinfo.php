<?php

//
//  Parameters
//

//TELEINFO
define("TELEINFO_SOURCE", '/dev/ttyAMA0');

//MySQL
define("SAVE_IN_MYSQL_DATABASE", true);
define("MYSQL_PDO", 'mysql:host=127.0.0.1;dbname=teleinfo');
define("MYSQL_USER", 'root');
define("MYSQL_PASSWORD", 'root');

//JSON
define("SAVE_IN_JSON_FILES", true);
define("JSON_DIRECTORY", "/tmp/"); //Don't forget trailing slash

//EMONCMS
define("SAVE_IN_EMONCMS", false); //TODO

//
//  renvoie une trame teleinfo complete sous forme d'array
//
function getTeleinfo () {

    $handle = fopen (TELEINFO_SOURCE, "r"); // ouverture du flux

    while (fread($handle, 1) != chr(2)); // on attend la fin d'une trame pour commencer a avec la trame suivante

    $char  = '';
    $trame = '';
    $datas = '';

    while ($char != chr(2)) { // on lit tous les caracteres jusqu'a la fin de la trame
      $char = fread($handle, 1);
      if ($char != chr(2)){
        $trame .= $char;
      }
    }

    fclose ($handle); // on ferme le flux

    $trame = chop(substr($trame,1,-1)); // on supprime les caracteres de debut et fin de trame

    $messages = explode(chr(10), $trame); // on separe les messages de la trame

    foreach ($messages as $key => $message) {
      $message = explode (' ', $message, 3); // on separe l'etiquette, la valeur et la somme de controle de chaque message
      if(!empty($message[0]) && !empty($message[1])) {
        $etiquette = $message[0];
        $valeur    = $message[1];
        $datas[$etiquette] = $valeur; // on stock les etiquettes et les valeurs de l'array datas
      }
    }

	$datas['timestamp'] = date('Y-m-d H:i:s');
  $datas['unix_timestamp'] = time();

  return $datas;

}

//
// returns if the received message is valid
//
function validateTrame($trame) {
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


$data = array();
//Loop to avoid invalid data
while (!validateTrame($data)) {
	$data = getTeleinfo();
}

if (SAVE_IN_MYSQL_DATABASE) {

  $db = new PDO(MYSQL_PDO, MYSQL_USER, MYSQL_PASSWORD);

  //Get last row result
  $stmt = $db->prepare("SELECT HCHC, HCHP FROM raw_data ORDER BY id DESC LIMIT 1");
  $stmt->execute();
  $lastRow = $stmt->fetch();

  //Calculate delta
  $calculated_data = array();
  $calculated_data['HCHC_delta'] = $data['HCHC'] - $lastRow['HCHC'];
  $calculated_data['HCHP_delta'] = $data['HCHP'] - $lastRow['HCHP'];

  //Insert data
  $stmt = $db->prepare("
    INSERT INTO raw_data(ADCO,OPTARIF,ISOUSC,HCHC,HCHP,PTEC,IINST,IMAX,HHPHC,timestamp,HCHC_delta,HCHP_delta) 
    VALUES(:ADCO,:OPTARIF,:ISOUSC,:HCHC,:HCHP,:PTEC,:IINST,:IMAX,:HHPHC,:timestamp,:HCHC_delta,:HCHP_delta)
    ");
  $stmt->execute(
    array(
      ':ADCO' => $data['ADCO'], 
      ':OPTARIF' => $data['OPTARIF'], 
      ':ISOUSC' => $data['ISOUSC'], 
      ':HCHC' => $data['HCHC'], 
      ':HCHP' => $data['HCHP'], 
      ':PTEC' => $data['PTEC'], 
      ':IINST' => $data['IINST'], 
      ':IMAX' => $data['IMAX'], 
      ':HHPHC' => $data['HHPHC'], 
      ':timestamp' => $data['timestamp'],
      ':HCHC_delta' => $calculated_data['HCHC_delta'],
      ':HCHP_delta' => $calculated_data['HCHP_delta']
    )
  );
}

if (SAVE_IN_JSON_FILES) {

  file_put_contents(JSON_DIRECTORY.$data['unix_timestamp'].'.json', json_encode($data));

}

if (SAVE_IN_EMONCMS) {
  //TODO
}