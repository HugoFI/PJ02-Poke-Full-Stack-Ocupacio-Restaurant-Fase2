<?php

require_once '../database/conexion.php';


// Validar idUsuario
if (!isset($_POST['idUsuario']) || !is_numeric($_POST['idUsuario']) || $_POST['idUsuario'] <= 0) {
    echo '<p>ID de usuario no válido.<br><a href="../pages/crud_usuarios.php">Volver</a></p>';
    exit;
}
$idUsuario = intval($_POST['idUsuario']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $nombreUsu = $_POST['nombreUsu'] ?? '';
    $dni = $_POST['dni'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';
    $fechaContratacion = $_POST['fechaContratacion'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $estado = $_POST['estado'] ?? '';

    try {
        $stmt = $conn->prepare('UPDATE usuarios SET nombre=?, apellidos=?, nombreUsu=?, dni=?, telefono=?, email=?, fechaContratacion=?, rol=?, estado=? WHERE idUsuario=?');
        $stmt->execute([$nombre, $apellidos, $nombreUsu, $dni, $telefono, $email, $fechaContratacion, $rol, $estado, $idUsuario]);
        if ($stmt->rowCount() > 0) {
            header('Location: ../pages/crud_usuarios.php?edit=ok');
            exit;
        } else {
            echo '<p style="color:orange;text-align:center;">No se realizaron cambios o usuario no encontrado.<br><a href="../pages/crud_usuarios.php">Volver</a></p>';
            exit;
        }
    } catch (PDOException $e) {
        echo '<p>Error al actualizar: ' . htmlspecialchars($e->getMessage()) . '<br><a href="../pages/crud_usuarios.php">Volver</a></p>';
        exit;
    }
} else {
    echo '<p>Acceso no permitido.<br><a href="../pages/crud_usuarios.php">Volver</a></p>';
    exit;
}
?>