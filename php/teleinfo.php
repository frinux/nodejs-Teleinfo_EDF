<?php

//
//  renvoie une trame teleinfo complete sous forme d'array
//
function getTeleinfo () {

    $handle = fopen ('/dev/ttyAMA0', "r"); // ouverture du flux

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

    return $datas;

}


$data = array();
//On boucle pour éviter les trames vides
while (!isset($data['ADCO'])) {
	$data = getTeleinfo();
}

$db = new PDO('mysql:host=127.0.0.1;dbname=teleinfo', 'root', 'root');

//Get last row result
$stmt = $db->prepare("SELECT HCHC, HCHP FROM raw_data ORDER BY id DESC LIMIT 1");
$stmt->execute();
$lastRow = $stmt->fetch();

//Calculate delta
$data['HCHC_delta'] = $data['HCHC'] - $lastRow['HCHC'];
$data['HCHP_delta'] = $data['HCHP'] - $lastRow['HCHP'];

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
    ':HCHC_delta' => $data['HCHC_delta'],
    ':HCHP_delta' => $data['HCHP_delta']
  )
);
