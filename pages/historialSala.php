<?php
session_start();
require_once '../database/conexion.php';

if (!isset($_SESSION['idUsuario'])) {
    header('Location: ../login.php?error=SesionExpirada');
    exit;
}

$idSala = isset($_GET['idSala']) ? intval($_GET['idSala']) : 0;

try {
    $sql = "
        SELECT 
            h.idHistorico,
            m.nombre AS nombreMesa,
            c.nombre AS nombreCamarero,
            c.apellidos AS apellidosCamarero,
            h.horaOcupacion,
            h.horaDesocupacion
        FROM historico h
        INNER JOIN mesa m ON h.idMesa = m.idMesa
        INNER JOIN usuarios c ON h.idUsuario = c.idUsuario
        WHERE m.idSala = :idSala
        ORDER BY h.horaOcupacion DESC, h.idHistorico DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':idSala' => $idSala]);
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "Error al cargar historial: " . $e->getMessage();
}

$nombreSala = 'Desconocida';
$salaNombres = [
    1 => "Kanto", 2 => "Johto", 3 => "Hoenn",
    4 => "Sinnoh", 5 => "Unova", 6 => "Kalos",
    7 => "Alola", 8 => "Galar", 9 => "Paldea"
];
if (isset($salaNombres[$idSala])) {
    $nombreSala = $salaNombres[$idSala];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Historial de Sala</title>
    <link rel="stylesheet" href="./../styles/styles.css">
</head>
<body class="body-historial">

<h1>Historial de la sala: <?= htmlspecialchars($nombreSala) ?></h1>

<?php if (isset($errorMsg)): ?>
    <p style="color:red; text-align:center; margin-top:30px;"><?= htmlspecialchars($errorMsg) ?></p>

<?php elseif (empty($historial)): ?>
    <p style="text-align:center; margin-top:30px;">No hay registros en el historial para esta sala.</p>

<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Mesa</th>
                <th>Camarero</th>
                <th>Hora Ocupación</th>
                <th>Hora Desocupación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historial as $h): ?>
                <tr>
                    <td><?= intval($h['idHistorico']) ?></td>
                    <td><?= htmlspecialchars($h['nombreMesa']) ?></td>
                    <td><?= htmlspecialchars($h['nombreCamarero'] . ' ' . $h['apellidosCamarero']) ?></td>
                    <td><?= htmlspecialchars($h['horaOcupacion']) ?></td>
                    <td>
                        <?= ($h['horaDesocupacion'] === '0000-00-00 00:00:00' || !$h['horaDesocupacion'])
                            ? '<em>En curso</em>'
                            : htmlspecialchars($h['horaDesocupacion']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="volver">
    <a href="sala.php?idSala=<?= $idSala ?>">← Volver a la sala</a>
</div>

</body>
</html>
