<?php
require_once '../processes/procesar_crud_recursos.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>CRUD Recursos</title>
	<link rel="stylesheet" href="../styles/estilos.css">
</head>
<body class="body-">
	<header>
		<span>Pokéfull Stack | Administrador</span>
		<h1>Gestión de Recursos</h1>
		<a href="selecciona_sala.php" class="btn-cerrar">Volver</a>
	</header>
	<main class="contenedor-principal">
		<div class="info-sala">
			<h2>Salas</h2>
			<table >
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Tipo</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($salas as $s): ?>
					<tr>
						<td><?= $s['idSala'] ?></td>
						<td><?= htmlspecialchars($s['nombre']) ?></td>
						<td><?= htmlspecialchars($s['tipo']) ?></td>
						<td>
							<form method="post" style="display:inline;">
								<input type="hidden" name="idSala" value="<?= $s['idSala'] ?>">
								<button type="submit" name="editar_sala" class="btn btn-link">Editar</button>
							</form>
							<form method="post" style="display:inline;">
								<input type="hidden" name="idSala" value="<?= $s['idSala'] ?>">
								<button type="submit" name="eliminar_sala" class="btn btn-link" style="background:#e74c3c; color:#fff;">Eliminar</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<h2 >Mesas</h2>
			<table >
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Sala</th>
						<th>Nº Sillas</th>
						<th>Estado</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($mesas as $m): ?>
					<tr>
						<td><?= $m['idMesa'] ?></td>
						<td><?= htmlspecialchars($m['nombre']) ?></td>
						<td><?= htmlspecialchars($m['nombreSala']) ?></td>
						<td><?= intval($m['numSillas']) ?></td>
						<td><?= htmlspecialchars($m['estado']) ?></td>
						<td>
							<form method="post" style="display:inline;">
								<input type="hidden" name="idMesa" value="<?= $m['idMesa'] ?>">
								<button type="submit" name="editar_mesa" class="btn btn-link">Editar</button>
							</form>
							<form method="post" style="display:inline;">
								<input type="hidden" name="idMesa" value="<?= $m['idMesa'] ?>">
								<button type="submit" name="eliminar_mesa" class="btn btn-link" style="background:#e74c3c; color:#fff;">Eliminar</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</main>
</body>
</html>
