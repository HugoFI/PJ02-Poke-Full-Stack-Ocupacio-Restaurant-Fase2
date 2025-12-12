<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../database/conexion.php';

// Obtener el id de la sala a editar
if (!isset($_POST['idMesa'])) {
    header('Location: crud_recursos.php');
    exit;
}

// Obtener las salas para el select
$stmtSalas = $conn->prepare('SELECT idSala, nombre, tipo FROM sala');
$stmtSalas->execute();
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);


$idMesa = intval($_POST['idMesa']);
// Obtener datos actuales de la mesa
$stmt = $conn->prepare('SELECT nombre, idSala, numSillas, estado FROM mesa WHERE idMesa = ?');
$stmt->execute([$idMesa]);
$mesa = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$mesa) {
    echo '<p>Mesa no encontrada.</p>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mesa</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <div class="header-left">
            <div class="logo-header">
                <img src="../img/Logo_Pokefull.png" alt="logo" class="logo">
            </div>
            <span><?php echo $_SESSION['username'];?></span>
        </div>
        <h2>Editar Mesa</h2>
		<a href="crud_recursos.php" class="btn-cerrar">Volver</a>
	</header>
    <form action="./../processes/procesar_editar_mesa.php" id="editar-elemento" method="POST">
        <input type="hidden" name="idMesa" value="<?php echo $idMesa; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($mesa['nombre']); ?>">
        <br>
        <label for="numSillas">Numero de sillas:</label>
        <input type="text" id="numSillas" name="numSillas" value="<?php echo htmlspecialchars($mesa['numSillas']); ?>">
        <br>
        <label for="estado">Estado:</label>
        <select id="estado" name="estado">
            <option value="libre" <?php if ($mesa['estado'] == 'libre') echo 'selected'; ?>>Libre</option>
            <option value="ocupada" <?php if ($mesa['estado'] == 'ocupada') echo 'selected'; ?>>Ocupada</option>
            <option value="reservada" <?php if ($mesa['estado'] == 'reservada') echo 'selected'; ?>>Reservada</option>
            <option value="mantenimiento" <?php if ($mesa['estado'] == 'mantenimiento') echo 'selected'; ?>>Mantenimiento</option>
        </select>
        <br>
        <label for="idSala">Sala:</label>
        <select id="idSala" name="idSala">
            <?php foreach ($salas as $sala): ?>
                <option value="<?php echo $sala['idSala']; ?>" <?php if ($sala['idSala'] == $mesa['idSala']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($sala['nombre']); echo " (" . htmlspecialchars($sala['tipo']) . ")"; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit" name="actualizar_mesa">Actualizar Mesa</button>
    </form>
<script src="./../js/script.js"></script>
</body>
</html>
