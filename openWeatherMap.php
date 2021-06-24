<?php
class OpenWeatherMap {
	
	public function getWeather($query, $lang) {
		$url = $this -> buildUrl($query, $lang);
		return file_get_contents($url);
	}
	
	private function buildUrl($query, $lang) {
		$key = $this -> getKey();
		$url = "http://api.openweathermap.org/data/2.5/weather?q=".$query."&appid=".$key."&lang=".$lang;
		return $url;
	}
	
	private function getKey() {
		$file = 'key.txt';
		return file_get_contents($file);
	}	
}