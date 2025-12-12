<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../database/conexion.php';

// Obtener el id de la sala a editar
if (!isset($_POST['idSala'])) {
    header('Location: crud_recursos.php');
    exit;
}
$idSala = intval($_POST['idSala']);
// Obtener datos actuales de la sala
$stmt = $conn->prepare('SELECT nombre, tipo FROM sala WHERE idSala = ?');
$stmt->execute([$idSala]);
$sala = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$sala) {
    echo '<p>Sala no encontrada.</p>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sala</title>
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
        <h2>Editar Sala</h2>
		<a href="crud_recursos.php" class="btn-cerrar">Volver</a>
	</header>
    <form action="./../processes/procesar_editar_sala.php" id="editar-elemento" method="POST">
        <input type="hidden" name="idSala" value="<?php echo $idSala; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($sala['nombre']); ?>">
        <br>
        <label for="tipo">Tipo:</label>
        <input type="text" id="tipo" name="tipo" value="<?php echo htmlspecialchars($sala['tipo']); ?>">
        <br>
        <button type="submit" name="actualizar_sala">Actualizar Sala</button>
    </form>
<script src="./../js/script.js"></script>
</body>
</html>