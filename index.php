<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicación del Tiempo</title>
    <link rel="stylesheet" type="text/css" href="assets\css\styles.css">
</head>
<body>    
<header>
    <div class="cabecera">
        <h1>WeatherWatch</h1>
        <img src="assets\img\iconosol.jpg" alt="Icono sol">
    </div>
</header>

<main>
    <div class="tiempo">
        <section>
            <h2>¿Qué tiempo hace en...?</h2>
            <form id="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                <input type="text" id="location-input" name="location" placeholder="Ej. Madrid" required>
                <button type="submit"><img id="search-icon" src="assets\img\lupa.png" alt="Buscar"></button>
            </form>
            <div class="dropdown">
            <button class="dropdown-button">&#9776;</button>
                <div class="dropdown-content">
                <a href="html\mapa_lluvias.html">Mapa de lluvias</a>
                <a href="html\mapa_temperatura.html">Mapa de temperatura</a>
                </div>
            </div>
        </section>
        <section>
    <div id="weather-info">
    <?php
    require_once 'WeatherAPI.php';
    require_once 'utils.php';

    // Verificar si se ha enviado una solicitud y se ha recibido una respuesta
    if (isset($_GET['location'])) {
        $location = $_GET['location'];

        // Crear una instancia de la clase WeatherAPI
        $weatherAPI = new WeatherAPI();

        try {
            // Obtener datos meteorológicos
            $weatherData = $weatherAPI->getWeatherData($location);

            // Obtener la latitud y longitud si están disponibles
            $latitude = $weatherAPI->getLatitude($weatherData);
            $longitude = $weatherAPI->getLongitude($weatherData);

            // Obtener datos de contaminación del aire si la latitud y longitud están disponibles
            if ($latitude !== null && $longitude !== null) {
                $airPollutionData = $weatherAPI->getAirPollutionData($latitude, $longitude);
            }

            // Procesar datos y generar recomendaciones
            $temperatura = $weatherAPI->getTemperature($weatherData);
            $descripcion = $weatherAPI->getWeatherDescription($weatherData);
            $humedad = $weatherAPI->getHumidity($weatherData);
            $velocidadViento = $weatherAPI->getWindSpeed($weatherData);

            // Obtener la calidad del aire
            $calidadAire = $weatherAPI-> getAirPollution($airPollutionData);
            $mensajeCalidadAire = determinarMensajeCalidadAire($calidadAire);

            // Determinar las recomendaciones de temperatura
            $recomendaciones = determinarRecomendacionesTemperatura($temperatura, $descripcion);               

            // Imprimir la información del tiempo por pantalla
            echo "<p><span style='font-size: 20px;'>Informe del tiempo:</span></p>";        
            echo "<p><strong><span style='font-size: 16px;'>Temperatura:</strong> $temperatura °C</span></p>";
            echo "<p><strong><span style='font-size: 16px;'>Descripción:</strong> $descripcion</span></p>";
            echo "<p><strong><span style='font-size: 16px;'>Humedad:</strong> $humedad%</span></p>";
            echo "<p><strong><span style='font-size: 16px;'>Velocidad del viento:</strong> $velocidadViento m/s</span></p>";
            echo "<p class='recomendaciones'><strong><span style='font-size: 16px; color: #21A5B0'>Recomendaciones:</strong></span> $recomendaciones</p>";
            echo "<p class='calidad-aire'><strong><span style='font-size: 16px; color: #21A5B0'>Calidad del aire:</strong></span> $mensajeCalidadAire</p>"; 
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>
    </div>
</section>
</div>
</main>
<footer>
    <div class="footer">
        <p>&copy; 2024 nahidev</p>
    </div>
</footer>
</body>
</html>