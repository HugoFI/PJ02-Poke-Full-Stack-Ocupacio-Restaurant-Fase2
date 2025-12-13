<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../processes/procesar_reserva.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./../styles/estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
	<header>
		<span>Pokéfull Stack | <?php echo $_SESSION['username'];?></span>
		<h2>Reservas</h2>
		<a href="selecciona_sala.php" class="btn-cerrar">Volver</a>
	</header>
    <form id="formReserva" class="filtros-bar" method="post" action="">
        <div class="filtro-grupo">
            <label class="filtro-label" for="nombreCliente">Nombre cliente:</label>
            <input type="text" id="nombreCliente" name="nombreCliente" required maxlength="60" autocomplete="off" value="<?php echo isset($_POST['nombreCliente']) ? htmlspecialchars($_POST['nombreCliente']) : ''; ?>">
        </div>
        <div class="filtro-grupo">
            <label class="filtro-label" for="telefonoCliente">Teléfono:</label>
            <input type="tel" id="telefonoCliente" name="telefonoCliente" required maxlength="10" pattern="[0-9]{9,10}" autocomplete="off" value="<?php echo isset($_POST['telefonoCliente']) ? htmlspecialchars($_POST['telefonoCliente']) : ''; ?>">
        </div>
        <div class="filtro-grupo">
            <label class="filtro-label" for="fechaReserva">Fecha:</label>
            <input type="date" id="fechaReserva" name="fechaReserva" required value="<?php echo isset($_POST['fechaReserva']) ? htmlspecialchars($_POST['fechaReserva']) : date('Y-m-d'); ?>" onchange="this.form.submit()">
        </div>
        <div class="filtro-grupo">
            <label class="filtro-label" for="horaReserva">Hora:</label>
            <input type="time" id="horaReserva" name="horaReserva" required value="<?php echo isset($_POST['horaReserva']) ? htmlspecialchars($_POST['horaReserva']) : date('H:i'); ?>" onchange="this.form.submit()">
        </div>
        <div class="filtro-grupo">
            <label class="filtro-label" for="numPersonas">Nº personas:</label>
            <input type="number" id="numPersonas" name="numPersonas" min="1" max="20" required value="<?php echo isset($_POST['numPersonas']) ? (int)$_POST['numPersonas'] : 1; ?>" style="width:60px;">
        </div>
        <div class="filtro-grupo">
            <button type="submit" class="btn">Reservar</button>
        </div>
    </form>

    <div class="contenedor-principal">
        <div style="width:100%;">
            <?php
            // Obtener fecha y hora seleccionadas (por defecto ahora)
            $fecha = isset($_POST['fechaReserva']) ? $_POST['fechaReserva'] : date('Y-m-d');
            $hora = isset($_POST['horaReserva']) ? $_POST['horaReserva'] : date('H:i');
            $fechaHoraInicio = $fecha . ' ' . $hora . ':00';
            $fechaHoraFin = date('Y-m-d H:i:s', strtotime($fechaHoraInicio . ' +1 hour'));

            // Conexión BD
            require_once '../database/conexion.php';

            // Obtener salas
            $salas = $conn->query('SELECT * FROM sala ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);

            foreach ($salas as $sala) {
                echo '<h2 style="margin-top:30px; color:var(--amarillo);">' . htmlspecialchars(ucfirst($sala['nombre'])) . '</h2>';
                echo '<div class="mesas-container">';
                // Obtener mesas de la sala
                $mesas = $conn->prepare('SELECT * FROM mesa WHERE idSala = ? ORDER BY nombre');
                $mesas->execute([$sala['idSala']]);
                $mesas = $mesas->fetchAll(PDO::FETCH_ASSOC);
                foreach ($mesas as $mesa) {
                    $reserva = $conn->prepare('SELECT COUNT(*) FROM reservas WHERE idMesa = ? AND ((horaInicio < ? AND horaFin > ?) OR (horaInicio >= ? AND horaInicio < ?))');
                    $reserva->execute([
                        $mesa['idMesa'],
                        $fechaHoraFin,
                        $fechaHoraInicio,
                        $fechaHoraInicio,
                        $fechaHoraFin
                    ]);
                    $reservada = $reserva->fetchColumn() > 0;
                    $estado = $reservada ? 'reservada' : 'libre';
                    $clase = 'mesa ' . $estado;
                    echo '<div class="' . $clase . '">';
                    if (!$reservada) {
                        $checked = (isset($_POST['mesaSeleccionada']) && $_POST['mesaSeleccionada'] == $mesa['idMesa']) ? 'checked' : '';
                        echo '<input type="radio" name="mesaSeleccionada" value="' . $mesa['idMesa'] . '" required ' . $checked . '> ';
                    }
                    echo '<div class="titulo-mesa">' . htmlspecialchars($mesa['nombre']) . '</div>';
                    echo '<div class="detalle">' . $mesa['numSillas'] . ' sillas</div>';
                    echo '<div class="estado-mesa">' . ucfirst($estado) . '</div>';
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>