<?php
require_once './../database/conexion.php';

// Procesar reserva si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreCliente'], $_POST['telefonoCliente'], $_POST['fechaReserva'], $_POST['horaReserva'], $_POST['numPersonas'], $_POST['mesaSeleccionada'])) {
	$nombreCliente = trim($_POST['nombreCliente']);
	$telefonoCliente = trim($_POST['telefonoCliente']);
	$fecha = $_POST['fechaReserva'];
	$hora = $_POST['horaReserva'];
	$numPersonas = (int)$_POST['numPersonas'];
	$idMesa = (int)$_POST['mesaSeleccionada'];
	$idUsuario = $_SESSION['idUsuario'] ?? 1; // Ajustar según sesión

	$fechaHoraInicio = $fecha . ' ' . $hora . ':00';
	$fechaHoraFin = date('Y-m-d H:i:s', strtotime($fechaHoraInicio . ' +1 hour'));

	// Comprobar si la mesa está libre en ese rango
	$stmt = $conn->prepare('SELECT COUNT(*) FROM reservas WHERE idMesa = ? AND ((horaInicio < ? AND horaFin > ?) OR (horaInicio >= ? AND horaInicio < ?))');
	$stmt->execute([$idMesa, $fechaHoraFin, $fechaHoraInicio, $fechaHoraInicio, $fechaHoraFin]);
	$reservada = $stmt->fetchColumn() > 0;

	if (!$reservada) {
		// Obtener idSala de la mesa
		$salaStmt = $conn->prepare('SELECT idSala FROM mesa WHERE idMesa = ?');
		$salaStmt->execute([$idMesa]);
		$idSala = $salaStmt->fetchColumn();

		// Insertar reserva
		$insert = $conn->prepare('INSERT INTO reservas (idMesa, idSala, idUsuario, nombreCliente, telefonoCliente, fechaReserva, horaInicio, horaFin, numPersonas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
		$insert->execute([
			$idMesa, $idSala, $idUsuario, $nombreCliente, $telefonoCliente, $fechaHoraInicio, $fechaHoraInicio, $fechaHoraFin, $numPersonas
		]);
		$reservaExitosa = true;
	} else {
		$reservaExitosa = false;
		$errorReserva = 'La mesa seleccionada ya está reservada para ese horario.';
	}
}


?>
