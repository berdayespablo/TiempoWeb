<?php
class WeatherAPI {
    const API_KEY = '79b93cc4e8b99878c153a44f0cd86eef';
    const OPENWEATHER_URL = 'http://api.openweathermap.org/data/2.5/';

    public function getWeatherData($location) {
        $apiUrl = self::OPENWEATHER_URL . "/weather?q=" . urlencode($location) . "&lang=es&units=metric&appid=" . self::API_KEY;
        $response = @file_get_contents($apiUrl);

        if ($response === false) {
            throw new Exception('Error al obtener los datos meteorológicos');
        }

        $weatherData = json_decode($response, true);

        if ($weatherData === null || !isset($weatherData['main']['temp']) || !isset($weatherData['weather'][0]['description']) || !isset($weatherData['main']['humidity']) || !isset($weatherData['wind']['speed'])) {
            throw new Exception('Datos meteorológicos incompletos o inválidos');
        }

        return $weatherData;
    }

    public function getAirPollutionData($latitude, $longitude) {
        $airPollutionApiUrl = self::OPENWEATHER_URL . "/air_pollution?lat=" . $latitude . "&lon=" . $longitude . "&appid=" . self::API_KEY;
        $response = @file_get_contents($airPollutionApiUrl);

        if ($response === false) {
            throw new Exception('Error al obtener los datos de contaminación del aire');
        }

        $airPollutionData = json_decode($response, true);

        if ($airPollutionData === null || !isset($airPollutionData['list'][0]['main']['aqi'])) {
            throw new Exception('Datos de contaminación del aire incompletos o inválidos');
        }

        return $airPollutionData;
    }
}
?>
