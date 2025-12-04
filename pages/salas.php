<?php
require_once("../database/conexion.php");

// Consulta todas las salas
$sqlSalas = "SELECT * FROM sala";
$stmtSalas = $conn->prepare($sqlSalas);
$stmtSalas->execute();
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>Salas y Mesas</h1>";

foreach ($salas as $sala) {
    echo "<h2>" . htmlspecialchars($sala['nombre']) . "</h2>";

    // Consulta las mesas de la sala actual
    $sqlMesas = "SELECT * FROM mesa WHERE idSala = ?";
    $stmtMesas = $conn->prepare($sqlMesas);
    $stmtMesas->execute([$sala['idSala']]);
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
}
?>