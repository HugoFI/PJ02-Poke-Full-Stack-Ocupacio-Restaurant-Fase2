<?php
require_once '../processes/procesar_crud_usuarios.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>CRUD Usuarios</title>
	<link rel="stylesheet" href="../styles/estilos.css">
</head>
<body class="body-">
	<header>
		<span>Pokéfull Stack | Admin</span>
		<h1>Gestión de Usuarios</h1>
		<a href="selecciona_sala.php" class="btn-cerrar">Volver</a>
	</header>
	<main class="contenedor-principal">
		<div class="">
			<h2>Usuarios registrados</h2>
			<table >
				<thead>
					<tr>
						<th>ID</th>
						<th>Usuario</th>
						<th>Nombre</th>
						<th>Apellidos</th>
						<th>DNI</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Rol</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($usuarios as $u): ?>
					<tr>
						<td><?= $u['idUsuario'] ?></td>
						<td><?= htmlspecialchars($u['nombreUsu']) ?></td>
						<td><?= htmlspecialchars($u['nombre']) ?></td>
						<td><?= htmlspecialchars($u['apellidos']) ?></td>
						<td><?= htmlspecialchars($u['dni']) ?></td>
						<td><?= htmlspecialchars($u['telefono']) ?></td>
						<td><?= htmlspecialchars($u['email']) ?></td>
						<td><?= htmlspecialchars($u['rol']) ?></td>
						<td>
							<form method="post" style="display:inline;">
								<input type="hidden" name="idUsuario" value="<?= $u['idUsuario'] ?>">
								<button type="submit" name="editar" class="btn btn-link">Editar</button>
							</form>
							<form method="post" style="display:inline;">
								<input type="hidden" name="idUsuario" value="<?= $u['idUsuario'] ?>">
								<button type="submit" name="eliminar" class="btn btn-link" style="background:#e74c3c; color:#fff;">Eliminar</button>
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
