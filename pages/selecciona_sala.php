<?php
session_start();

// --- Comprobar si hay sesión activa ---
if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=NoSesion");
    exit();
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

  <main class="contenedor-selector-sala">
    <div class="contenedor-selector-sala">
      <!-- Comedor -->
      <a href="./salas/comedores/sinnoh.php" class="boton-sala" id="boton-sala1">
      </a>

      <!-- Terraza -->
      <a href="./salas/terrazas/kanto.php" class="boton-sala" id="boton-sala2">
      </a>

      <!-- Sala privada -->
      <a href="./salas/salas_privadas/Alola.php" class="boton-sala" id="boton-sala3">
      </a>
    </div>  
  </main>

  <footer>
    <span>Pokéfull Stack &copy; 2025</span>
  </footer>
<script src="./../js/script.js"></script>
</body>
</html>
