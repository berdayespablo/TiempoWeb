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
    require_once 'WeatherAPI.php'; // Incluir la clase WeatherAPI

    // Verificar si se ha enviado una solicitud y se ha recibido una respuesta
    if (isset($_GET['location'])) {
        $location = $_GET['location'];

        // Crear una instancia de la clase WeatherAPI
        $weatherAPI = new WeatherAPI();

        try {
            // Obtener datos meteorológicos
            $weatherData = $weatherAPI->getWeatherData($location);

            // Obtener datos de contaminación del aire si es posible
            if (isset($weatherData['coord']['lat']) && isset($weatherData['coord']['lon'])) {
                $airPollutionData = $weatherAPI->getAirPollutionData($weatherData['coord']['lat'], $weatherData['coord']['lon']);
            }

            // Procesar datos y generar recomendaciones
            $temperatura = $weatherData['main']['temp'];
            $descripcion = $weatherData['weather'][0]['description'];
            $humedad = $weatherData['main']['humidity'];
            $velocidadViento = $weatherData['wind']['speed'];

            // Obtener la calidad del aire
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
        } catch (Exception $e) {
            // Manejar la excepción
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