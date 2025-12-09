<?php
session_start();
require_once '../../../database/conexion.php';

// --- 1. CAMBIAR ESTADO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idMesa'])) {
    $idMesa = intval($_POST['idMesa']);
    $idUsuario = $_SESSION['idUsuario'] ?? null;

    if (!$idUsuario) {
        header('Location: ../../login.php?error=SesionExpirada');
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT estado FROM mesa WHERE idMesa = :id");
        $stmt->execute([':id' => $idMesa]);
        $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($mesa) {
            if ($mesa['estado'] === 'libre') {
                $nuevoEstado = 'ocupada';
                $upd = $conn->prepare("UPDATE mesa SET estado = :estado WHERE idMesa = :id");
                $upd->execute([':estado' => $nuevoEstado, ':id' => $idMesa]);

                $insertHist = $conn->prepare("
                    INSERT INTO historico (idMesa, idSala, idUsuario, horaOcupacion, horaDesocupacion)
                    VALUES (:idMesa, :idSala, :idUsuario, NOW(), NULL)
                ");
                $insertHist->execute([
                    ':idMesa' => $idMesa,
                    ':idSala' => 1,
                    ':idUsuario' => $idUsuario
                ]);
            } else {
                $stmtHist = $conn->prepare("
                    SELECT idUsuario 
                    FROM historico 
                    WHERE idMesa = :idMesa 
                    AND horaDesocupacion IS NULL
                    ORDER BY idHistorico DESC 
                    LIMIT 1
                ");
                $stmtHist->execute([':idMesa' => $idMesa]);
                $historico = $stmtHist->fetch(PDO::FETCH_ASSOC);

                if (!$historico || $historico['idUsuario'] != $idUsuario) {
                    $_SESSION['error'] = "No puedes liberar esta mesa. Solo el camarero que la ocupó puede liberarla.";
                    header('Location: ./kanto.php?select=' . $idMesa);
                    exit;
                }

                $nuevoEstado = 'libre';
                $upd = $conn->prepare("UPDATE mesa SET estado = :estado WHERE idMesa = :id");
                $upd->execute([':estado' => $nuevoEstado, ':id' => $idMesa]);

                $updateHist = $conn->prepare("
                    UPDATE historico
                    SET horaDesocupacion = NOW()
                    WHERE idMesa = :idMesa
                    AND horaDesocupacion IS NULL
                    ORDER BY idHistorico DESC
                    LIMIT 1
                ");
                $updateHist->execute([':idMesa' => $idMesa]);
            }
        }
    } catch (PDOException $e) {
        $errorMsg = "Error al cambiar estado: " . $e->getMessage();
    }

    header('Location: ./kanto.php?select=' . $idMesa);
    exit;
}

// --- FILTROS ---
$filtro_estado = $_GET['filtro_estado'] ?? 'todas';
$filtro_sillas = $_GET['filtro_sillas'] ?? 'todas';

try {
    $sql = "SELECT * FROM mesa WHERE idSala = 1";
    if ($filtro_estado === 'ocupadas') $sql .= " AND estado = 'ocupada'";
    elseif ($filtro_estado === 'libres') $sql .= " AND estado = 'libre'";

    if (in_array($filtro_sillas, ['1','2','3','4'])) $sql .= " AND numSillas = " . intval($filtro_sillas);
    $sql .= " ORDER BY nombre";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mesas = [];
    $errorMsg = "Error al leer mesas: " . $e->getMessage();
}

// --- MESA SELECCIONADA ---
$selectedMesa = null;
$nombreUsuarioOcupante = null;
$puedeLiberar = false;
$idUsuarioOcupante = null;

if (isset($_GET['select'])) {
    $idSelect = intval($_GET['select']);
    foreach ($mesas as $m) {
        if ($m['idMesa'] == $idSelect) {
            $selectedMesa = $m;
            break;
        }
    }

    if ($selectedMesa && $selectedMesa['estado'] === 'ocupada') {
        $sql = "
            SELECT c.nombre, c.apellidos, c.idUsuario
            FROM historico h
            INNER JOIN usuarios c ON h.idUsuario = c.idUsuario
            WHERE h.idMesa = :idMesa
            AND h.horaDesocupacion IS NULL
            ORDER BY h.idHistorico DESC
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':idMesa' => $selectedMesa['idMesa']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $nombreUsuarioOcupante = $row['nombre'] . ' ' . $row['apellidos'];
            $idUsuarioOcupante = $row['idUsuario'];
            $puedeLiberar = ($idUsuarioOcupante == $_SESSION['idUsuario']);
        }
    }
}

if (isset($_SESSION['error'])) {
    $errorMsg = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Terraza Kanto</title>
<link rel="stylesheet" href="../../../styles/estilos.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="body-kanto">

<header>
    <span>Pokéfull Stack | <?= $_SESSION['username']; ?></span>
    <h1>Terraza Kanto</h1>
    <form id="cerrar-sesion" action="./../../../processes/logout.php" method="post">
        <button type="submit" class="btn-cerrar">Cerrar sesión</button>
    </form>
</header>

<div class="sala-buttons">
    <a href="./kanto.php" class="btn-link" disabled>Kanto</a>
    <a href="./johto.php" class="btn-link">Johto</a>
    <a href="./hoenn.php" class="btn-link">Hoenn</a>
</div>

<!-- BARRA DE FILTROS -->
<?php
$total_mesas = count($mesas);
$ocupadas = $libres = 0;
foreach ($mesas as $m) {
    if ($m['estado'] === 'ocupada') $ocupadas++;
    if ($m['estado'] === 'libre') $libres++;
}
$sillas_1 = $sillas_2 = $sillas_3 = $sillas_4 = 0;
foreach ($mesas as $m) {
    if ($m['numSillas'] == 1) $sillas_1++;
    if ($m['numSillas'] == 2) $sillas_2++;
    if ($m['numSillas'] == 3) $sillas_3++;
    if ($m['numSillas'] == 4) $sillas_4++;
}
?>
<div class="filtros-bar">
    <div class="filtro-grupo">
        <span class="filtro-label">Estado:</span>
        <div class="filtro-botones">
            <a href="?filtro_estado=todas&filtro_sillas=<?= $filtro_sillas ?>" class="filtro-btn <?= $filtro_estado==='todas'?'filtro-activo':'' ?>">Todas (<?= $total_mesas ?>)</a>
            <a href="?filtro_estado=ocupadas&filtro_sillas=<?= $filtro_sillas ?>" class="filtro-btn <?= $filtro_estado==='ocupadas'?'filtro-activo':'' ?>">Ocupadas (<?= $ocupadas ?>)</a>
            <a href="?filtro_estado=libres&filtro_sillas=<?= $filtro_sillas ?>" class="filtro-btn <?= $filtro_estado==='libres'?'filtro-activo':'' ?>">Libres (<?= $libres ?>)</a>
        </div>
    </div>

    <div class="filtro-grupo">
        <span class="filtro-label">Sillas:</span>
        <div class="filtro-botones">
            <a href="?filtro_estado=<?= $filtro_estado ?>&filtro_sillas=todas" class="filtro-btn <?= $filtro_sillas==='todas'?'filtro-activo':'' ?>">Todas</a>
            <a href="?filtro_estado=<?= $filtro_estado ?>&filtro_sillas=1" class="filtro-btn <?= $filtro_sillas==='1'?'filtro-activo':'' ?>">1 (<?= $sillas_1 ?>)</a>
            <a href="?filtro_estado=<?= $filtro_estado ?>&filtro_sillas=2" class="filtro-btn <?= $filtro_sillas==='2'?'filtro-activo':'' ?>">2 (<?= $sillas_2 ?>)</a>
            <a href="?filtro_estado=<?= $filtro_estado ?>&filtro_sillas=3" class="filtro-btn <?= $filtro_sillas==='3'?'filtro-activo':'' ?>">3 (<?= $sillas_3 ?>)</a>
            <a href="?filtro_estado=<?= $filtro_estado ?>&filtro_sillas=4" class="filtro-btn <?= $filtro_sillas==='4'?'filtro-activo':'' ?>">4 (<?= $sillas_4 ?>)</a>
        </div>
    </div>

    <div class="filtro-grupo">
        <a href="?" class="filtro-btn filtro-limpiar">🗙 Limpiar</a>
    </div>
</div>

<div class="contenedor-principal">
    <div class="info-sala">
        <h2>Terraza Kanto</h2>
        <?php if (!empty($errorMsg)): ?>
            <p class="error-msg"><?= htmlspecialchars($errorMsg) ?></p>
        <?php endif; ?>

        <?php if (!$selectedMesa): ?>
            <p>Mesas totales: <?= $total_mesas ?></p>
            <p>Mesas disponibles: <?= $libres ?></p>
            <p><strong>Filtros activos:</strong></p>
            <ul>
                <li>Estado: <?= $filtro_estado==='todas'?'Todas':ucfirst($filtro_estado) ?></li>
                <li>Sillas: <?= $filtro_sillas==='todas'?'Todas':$filtro_sillas ?></li>
            </ul>
            <strong>Selecciona una mesa para ver detalles.</strong>
        <?php else: ?>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($selectedMesa['nombre']) ?></p>
            <p><strong>Estado:</strong> <?= ucfirst($selectedMesa['estado']) ?></p>
            <p><strong>Sillas:</strong> <?= intval($selectedMesa['numSillas']) ?></p>
            <?php if ($selectedMesa['estado'] === 'ocupada' && $nombreUsuarioOcupante): ?>
                <p><strong>Ocupada por:</strong> <?= htmlspecialchars($nombreUsuarioOcupante) ?></p>
                <?php if (!$puedeLiberar): ?>
                    <p style="color: #ff6b6b; font-weight: bold;">⚠️ Solo <?= htmlspecialchars($nombreUsuarioOcupante) ?> puede liberar esta mesa</p>
                <?php endif; ?>
            <?php endif; ?>

            <!-- FORMULARIO CON ID PARA SWEETALERT -->
            <form id="form-cambiar-estado" method="post" action="./kanto.php?filtro_estado=<?= $filtro_estado ?>&filtro_sillas=<?= $filtro_sillas ?>">
                <input type="hidden" name="idMesa" value="<?= intval($selectedMesa['idMesa']) ?>">
                <button type="submit" class="btn-toggle" <?= ($selectedMesa['estado']==='ocupada' && !$puedeLiberar)?'disabled style="background:#ccc; cursor:not-allowed;"':'' ?>>
                    <?= ($selectedMesa['estado']==='libre')?'Marcar como ocupada':'Marcar como libre' ?>
                </button>
            </form>

            <br>
            <a class="btn-toggle" href="../../historial.php?idMesa=<?= intval($selectedMesa['idMesa']) ?>&idSala=<?= intval($selectedMesa['idSala']) ?>">Ver historial de mesa</a>
            <br><br>
            <a class="btn-toggle" href="../../historialSala.php?idSala=<?= intval($selectedMesa['idSala']) ?>">Ver historial de sala</a>
        <?php endif; ?>
    </div>

    <div class="mesas-container">
        <?php if(empty($mesas)): ?>
            <p>No hay mesas disponibles con los filtros seleccionados.</p>
        <?php else: ?>
            <?php foreach($mesas as $m): 
                $cls = ($m['estado']==='ocupada')?'ocupada':'libre';
                $url = "kanto.php?select=".intval($m['idMesa'])."&filtro_estado=$filtro_estado&filtro_sillas=$filtro_sillas";
            ?>
                <a class="mesa <?= $cls ?>" href="<?= $url ?>">
                    <div class="titulo-mesa"><?= htmlspecialchars($m['nombre']) ?></div>
                    <div class="detalle"><?= intval($m['numSillas']) ?> sillas</div>
                    <div class="estado-mesa"><?= ucfirst($m['estado']) ?></div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="volver">
    <a href="../../selecciona_sala.php">← Volver a seleccionar sala</a>
</div>

<footer>
    <span>Pokéfull Stack &copy; 2025</span>
</footer>
<script src="./../../../js/script.js"></script>
</body>
</html>
