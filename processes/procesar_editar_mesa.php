<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ./../index.php');
    exit;
}
require_once './../database/conexion.php';

// Obtener el id de la mesa a editar
if (!isset($_GET['idMesa'])) {
    header('Location: ./../pages/crud_recursos.php');
    exit;
}

$idMesa = intval($_GET['idMesa']);
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_mesa'])) {
    $nombre = $_POST['nombre'];
    $idSala = intval($_POST['idSala']);
    $numSillas = intval($_POST['numSillas']);
    $estado = $_POST['estado'];
    $stmt = $conn->prepare('UPDATE mesa SET nombre=?, idSala=?, numSillas=?, estado=? WHERE idMesa=?');
    $stmt->execute([$nombre, $idSala, $numSillas, $estado, $idMesa]);
    header('Location: crud_recursos.php');
    exit;
}

// Obtener todas las salas para el select
$stmtSalas = $conn->prepare('SELECT idSala, nombre FROM sala');
$stmtSalas->execute();
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);
?>