<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../database/conexion.php';


// Obtener el id de la sala a editar por POST
if (!isset($_POST['idSala']) || !is_numeric($_POST['idSala']) || $_POST['idSala'] <= 0) {
    echo '<p>ID de sala no válido.<br><a href="../pages/crud_recursos.php">Volver</a></p>';
    exit;
}
$idSala = intval($_POST['idSala']);
// Obtener datos actuales de la sala
$stmt = $conn->prepare('SELECT nombre, tipo FROM sala WHERE idSala = ?');
$stmt->execute([$idSala]);
$sala = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sala) {
    echo '<p>Sala no encontrada.</p>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar'])) {
    header('Location: crud_recursos.php');
    exit;
}

// Si se envía el formulario, actualizar sala
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    try {
        $stmt = $conn->prepare('UPDATE sala SET nombre=?, tipo=? WHERE idSala=?');
        $stmt->execute([$nombre, $tipo, $idSala]);
        if ($stmt->rowCount() > 0) {
            header('Location: ../pages/crud_recursos.php?edit=ok');
            exit;
        } else {
            echo '<p>No se realizaron cambios o sala no encontrada.<br><a href="../pages/crud_recursos.php">Volver</a></p>';
            exit;
        }
    } catch (PDOException $e) {
        echo '<p>Error al actualizar: ' . htmlspecialchars($e->getMessage()) . '<br><a href="../pages/crud_recursos.php">Volver</a></p>';
        exit;
    }
} else {
    echo '<p>Acceso no permitido.<br><a href="../pages/crud_recursos.php">Volver</a></p>';
    exit;
}
?>