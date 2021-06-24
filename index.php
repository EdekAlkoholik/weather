<?php
session_start();

require_once 'database.php';
$recordsQuery = $db -> query('SELECT * FROM records');
$records = $recordsQuery -> fetchAll();

function getImg($temp) {
	if ($temp < 12) return "cold";
	elseif($temp > 30) return "hot";
	else return "warm";
}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-eqiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Pogoda App</title>
	<link rel="stylesheet" href="main.css">
</head>
<body>
	<div class="conteiner">
		<h1>Poznaj aktualną pogodę w dowolnym miejscu na świecie</h1>
		<form method="post" action="weather.php">
			<label for="city"> Wpisz nazwę miasta którego pogodę chcesz poznać </label>
			<p><input type="text" name="city" placeholder="nazwa miasta" /></p>
			<select name="lang">
			<?php include ("lang.txt") ?>
			</select>
			<?php
				if(isset($_SESSION['errorNote'])){
					echo "<br>".$_SESSION['errorNote'];
					unset($_SESSION['errorNote']);
				}
			?>
			<p><button type="submit" name="submit"> zatwierdź </button></p>
		</form>
		<table>
			<thead>
				<tr><b><th>Wyniki dla</th> <th colspan = "2">Temp</th> <th>Warunki pogodowe</th> <th>Ciśnienie</th> <th>Ostatnia aktualizacja</th></b></tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach (array_reverse($records) as $record) {
					$img = getImg($record['temp']);
					$imgSrc = "resources/".$img.".jpg";
					$date = gmdate("y-m-d H:i:s", $record['date']);
					echo "<tr>	<td>{$record['city']} - {$record['country']}</td>
										<td>{$record['temp']}</td>
										<td><img src='$imgSrc'></td>
										<td>{$record['description']}</td>
										<td>{$record['pressure']}</td>
										<td>{$date}</td>
							</tr>";
					if (++$i == 7) break;
				}
				?>
			</tbody>
		</table>
	</div>
</body>
</html>