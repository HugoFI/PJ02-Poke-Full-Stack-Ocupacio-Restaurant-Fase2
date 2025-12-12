<?php
require_once '../database/conexion.php';

// Obtener el id del usuario a editar
$idUsuario = isset($_POST['idUsuario']) ? intval($_POST['idUsuario']) : 0;

// Si se envía el formulario, actualizar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $nombreUsu = $_POST['nombreUsu'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $fechaContratacion = $_POST['fechaContratacion'];
    $rol = $_POST['rol'];
    // No se actualiza la contraseña aquí

    $stmt = $conn->prepare('UPDATE usuarios SET nombre=?, apellidos=?, nombreUsu=?, dni=?, telefono=?, email=?, fechaContratacion=?, rol=? WHERE idUsuario=?');
    $stmt->execute([$nombre, $apellidos, $nombreUsu, $dni, $telefono, $email, $fechaContratacion, $rol, $idUsuario]);
    header('Location: crud_usuarios.php');
    exit;
}

// Obtener los datos actuales del usuario
$stmt = $conn->prepare('SELECT * FROM usuarios WHERE idUsuario = ?');
$stmt->execute([$idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuario) {
    echo '<p>Usuario no encontrado.</p>';
    exit;
}
?>