<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../database/conexion.php';


// Obtener el id de la sala a editar
if (!isset($_GET['idSala'])) {
    header('Location: crud_recursos.php');
    exit;
}

$idSala = intval($_GET['idSala']);
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_sala'])) {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $stmt = $conn->prepare('UPDATE sala SET nombre=?, tipo=? WHERE idSala=?');
    $stmt->execute([$nombre, $tipo, $idSala]);
    header('Location: crud_recursos.php');
    exit;
}
?>