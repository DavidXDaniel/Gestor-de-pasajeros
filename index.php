<!DOCTYPE html>
<html>
<head>
    <title>Registro de Pasajeros</title>

</head>
<body>
    <h2>Registro de Pasajeros</h2>

    <!-- Formulario de la captura de datos -->
    <form method="POST" action="">
        <label for="nombre">Nombre y Apellido:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="dui">Número de DUI:</label>
        <input type="text" id="dui" name="dui" required><br><br>

        <input type="submit" name="submit" value="Registrar">
    </form>

    <?php
    // Hize un inicio de sesión para guardar los datos de manera temporal
    session_start();

    // Aca esta la funcion para la ubicacion
    function obtenerUbicacion() {
        return "Ubicación predeterminada"; // Esto lo coloque tal cual porque aun no se programa para mostrar la ubicacion exacta de los pasajeros :)
    }

    // Si el formulario de registro se ha enviado
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
                'fecha_hora' => date('Y-m-d H:i:s'), // Fecha y hora 
                'ubicacion' => obtenerUbicacion(),
                'dui' => $nuevo_dui
            ];

            // Almacenar los datos en la sesión temporal
            if (!isset($_SESSION['pasajeros'])) {
                $_SESSION['pasajeros'] = [];
            }
            $_SESSION['pasajeros'][] = $pasajero;
        } else {
            echo '<p class="error">Error: El pasajero con el número de DUI ' . htmlspecialchars($nuevo_dui) . ' ya está registrado.</p>';
        }
    }
    ?>

    <h2>Lista de Pasajeros</h2>

    <!-- Filtro para Dui, nombre y fehca -->
    <form method="GET" action="">
        <label for="filtro_nombre">Filtrar por Nombre y Apellido:</label>
        <input type="text" id="filtro_nombre" name="filtro_nombre"><br><br>

        <label for="filtro_dui">Filtrar por DUI:</label>
        <input type="text" id="filtro_dui" name="filtro_dui"><br><br>

        <label for="filtro_fecha">Filtrar por Fecha:</label>
        <input type="date" id="filtro_fecha" name="filtro_fecha"><br><br>

        <input type="submit" value="Filtrar">
    </form>

    <?php
