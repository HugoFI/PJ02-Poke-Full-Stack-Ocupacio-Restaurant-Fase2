<?php
require_once("../database/conexion.php");

// Validar sala
$sala = null;
$mesas = [];
$errorMsg = null;
if (!isset($_GET['idSala'])) {
    header('Location: selecciona_sala.php');
    exit();
}

$idSala = intval($_GET['idSala']);
$sqlSala = "SELECT nombre, tipo FROM sala WHERE idSala = ?";
$stmtSala = $conn->prepare($sqlSala);
$stmtSala->execute([$idSala]);
$sala = $stmtSala->fetch(PDO::FETCH_ASSOC);

// Filtros
$filtro_estado = $_GET['filtro_estado'] ?? 'todas'; // 'todas', 'ocupadas', 'libres'
$filtro_sillas = $_GET['filtro_sillas'] ?? 'todas'; // 'todas', '1', '2', '3', '4'

if ($sala) {
    $sqlMesas = "SELECT * FROM mesa WHERE idSala = ?";
    $params = [$idSala];
    if ($filtro_estado === 'ocupadas') {
        $sqlMesas .= " AND estado = 'ocupada'";
    } elseif ($filtro_estado === 'libres') {
        $sqlMesas .= " AND estado = 'libre'";
    }
    if ($filtro_sillas === '1') {
        $sqlMesas .= " AND numSillas = 1";
    } elseif ($filtro_sillas === '2') {
        $sqlMesas .= " AND numSillas = 2";
    } elseif ($filtro_sillas === '3') {
        $sqlMesas .= " AND numSillas = 3";
    } elseif ($filtro_sillas === '4') {
        $sqlMesas .= " AND numSillas = 4";
    }
    $sqlMesas .= " ORDER BY nombre";
    $stmtMesas = $conn->prepare($sqlMesas);
    $stmtMesas->execute($params);
    $mesas = $stmtMesas->fetchAll(PDO::FETCH_ASSOC);
    $errorMsg = null;
} else {
    $errorMsg = "Sala no encontrada.";
}

// Mesa seleccionada
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
    // Si la mesa está ocupada, obtener el camarero que la ocupa
    if ($selectedMesa && $selectedMesa['estado'] === 'ocupada') {
        $sql = "SELECT c.nombre, c.apellidos, c.idUsuario FROM historico h INNER JOIN usuarios c ON h.idUsuario = c.idUsuario WHERE h.idMesa = :idMesa AND h.horaDesocupacion IS NULL ORDER BY h.idHistorico DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':idMesa' => $selectedMesa['idMesa']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $nombreUsuarioOcupante = $row['nombre'] . ' ' . $row['apellidos'];
            $idUsuarioOcupante = $row['idUsuario'];
            $puedeLiberar = (isset($_SESSION['idUsuario']) && $idUsuarioOcupante == $_SESSION['idUsuario']);
        }
    }
}

// Contadores para filtros
$total_mesas = count($mesas);
$ocupadas = 0;
$libres = 0;
$sillas_1 = 0;
$sillas_2 = 0;
$sillas_3 = 0;
$sillas_4 = 0;
foreach ($mesas as $m) {
    if ($m['estado'] === 'ocupada') $ocupadas++;
    if ($m['estado'] === 'libre') $libres++;
    if ($m['numSillas'] == 1) $sillas_1++;
    if ($m['numSillas'] == 2) $sillas_2++;
    if ($m['numSillas'] == 3) $sillas_3++;
    if ($m['numSillas'] == 4) $sillas_4++;
}

if (isset($_SESSION['error'])) {
    $errorMsg = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>