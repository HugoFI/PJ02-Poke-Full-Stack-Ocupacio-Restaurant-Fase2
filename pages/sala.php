<?php
require_once("../database/conexion.php");

if (isset($_GET['idSala'])) {
    // Mostrar mesas de la sala seleccionada
    $idSala = intval($_GET['idSala']);
    $sqlSala = "SELECT nombre FROM sala WHERE idSala = ?";
    $stmtSala = $conn->prepare($sqlSala);
    $stmtSala->execute([$idSala]);
    $sala = $stmtSala->fetch(PDO::FETCH_ASSOC);

    if ($sala) {
        echo "<h1>Mesas de la sala: " . htmlspecialchars($sala['nombre']) . "</h1>";
        $sqlMesas = "SELECT * FROM mesa WHERE idSala = ?";
        $stmtMesas = $conn->prepare($sqlMesas);
        $stmtMesas->execute([$idSala]);
        $mesas = $stmtMesas->fetchAll(PDO::FETCH_ASSOC);

        if (count($mesas) > 0) {
            echo "<ul>";
            foreach ($mesas as $mesa) {
                echo "<li>";
                echo "Mesa: " . htmlspecialchars($mesa['nombre']) . " | Sillas: " . $mesa['numSillas'] . " | Estado: " . $mesa['estado'];
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No hay mesas en esta sala.</p>";
        }
        echo '<p><a href="sala.php">Volver a salas</a></p>';
    } else {
        echo "<p>Sala no encontrada.</p>";
        echo '<p><a href="sala.php">Volver a salas</a></p>';
    }
} else {
    // Mostrar lista de salas
    $sqlSalas = "SELECT * FROM sala";
    $stmtSalas = $conn->prepare($sqlSalas);
    $stmtSalas->execute();
    $salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);
    echo "<h1>Selecciona una sala</h1>";
    echo "<ul>";
    foreach ($salas as $sala) {
        echo '<li><a href="sala.php?idSala=' . $sala['idSala'] . '">' . htmlspecialchars($sala['nombre']) . '</a></li>';
    }
    echo "</ul>";
}
?>