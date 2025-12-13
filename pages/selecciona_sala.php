<?php
session_start();

// --- Comprobar si hay sesión activa ---
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=aNoSesion");
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
      <?php echo $_SESSION['username'];?>
    </div>

    <div>
      <a class="btn-cerrar" href="./crud_recursos.php">Gestión de Recursos</a>
      <a class="btn-cerrar" href="./crud_usuarios.php">Gestión de Usuarios</a>
    </div>

    <span><h2>Pokéfull Stack</h2></span>
    
    <a class="btn-cerrar" href="./reserva.php">Crear reserva</a>

    <form id="cerrar-sesion" action="./../processes/logout.php" method="post">
      <button type="submit" class="btn-cerrar">Cerrar sesión</button>
    </form>
  </header>

  <main class="contenedor-selector-sala">
    <div class="contenedor-sala">
      <h1>Comedores</h1>
      <div class="salas-grid">
        <?php foreach ($salasComedor as $sala): ?>
          <a href="./sala.php?idSala=<?php echo $sala['idSala']; ?>" class="boton-sala" id="boton-comedor"><?php echo htmlspecialchars($sala['nombre']); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="contenedor-sala">
      <h1>Terrazas</h1>
      <div class="salas-grid">
        <?php foreach ($salasTerraza as $sala): ?>
          <a href="./sala.php?idSala=<?php echo $sala['idSala']; ?>" class="boton-sala" id="boton-terraza"><?php echo htmlspecialchars($sala['nombre']); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="contenedor-sala">
      <h1>Salas Privadas</h1>
      <div class="salas-grid">
        <?php foreach ($salasPrivada as $sala): ?>
          <a href="./sala.php?idSala=<?php echo $sala['idSala']; ?>" class="boton-sala" id="boton-sala-privada"><?php echo htmlspecialchars($sala['nombre']); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

<script src="./../js/script.js"></script>
</body>
</html>
