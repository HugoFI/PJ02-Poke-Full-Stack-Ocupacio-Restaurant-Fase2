<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}

require_once '../database/conexion.php';

// Obtener todas las salas
$stmtSalas = $conn->prepare('SELECT * FROM sala');
$stmtSalas->execute();
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

// Obtener todas las mesas
$stmtMesas = $conn->prepare('SELECT mesa.*, sala.nombre AS nombreSala FROM mesa JOIN sala ON mesa.idSala = sala.idSala');
$stmtMesas->execute();
$mesas = $stmtMesas->fetchAll(PDO::FETCH_ASSOC);

// Eliminar sala
if (isset($_POST['eliminar_sala']) && isset($_POST['idSala'])) {
    $id = intval($_POST['idSala']);
    // Eliminar primero las mesas asociadas a la sala
    $delMesas = $conn->prepare('DELETE FROM mesas WHERE idSala = ?');
    $delMesas->execute([$id]);
    // Ahora eliminar la sala
    $delSala = $conn->prepare('DELETE FROM salas WHERE idSala = ?');
    $delSala->execute([$id]);
    header('Location: crud_recursos.php');
    exit;
}
// Eliminar mesa
if (isset($_POST['eliminar_mesa']) && isset($_POST['idMesa'])) {
    $id = intval($_POST['idMesa']);
    $del = $conn->prepare('DELETE FROM mesa WHERE idMesa = ?');
    $del->execute([$id]);
    header('Location: crud_recursos.php');
    exit;
}
// // Editar sala (redirige a editar_sala.php)
// if (isset($_POST['editar_sala']) && isset($_POST['idSala'])) {
//     $id = intval($_POST['idSala']);
//     header('Location: editar_sala.php?idSala=' . $id);
//     exit;
// }
// // Editar mesa (redirige a editar_mesa.php)
// if (isset($_POST['editar_mesa']) && isset($_POST['idMesa'])) {
//     $id = intval($_POST['idMesa']);
//     header('Location: editar_mesa.php?idMesa=' . $id);
//     exit;
// }
?>
