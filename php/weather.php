<?php

//Get Temperature from Wunderground
function getTemperature () {

  $json_string = file_get_contents("http://api.wunderground.com/api/576be17743f6c502/conditions/q/France/Nantes.json");
  $parsed_json = json_decode($json_string);
  $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};

  $datas['timestamp'] = date('Y-m-d H:i:s');
  $datas['wunderground_temperature'] = $temp_c;

  return $datas;
}


$data = array();
//On boucle pour éviter les données vides
while (!isset($data['wunderground_temperature'])) {
  $data = getTemperature();
}

$db = new PDO('mysql:host=127.0.0.1;dbname=teleinfo', 'root', 'root');

$stmt = $db->prepare("
  INSERT INTO weather_data(timestamp,wunderground_temperature) 
  VALUES(:timestamp,:wunderground_temperature)
  ");
$stmt->execute(
  array(
    ':timestamp' => $data['timestamp'],
    ':wunderground_temperature' => $data['wunderground_temperature']
  )
);
