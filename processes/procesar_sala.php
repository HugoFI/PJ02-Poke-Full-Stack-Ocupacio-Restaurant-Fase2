<?php
session_start();
require_once '../../../database/conexion.php';

if (!$salaId) {
    header('Location: ./selecciona_sala.php');
    exit;
}
$salaId = $_GET['sala'] ?? null;

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