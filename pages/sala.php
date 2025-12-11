<?php
session_start();
if (!isset($_SESSION['idUsuario'])) {
    header('Location: ./login.php?error=SesionExpirada');
    exit;
}
require_once '../processes/procesar_sala.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Sala</title>
    <link rel="stylesheet" href="./../styles/estilos.css">
</head>
<body class="body-sala">
    <header style="margin-bottom: 8px;">
        <span>Pokéfull Stack<?php if(isset($_SESSION['username'])) echo ' | ' . $_SESSION['username']; ?></span>
        <h2><?php echo htmlspecialchars($sala['nombre']); ?> <small class="sala-tipo">(<?php echo htmlspecialchars($sala['tipo']); ?>)</small></h2>
        <a href="selecciona_sala.php" class="btn-cerrar">Volver a selector de salas</a>
    </header>
    <div class="filtros-bar">
                <div class="filtro-grupo">
                    <span class="filtro-label">Estado:</span>
                    <div class="filtro-botones">
                        <a href="?idSala=<?= $idSala ?>&filtro_estado=todas&filtro_sillas=<?= $filtro_sillas ?>" class="filtro-btn <?= $filtro_estado === 'todas' ? 'filtro-activo' : '' ?>">Todas (<?= $total_mesas ?>)</a>
                        <a href="?idSala=<?= $idSala ?>&filtro_estado=ocupadas&filtro_sillas=<?= $filtro_sillas ?>" class="filtro-btn <?= $filtro_estado === 'ocupadas' ? 'filtro-activo' : '' ?>">Ocupadas (<?= $ocupadas ?>)</a>
                        <a href="?idSala=<?= $idSala ?>&filtro_estado=libres&filtro_sillas=<?= $filtro_sillas ?>" class="filtro-btn <?= $filtro_estado === 'libres' ? 'filtro-activo' : '' ?>">Libres (<?= $libres ?>)</a>
                    </div>
                </div>
                <div class="filtro-grupo">
                    <span class="filtro-label">Sillas:</span>
                    <div class="filtro-botones">
                        <a href="?idSala=<?= $idSala ?>&filtro_estado=<?= $filtro_estado ?>&filtro_sillas=todas" class="filtro-btn <?= $filtro_sillas === 'todas' ? 'filtro-activo' : '' ?>">Todas</a>
                        <a href="?idSala=<?= $idSala ?>&filtro_estado=<?= $filtro_estado ?>&filtro_sillas=1" class="filtro-btn <?= $filtro_sillas === '1' ? 'filtro-activo' : '' ?>">1 (<?= $sillas_1 ?>)</a>
                        <a href="?idSala=<?= $idSala ?>&filtro_estado=<?= $filtro_estado ?>&filtro_sillas=2" class="filtro-btn <?= $filtro_sillas === '2' ? 'filtro-activo' : '' ?>">2 (<?= $sillas_2 ?>)</a>
                        <a href="?idSala=<?= $idSala ?>&filtro_estado=<?= $filtro_estado ?>&filtro_sillas=3" class="filtro-btn <?= $filtro_sillas === '3' ? 'filtro-activo' : '' ?>">3 (<?= $sillas_3 ?>)</a>
                        <a href="?idSala=<?= $idSala ?>&filtro_estado=<?= $filtro_estado ?>&filtro_sillas=4" class="filtro-btn <?= $filtro_sillas === '4' ? 'filtro-activo' : '' ?>">4 (<?= $sillas_4 ?>)</a>
                    </div>
                </div>
                <div class="filtro-grupo">
                    <a href="?idSala=<?= $idSala ?>" class="filtro-btn filtro-limpiar">🗙 Limpiar</a>
                </div>
            </div>
    <main class="contenedor-principal">
        <div class="info-sala">
            <?php if (!empty($errorMsg)): ?>
                <p class="error-msg"><?= htmlspecialchars($errorMsg) ?></p>
            <?php endif; ?>

            

            <?php if (!$selectedMesa): ?>
                <p>Mesas totales: <?= $total_mesas ?></p>
                <p>Mesas disponibles: <?= $libres ?></p>
                <p><strong>Filtros activos:</strong></p>
                <ul>
                    <li>Estado: <?= $filtro_estado === 'todas' ? 'Todas' : $filtro_estado ?></li>
                    <li>Sillas: <?= $filtro_sillas === 'todas' ? 'Todas' : $filtro_sillas ?></li>
                </ul>
                <strong>Selecciona una mesa para ver detalles.</strong>
                <br><br><br>
                <a class="btn-toggle" href="../pages/historialSala.php?idSala=<?= $idSala ?>">Ver historial de sala</a>
            <?php else: ?>
                <p><strong>Nombre:</strong> <?= htmlspecialchars($selectedMesa['nombre']) ?></p>
                <p><strong>Estado:</strong> <?= $selectedMesa['estado'] ?></p>
                <p><strong>Sillas:</strong> <?= intval($selectedMesa['numSillas']) ?></p>
                <?php if ($selectedMesa && $selectedMesa['estado'] === 'ocupada' && $nombreUsuarioOcupante): ?>
                    <p><strong>Ocupada por:</strong> <?= htmlspecialchars($nombreUsuarioOcupante) ?></p>
                    <?php if (!$puedeLiberar): ?>
                        <p class="solo-ocupa">Solo <?= htmlspecialchars($nombreUsuarioOcupante) ?> puede liberar esta mesa</p>
                    <?php endif; ?>
                <?php endif; ?>
                <form id="form-cambiar-estado" method="post" action="?idSala=<?= $idSala ?>&filtro_estado=<?= $filtro_estado ?>&filtro_sillas=<?= $filtro_sillas ?>">
                    <input type="hidden" name="idMesa" value="<?= intval($selectedMesa['idMesa']) ?>">
                    <button type="submit" class="btn-toggle" <?= ($selectedMesa['estado'] === 'ocupada' && !$puedeLiberar) ? 'disabled style="background:#ccc; cursor:not-allowed;"' : '' ?>>
                        <?= ($selectedMesa['estado'] === 'libre') ? 'Marcar como ocupada' : 'Marcar como libre' ?>
                    </button>
                </form>
                <br>
                <a class="btn-toggle" href="../pages/historial.php?idMesa=<?= intval($selectedMesa['idMesa']) ?>&idSala=<?= intval($selectedMesa['idSala']) ?>">Ver historial de mesa</a>
                <br><br><br>
                <a class="btn-toggle" href="../pages/historialSala.php?idSala=<?= intval($selectedMesa['idSala']) ?>">Ver historial de sala</a>
            <?php endif; ?>
        </div>
        <div class="mesas-container">
            <?php if (empty($mesas)): ?>
                <p style="font-size:0.95em;">No hay mesas disponibles con los filtros seleccionados.</p>
            <?php else: ?>
                <?php foreach ($mesas as $m): ?>
                    <?php
                        $cls = ($m['estado'] === 'ocupada') ? 'ocupada' : 'libre';
                        $url = "?idSala=" . $idSala . "&select=" . intval($m['idMesa']) . "&filtro_estado=" . $filtro_estado . "&filtro_sillas=" . $filtro_sillas;
                    ?>
                    <a class="mesa <?= $cls ?>" href="<?= $url ?>">
                        <div class="titulo-mesa" ><?= htmlspecialchars($m['nombre']) ?></div>
                        <div class="detalle" ><?= intval($m['numSillas']) ?> sillas</div>
                        <div class="estado-mesa" ><?= ucfirst($m['estado']) ?></div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <div class="volver">
        <a href="selecciona_sala.php">← Volver a seleccionar sala</a>
    </div>
</body>
</html>