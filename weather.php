<?php
session_start();

if (isset($_POST['city'])) {
	
	require_once 'openWeatherMap.php';
	$owm = new OpenWeatherMap();
	
	try{
		$weatherData = $owm -> getWeather($_POST['city'],$_POST['lang']);
	} catch (Exception $e) {
		$_SESSION['errorNote'] = 'OpenWeatherMap exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
	}
	
	$weather = json_decode($weatherData, true);
	if ($weather['cod'] == 200) {
	require_once 'database.php';
	$query = $db -> prepare('INSERT INTO records VALUES(
	NULL, :code, :city, :country, :temp, :date, :description, :pressure)');
	$query -> bindValue(':code', 			$weather['id'], 											PDO::PARAM_INT);
	$query -> bindValue(':city', 				$weather['name'], 										PDO::PARAM_STR);
	$query -> bindValue(':country', 		$weather['sys']['country'], 						PDO::PARAM_STR);
	$query -> bindValue(':temp', 			(($weather['main']['temp']) - 273.15), 		PDO::PARAM_INT);
	$query -> bindValue(':date', 				$weather['dt'], 											PDO::PARAM_INT);
	$query -> bindValue(':description', 	$weather['weather']['0']['description'], 	PDO::PARAM_STR);
	$query -> bindValue(':pressure', 		$weather['main']['pressure'], 					PDO::PARAM_INT);
	$query -> execute();
	}
	else {
		$_SESSION['errorNote'] = "Podana nazwa miasta jest błędna";
	}
	header('Location: index.php');
	
}
else {
	$_SESSION['errorNote'] = "Nie podano nazwy miasta";
	header('Location: index.php');
}
