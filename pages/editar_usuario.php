<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../database/conexion.php';

// Obtener el id del usuario a editar
if (!isset($_GET['idUsuario'])) {
    header('Location: crud_usuarios.php');
    exit;
}
$idUsuario = intval($_GET['idUsuario']);
// Obtener datos actuales del usuario
$stmt = $conn->prepare('SELECT nombre, apellidos, nombreUsu, dni, telefono, email, fechaContratacion, rol, estado FROM usuarios WHERE idUsuario = ?');
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuario) {
    echo '<p>Usuario no encontrado.</p>';
    exit;
}
// Si se envía el formulario, actualizar usuario
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../styles/estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
		<span>Pokéfull Stack | <?php echo $_SESSION['username'];?></span>
		<h2>Gestión de Usuarios</h2>
		<a href="./crud_usuarios.php" class="btn-cerrar">Volver</a>
	</header>
    <h1>Editar Usuario</h1>
    <form action="./../processes/procesar_editar_usuario.php" id="editar-elemento" method="post">
        <input type="hidden" name="idUsuario" value="<?= $idUsuario ?>">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>"><br>
        <label>Apellidos:</label>
        <input type="text" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos']) ?>"><br>
        <label>Usuario:</label>
        <input type="text" name="nombreUsu" value="<?= htmlspecialchars($usuario['nombreUsu']) ?>"><br>
        <label>DNI:</label>
        <input type="text" name="dni" value="<?= htmlspecialchars($usuario['dni']) ?>"><br>
        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>"><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>"><br>
        <label>Fecha de Contratación:</label>
        <input type="date" name="fechaContratacion" value="<?= htmlspecialchars($usuario['fechaContratacion']) ?>"><br>
        <label>Rol:</label>
        <select name="rol">
            <option value="admin" <?= $usuario['rol']==='admin'?'selected':'' ?>>Admin</option>
            <option value="gerente" <?= $usuario['rol']==='gerente'?'selected':'' ?>>Gerente</option>
            <option value="mantenimiento" <?= $usuario['rol']==='mantenimiento'?'selected':'' ?>>Mantenimiento</option>
            <option value="camarero" <?= $usuario['rol']==='camarero'?'selected':'' ?>>Camarero</option>
        </select><br>
        <select name="estado">
            <option value="activo" <?= $usuario['estado']==='activo'?'selected':'' ?>>Activo</option>
            <option value="baja" <?= $usuario['estado']==='baja'?'selected':'' ?>>Baja</option>
        </select><br>
        <button type="submit" name="actualizar_usuario">Guardar cambios</button>
        <a href="crud_usuarios.php">Cancelar</a>
    </form>
<script src="./../js/script.js"></script>
</body>
</html>
