<?php
require_once '../database/conexion.php';

// Obtener todos los usuarios
$stmt = $conn->prepare('SELECT idUsuario, nombre, apellidos, nombreUsu, dni, telefono, email, fechaContratacion, rol FROM usuarios');
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Eliminar usuario
if (isset($_POST['eliminar']) && isset($_POST['idUsuario'])) {
    $id = intval($_POST['idUsuario']);
    $del = $conn->prepare('DELETE FROM usuarios WHERE idUsuario = ?');
    $del->execute([$id]);
    header('Location: crud_usuarios.php');
    exit;
}

// Editar usuario (solo redirige, el formulario de edición se haría en otra página)
if (isset($_POST['editar']) && isset($_POST['idUsuario'])) {
    $id = intval($_POST['idUsuario']);
    header('Location: editar_usuario.php?idUsuario=' . $id);
    exit;
}

?>