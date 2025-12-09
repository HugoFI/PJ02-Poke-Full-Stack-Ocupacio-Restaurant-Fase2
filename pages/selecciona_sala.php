<?php
session_start();

// --- Comprobar si hay sesión activa ---
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=NoSesion");
    exit();
}

require_once './../database/conexion.php';
$stmt = $conn->query("SELECT idSala, nombre, tipo FROM sala");
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$salasComedor = [];
$salasTerraza = [];
$salasPrivada = [];
if ($salas && is_array($salas)) {
  foreach ($salas as $sala) {
    switch ($sala['tipo']) {
      case 'comedor':
        $salasComedor[] = $sala;
        break;
      case 'terraza':
        $salasTerraza[] = $sala;
        break;
      case 'sala privada':
        $salasPrivada[] = $sala;
        break;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selección de Sala - PokéRestaurant</title>
  <link rel="stylesheet" href="./../styles/styles.css">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <header>
    <div class="header-left">
      <div class="logo-header">
          <img src="../img/Logo_Pokefull.png" alt="logo" class="logo">
      </div>
    </div>

    <span>Pokéfull Stack | <?php echo $_SESSION['username'];?></span>
    
    <form id="cerrar-sesion" action="./../processes/logout.php" method="post">
      <button type="submit" class="btn-cerrar">Cerrar sesión</button>
    </form>
  </header>

  <!-- <main class="contenedor-selector-sala"> -->
    <div class="contenedor-sala">
      <!-- Comedores -->
      <h1>Comedores</h1>
      <?php foreach ($salasComedor as $sala): ?>
        <a href="./sala.php?id=<?php echo $sala['idSala']; ?>" class="boton-sala" id="boton-comedor"></a>
        <?php endforeach; ?>
    </div>

    <div class="contenedor-sala">
      <!-- Terrazas -->
      <h1>Terrazas</h1>
      <?php foreach ($salasTerraza as $sala): ?>
        <a href="./sala.php?id=<?php echo $sala['idSala']; ?>" class="boton-sala" id="boton-terraza"></a>
        <?php endforeach; ?>
    </div>

    <div class="contenedor-sala">
      <!-- Salas privadas -->
      <h1>Salas Privadas</h1>
      <?php foreach ($salasPrivada as $sala): ?>
        <a href="./sala.php?id=<?php echo $sala['idSala']; ?>" class="boton-sala" id="boton-sala-privada"></a>
      <?php endforeach; ?>
    </div>
  <!-- </main> -->

  <footer>
    <span>Pokéfull Stack &copy; 2025</span>
  </footer>
<script src="./../js/script.js"></script>
</body>
</html>
