<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicación del Tiempo</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>    
<header>
    <div class="cabecera">
        <h1>WeatherWatch</h1>
        <img src="imgs/iconosol.jpg" alt="Icono sol">
    </div>
</header>

<main>
    <div class="tiempo">
        <section>
            <h2>¿Qué tiempo hace en...?</h2>
            <form id="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                <input type="text" id="location-input" name="location" placeholder="Ej. Madrid" required>
                <button type="submit"><img id="search-icon" src="imgs/lupa.png" alt="Buscar"></button>
            </form>
            <div class="dropdown">
            <button class="dropdown-button">&#9776;</button>
                <div class="dropdown-content">
                <a href="mapa_lluvias.html">Mapa de lluvias</a>
                <a href="mapa_temperatura.html">Mapa de temperatura</a>
                </div>
            </div>
        </section>
        <section>
    <div id="weather-info">
        <?php
        // Isset para verificar si se ha enviado una solicitud y se ha recibido una respuesta
        if (isset($_GET['location'])) {
            // Obtener la ubicación proporcionada por el usuario
            $location = $_GET['location'];

            // Construir la URL de la API de OpenWeatherMap API:79b93cc4e8b99878c153a44f0cd86eef
            $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($location) . "&lang=es&units=metric&appid=79b93cc4e8b99878c153a44f0cd86eef";

            // Realizar la solicitud a la API y obtener la respuesta
            $response = @file_get_contents($apiUrl);

            // Verificar si la solicitud fue exitosa
            if ($response !== false) {
                // Decodificar la respuesta JSON
                $weatherData = json_decode($response, true);

                // Verificar si los datos del clima están presentes, elegimos temperatura, descripción del clima, humedad y viento 
                if (isset($weatherData['main']['temp']) && isset($weatherData['weather'][0]['description']) && isset($weatherData['main']['humidity']) && isset($weatherData['wind']['speed'])) {
                    $temperatura = $weatherData['main']['temp'];
                    $descripcion = $weatherData['weather'][0]['description'];
                    $humedad = $weatherData['main']['humidity'];
                    $velocidadViento = $weatherData['wind']['speed'];

                    // Obtener la calidad del aire
                    $calidadAire = null;
                    $mensajeCalidadAire = 'No se pudo determinar la calidad del aire.';
                    if (isset($weatherData['coord']['lat']) && isset($weatherData['coord']['lon'])) {
                        // Construir la URL de la API de OpenWeatherMap para la polución del aire
                        $airPollutionApiUrl = "http://api.openweathermap.org/data/2.5/air_pollution?lat=" . $weatherData['coord']['lat'] . "&lon=" . $weatherData['coord']['lon'] . "&appid=79b93cc4e8b99878c153a44f0cd86eef";

                        // Realizar la solicitud a la API de polución del aire y obtener la respuesta
                        $airPollutionResponse = @file_get_contents($airPollutionApiUrl);

                        // Decodificar la respuesta JSON
                        $airPollutionData = json_decode($airPollutionResponse, true);

                        // Obtener la calidad del aire si la respuesta es válida
                        if ($airPollutionData !== false && isset($airPollutionData['list'][0]['main']['aqi'])) {
                            $calidadAire = $airPollutionData['list'][0]['main']['aqi'];
                            switch ($calidadAire) {
                                case 1:
                                    $mensajeCalidadAire = 'Buena calidad del aire.';
                                    break;
                                case 2:
                                    $mensajeCalidadAire = 'Calidad del aire regular.';
                                    break;
                                case 3:
                                    $mensajeCalidadAire = 'Calidad del aire moderada.';
                                    break;
                                case 4:
                                    $mensajeCalidadAire = 'Calidad del aire deficiente.';
                                    break;
                                case 5:
                                    $mensajeCalidadAire = 'Calidad del aire muy deficiente.';
                                    break;
                            }
                        }
                    }
                    // Definir recomendaciones de temperatura
                    $recomendaciones = '';
                    if ($temperatura < 10) {
                        $recomendaciones .= 'Hace frío. Recomiendo abrigarse bien.';
                    } elseif ($temperatura >= 10 && $temperatura < 15) {
                        $recomendaciones .= 'El clima es fresco, considera llevar una chaqueta.';
                    } elseif ($temperatura >= 15 && $temperatura < 20) {
                        $recomendaciones .= 'La temperatura es fresca. Un suéter ligero puede ser suficiente.';
                    } elseif ($temperatura >= 20 && $temperatura < 25) {
                        $recomendaciones .= 'El clima es agradable. Disfruta del día al aire libre.';
                    } elseif ($temperatura >= 25) {
                        $recomendaciones .= 'Hace calor. Mantente hidratado y busca lugares frescos.';
                    }
                    // Definir recomendaciones basadas en la descripción del clima
                    if (strpos(strtolower($descripcion), 'cielo claro') !== false) {
                        $recomendaciones .= ' No olvides aplicar protector solar.';
                    } 
                    if (strpos(strtolower($descripcion), 'lluvia') !== false) {
                        $recomendaciones .= ' Se pronostica lluvia, asegúrate de llevar un paraguas.';
                    }                     
                    // Imprimir la información del tiempo por pantalla
                    echo "<p><span style='font-size: 20px;'>Informe del tiempo:</span></p>";        
                    echo "<p><strong><span style='font-size: 16px;'>Temperatura:</strong> $temperatura °C</span></p>";
                    echo "<p><strong><span style='font-size: 16px;'>Descripción:</strong> $descripcion</span></p>";
                    echo "<p><strong><span style='font-size: 16px;'>Humedad:</strong> $humedad%</span></p>";
                    echo "<p><strong><span style='font-size: 16px;'>Velocidad del viento:</strong> $velocidadViento m/s</span></p>";
                    echo "<p class='recomendaciones'><strong><span style='font-size: 16px; color: #21A5B0'>Recomendaciones:</strong></span> $recomendaciones</p>";
                    echo "<p class='calidad-aire'><strong><span style='font-size: 16px; color: #21A5B0'>Calidad del aire:</strong></span> $mensajeCalidadAire</p>";                    
                } else {
                    // Mostrar un mensaje de error si no se pudo obtener la información del tiempo
                    echo "No se pudo obtener la información del tiempo para $location.";
                }
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