<!DOCTYPE html>
<html>
<head>
    <title>Registro de Pasajeros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="datetime-local"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        input[type="submit"], button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover, button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Registro de Pasajeros</h2>


    <form method="POST" action="">
        <label for="nombre">Nombre y Apellido:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="dui">Número de DUI:</label>
        <input type="text" id="dui" name="dui" required><br><br>

        <input type="submit" name="submit" value="Registrar">
    </form>

    <?php
   
    session_start();

   
    function obtenerUbicacion() {
        return "Ubicación predeterminada"; // Cambia esto por la lógica para obtener la ubicación real
    }

   
    if (isset($_POST['submit'])) {
        $nuevo_dui = $_POST['dui'];
        $pasajero_existente = false;

        // Verifica si el DUI ya existe
        if (isset($_SESSION['pasajeros'])) {
            foreach ($_SESSION['pasajeros'] as $pasajero) {
                if ($pasajero['dui'] === $nuevo_dui) {
                    $pasajero_existente = true;
                    break;
                }
            }
        }

        if (!$pasajero_existente) {
            $pasajero = [
                'nombre' => $_POST['nombre'],
                'fecha_hora' => date('Y-m-d H:i:s'), // Fecha y hora actual
                'ubicacion' => obtenerUbicacion(),
                'dui' => $nuevo_dui
            ];


            if (!isset($_SESSION['pasajeros'])) {
                $_SESSION['pasajeros'] = [];
            }
            $_SESSION['pasajeros'][] = $pasajero;
        } else {
            echo '<p class="error">Error: El pasajero con el número de DUI ' . htmlspecialchars($nuevo_dui) . ' ya está registrado.</p>';
        }
    }


    if (isset($_POST['cerrar_sesion'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    ?>

    <h2>Lista de Pasajeros</h2>

    <!-- Formulario para filtrar por nombre, DUI y fecha -->
    <form method="GET" action="">
        <label for="filtro_nombre">Filtrar por Nombre y Apellido:</label>
        <input type="text" id="filtro_nombre" name="filtro_nombre"><br><br>

        <label for="filtro_dui">Filtrar por DUI:</label>
        <input type="text" id="filtro_dui" name="filtro_dui"><br><br>

        <label for="filtro_fecha">Filtrar por Fecha:</label>
        <input type="date" id="filtro_fecha" name="filtro_fecha"><br><br>

        <input type="submit" value="Filtrar">
    </form>

    <form method="POST" action="">
        <button type="submit" name="cerrar_sesion">Cerrar Sesión (Eliminar datos)</button>
    </form>

    <?php

    $filtro_nombre = isset($_GET['filtro_nombre']) ? $_GET['filtro_nombre'] : '';
    $filtro_dui = isset($_GET['filtro_dui']) ? $_GET['filtro_dui'] : '';
    $filtro_fecha = isset($_GET['filtro_fecha']) ? $_GET['filtro_fecha'] : '';

    if (isset($_SESSION['pasajeros'])) {
        $pasajeros_filtrados = array_filter($_SESSION['pasajeros'], function ($pasajero) use ($filtro_nombre, $filtro_dui, $filtro_fecha) {
            $nombre_match = stripos($pasajero['nombre'], $filtro_nombre) !== false;
            $dui_match = stripos($pasajero['dui'], $filtro_dui) !== false;
            $fecha_match = $filtro_fecha ? strpos($pasajero['fecha_hora'], $filtro_fecha) === 0 : true;
            return ($nombre_match || !$filtro_nombre) && ($dui_match || !$filtro_dui) && $fecha_match;
        });


        if (!empty($pasajeros_filtrados)) {
            echo '<table>';
            echo '<tr><th>Nombre y Apellido</th><th>Fecha y Hora</th><th>Ubicación</th><th>Número de DUI</th></tr>';
            foreach ($pasajeros_filtrados as $pasajero) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($pasajero['nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($pasajero['fecha_hora']) . '</td>';
                echo '<td>' . htmlspecialchars($pasajero['ubicacion']) . '</td>';
                echo '<td>' . htmlspecialchars($pasajero['dui']) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No se encontraron pasajeros con esos criterios.</p>';
        }
    } else {
        echo '<p>No hay pasajeros registrados.</p>';
    }
    ?>
</body>
</html>
