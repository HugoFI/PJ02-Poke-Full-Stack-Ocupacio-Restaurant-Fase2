<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../processes/procesar_crud_usuarios.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>CRUD Usuarios</title>
	<link rel="stylesheet" href="../styles/estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="body-">
	<header>
		<span>Pokéfull Stack | <?php echo $_SESSION['username'];?></span>
		<h2>Gestión de Usuarios</h2>
		<a href="selecciona_sala.php" class="btn-cerrar">Volver</a>
	</header>
	<main class="contenedor-principal">
		<div class="">
			<h2>Usuarios registrados</h2>
			<table class="crud-table">
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
							<div class="crud-actions">
								<form method="post">
									<input type="hidden" name="idUsuario" value="<?= $u['idUsuario'] ?>">
									<button type="submit" name="editar" class="btn btn-link btn-editar">Editar</button>
								</form>
								<form class="eliminar-usuario" method="post">
									<input type="hidden" name="idUsuario" value="<?= $u['idUsuario'] ?>">
									<button type="submit" name="eliminar" class="btn btn-link btn-eliminar">Eliminar</button>
								</form>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</main>
<script src="./../js/script.js"></script>
</body>
</html>
