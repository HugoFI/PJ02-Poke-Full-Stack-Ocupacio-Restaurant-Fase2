<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ./../index.php');
    exit;
}
require_once './../database/conexion.php';

// Obtener el id de la mesa a editar por POST
if (!isset($_POST['idMesa']) || !is_numeric($_POST['idMesa']) || $_POST['idMesa'] <= 0) {
    echo '<p>ID de mesa no válido.<br><a href="../pages/crud_recursos.php">Volver</a></p>';
    exit;
}
$idMesa = intval($_POST['idMesa']);
// Obtener datos actuales de la mesa
$stmt = $conn->prepare('SELECT nombre, idSala, numSillas, estado FROM mesa WHERE idMesa = ?
');
$stmt->execute([$idMesa]);
$mesa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mesa) {
    echo '<p>Mesa no encontrada.</p>';
    exit;
}

// Si se envía el formulario, actualizar mesa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $idSala = isset($_POST['idSala']) ? intval($_POST['idSala']) : 0;
    $numSillas = isset($_POST['numSillas']) ? intval($_POST['numSillas']) : 0;
    $estado = $_POST['estado'] ?? '';
    try {
        $stmt = $conn->prepare('UPDATE mesa SET nombre=?, idSala=?, numSillas=?, estado=? WHERE idMesa=?');
        $stmt->execute([$nombre, $idSala, $numSillas, $estado, $idMesa]);
        if ($stmt->rowCount() > 0) {
            header('Location: ../pages/crud_recursos.php?edit=ok');
            exit;
        } else {
            echo '<p>No se realizaron cambios o mesa no encontrada.<br><a href="../pages/crud_recursos.php">Volver</a></p>';
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

// Obtener todas las salas para el select
$stmtSalas = $conn->prepare('SELECT idSala, nombre FROM sala');
$stmtSalas->execute();
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);
?>