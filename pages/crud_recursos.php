<?php
require_once '../processes/procesar_crud_recursos.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>CRUD Recursos</title>
	<link rel="stylesheet" href="../styles/estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="body-">
	<header>
		<span>Pokéfull Stack | <?php echo $_SESSION['username'];?></span>
        <a href="./crear_sala.php" class="btn-cerrar">Crear Sala</a>

        <h2>Gestión de Recursos</h2>

        <a href="./crear_mesa.php" class="btn-cerrar">Crear Mesa</a>
		<a href="selecciona_sala.php" class="btn-cerrar">Volver</a>
	</header>
	<main class="contenedor-principal">
        <h2>Salas</h2>
        <table class="crud-table">
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
                        <div class="crud-actions">
                            <form action="./editar_sala.php" method="post">
                                <input type="hidden" name="idSala" value="<?= $s['idSala'] ?>">
                                <button type="submit" name="editar_sala" class="btn btn-link btn-editar">Editar</button>
                            </form>
                            <form class="eliminar-sala" method="post">
                                <input type="hidden" name="idSala" value="<?= $s['idSala'] ?>">
                                <button type="submit" name="eliminar_sala" class="btn btn-link btn-eliminar">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <h2 >Mesas</h2>
        <table class="crud-table">
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
                        <div class="crud-actions">
                            <form action="./editar_mesa.php" method="post">
                                <input type="hidden" name="idMesa" value="<?= $m['idMesa'] ?>">
                                <button type="submit" name="editar_mesa" class="btn btn-link btn-editar">Editar</button>
                            </form>
                            <form class="eliminar-mesa" method="post">
                                <input type="hidden" name="idMesa" value="<?= $m['idMesa'] ?>">
                                <button type="submit" name="eliminar_mesa" class="btn btn-link btn-eliminar">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
	</main>
<script src="./../js/script.js"></script>
</body>
</html>
