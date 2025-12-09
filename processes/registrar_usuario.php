<?php
session_start();
require_once '../database/conexion.php';

// Validaciones básicas
if (filter_has_var(INPUT_POST, 'Registrar')) {

    // Obtener y limpiar los datos del formulario
       $nombre = trim($_POST['nombre'] ?? '');
       $apellido = trim($_POST['apellido'] ?? '');
       $username = trim($_POST['username'] ?? '');
       $dni = trim($_POST['dni'] ?? '');
       $telefono = trim($_POST['telefono'] ?? '');
       $correo = trim($_POST['correo'] ?? '');
       $fecha = $_POST['fecha'] ?? '';
       $rol = $_POST['rol'] ?? '';  
       $password = $_POST['password'] ?? '';  
       $confirma_password = $_POST['confirma-password'] ?? '';  

       // Creamos el array para los errores
       $_SESSION['errorReg'] = [];

       // Validar nombre
       if (empty($nombre)) {
              $_SESSION['errorReg'][] = "El nombre es obligatorio<br>";

       } else if(strlen($nombre) < 3) {
              $_SESSION['errorReg'][] = "El nombre debe tener al menos 3 caracteres<br>";

       } else if(strlen($nombre) > 20) {
              $_SESSION['errorReg'][] = "El nombre debe tener menos de 20 caracteres<br>";
       }

       // Validar apellido
       $separacion = preg_split('/\s+/', $apellido);
       $separacion = array_filter($separacion); // Eliminar elementos vacíos

       if (empty($apellido)) {
              $_SESSION['errorReg'][] = "El apellido es obligatorio<br>";

       } else if(count($separacion) < 2) {
              $_SESSION['errorReg'][] = "El apellido debe tener al menos 2 palabras<br>";

       } else if(strlen($apellido) < 3) {
              $_SESSION['errorReg'][] = "El apellido debe tener al menos 3 caracteres<br>";

       } else if(strlen($apellido) > 20) {
              $_SESSION['errorReg'][] = "El apellido debe tener menos de 20 caracteres<br>";
       }

       // Validar username
       if (empty($username)) {
              $_SESSION['errorReg'][] = "El nombre de usuario es obligatorio<br>";

       } else if(strlen($username) < 3) {
              $_SESSION['errorReg'][] = "El nombre de usuario debe tener al menos 3 caracteres<br>";

       } else if(strlen($username) > 20) {
              $_SESSION['errorReg'][] = "El nombre de usuario debe tener menos de 20 caracteres<br>";
       }

       // Validar DNI
       if (empty($dni)) {
              $_SESSION['errorReg'][] = "El DNI es obligatorio<br>";

       } else if(strlen($dni) < 7) {
              $_SESSION['errorReg'][] = "El DNI debe tener al menos 7 caracteres<br>";

       } else if(!preg_match('/^[a-zA-Z0-9]+$/', $dni)) {
              $_SESSION['errorReg'][] = "El DNI debe contener solo letras y números<br>";
       }   

       // Validar telefono - CORREGIDO: is_nan no existe en PHP
       if (empty($telefono)) {
              $_SESSION['errorReg'][] = "El teléfono es obligatorio<br>";

       } else if(strlen($telefono) < 9 || strlen($telefono) > 10) {
              $_SESSION['errorReg'][] = "El teléfono debe tener entre 9 y 10 caracteres<br>";

       } else if(!ctype_digit($telefono)) { // CORRECCIÓN: usar ctype_digit en lugar de is_nan
              $_SESSION['errorReg'][] = "El teléfono debe ser numérico<br>";
       }

       // Validar correo
       if (empty($correo)) {
              $_SESSION['errorReg'][] = "El correo es obligatorio<br>";

       } else if(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
              $_SESSION['errorReg'][] = "El correo debe ser válido<br>";
       }

       // Validar rol de usuario
       if (empty($rol)) {
              $_SESSION['errorReg'][] = "El rol de usuario es obligatorio<br>";
       }

       // Validar contraseña
       if (empty($password)) {
              $_SESSION['errorReg'][] = "La contraseña es obligatoria<br>";

       } else if(!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
              $_SESSION['errorReg'][] = "La contraseña debe tener al menos una letra mayúscula y un número<br>";
       }

       // Validar confirmar contraseña
       if (empty($confirma_password)) {
              $_SESSION['errorReg'][] = "La confirmación de la contraseña es obligatoria<br>";
              
       } else if($confirma_password !== $password) {
              $_SESSION['errorReg'][] = "Las contraseñas deben coincidir<br>";
       }

       // Si hay errores, redirigir y salir
       if(!empty($_SESSION['errorReg'])) {
              header("Location: ../pages/register.php?ErrorAlCrearRegistro");
              exit();
       }

       // SI NO HAY ERRORES, proceder con la inserción
       try {
              // Verificar si el nombre de usuario ya existe
              $sql = "SELECT idUsuario FROM usuarios WHERE nombreUsu = :username OR email = :correo OR dni = :dni";
              $stmt = $conn->prepare($sql);
              $stmt->execute([
              ':username' => $username,
              ':correo' => $correo,
              ':dni' => $dni
              ]);
              
              $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);
              
              if ($usuarioExistente) {
              $_SESSION['errorReg'][] = "Error: El nombre de usuario, correo o DNI ya están registrados";
              header("Location: ../pages/register.php?ErrorAlCrearRegistro");
              exit();
              }

              // Hashear la contraseña
              $contrasenaHash = password_hash($password, PASSWORD_DEFAULT);

              // Insertar el nuevo usuarios
              $sql = "INSERT INTO usuarios (nombre, apellidos, nombreUsu, dni, telefono, email, fechaContratacion, rol, password) 
                     VALUES (:nombre, :apellidos, :username, :dni, :telefono, :email, :fecha, :rol, :password)";
              
              $stmt = $conn->prepare($sql);
              
              $resultado = $stmt->execute([
              ':nombre' => $nombre,
              ':apellidos' => $apellido,
              ':username' => $username,
              ':dni' => $dni,
              ':telefono' => $telefono,
              ':email' => $correo,
              ':fecha' => $fecha,
              ':rol' => $rol,
              ':password' => $contrasenaHash
              ]);

              if ($resultado) {
              // Éxito - redirigir a la página de selección de sala
              header("Location: ../pages/selecciona_sala.php?registro=exitoso");
              exit();
              } else {
              $_SESSION['errorReg'][] = "Error al crear el registro en la base de datos";
              header("Location: ../pages/register.php?ErrorAlCrearRegistro");
              exit();
              }

       } catch (PDOException $e) {
              $_SESSION['errorReg'][] = "Error de base de datos: " . $e->getMessage();
              header("Location: ../pages/register.php?ErrorAlCrearRegistro");
              exit();
       } catch (Exception $e) {
              $_SESSION['errorReg'][] = "Error: " . $e->getMessage();
              header("Location: ../pages/register.php?ErrorAlCrearRegistro");
              exit();
       }
}
?>