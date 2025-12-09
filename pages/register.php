<?php
session_start();
include '../database/conexion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Camarero</title>
    <link rel="stylesheet" href="./../styles/styles.css">
</head>
<body class="body-registro">
    <div class="container-registro">

        <!-- WRAPPER PARA LOGO + FORMULARIO -->
        <div class="registro-wrapper">

            <!-- LOGO ENCIMA DEL FORMULARIO -->
            <div class="logo-registro">
                <img src="../img/Logo_Pokefull.png" alt="logo">
            </div>

            <form class="form-registro" action="../processes/registrar_usuario.php" method="post">
                <div id="heading-registro">REGISTRO usuarios</div>

                <!-- GRID DE DOS COLUMNAS -->
                <div class="registro-grid">

                    <div class="form-group">
                        <label class="label-registro" for="nombre">Nombre</label>
                        <div class="field-registro">
                            <input class="input-field-registro" type="text" name="nombre" id="nombre" placeholder="Nombre">
                        </div>
                        <p id="nombreError"></p>
                    </div>

                    <div class="form-group">
                        <label class="label-registro" for="apellido">Apellido</label>
                        <div class="field-registro">
                            <input class="input-field-registro" type="text" name="apellido" id="apellido" placeholder="Apellido">
                        </div>
                        <p id="apellidoError"></p>
                    </div>

                    <div class="form-group">
                        <label class="label-registro" for="nombreUsuario">Usuario</label>
                        <div class="field-registro">
                            <input class="input-field-registro" type="text" name="username" id="username" placeholder="Usuario">
                        </div>
                        <p id="usernameError"></p>
                    </div>

                    <div class="form-group">
                        <label class="label-registro" for="dni">DNI</label>
                        <div class="field-registro">
                            <input class="input-field-registro" type="text" name="dni" id="dni" placeholder="DNI">
                        </div>
                        <p id="dniError"></p>
                    </div>

                    <div class="form-group">
                        <label class="label-registro" for="telefono">Teléfono</label>
                        <div class="field-registro">
                            <input class="input-field-registro" type="text" name="telefono" id="telefono" placeholder="Teléfono">
                        </div>
                        <p id="telefonoError"></p>
                    </div>

                    <div class="form-group">
                        <label class="label-registro" for="correo">Correo</label>
                        <div class="field-registro">
                            <input class="input-field-registro" type="email" name="correo" id="correo" placeholder="Correo">
                        </div>
                        <p id="correoError"></p>
                    </div>

                    <div class="form-group">
                        <label class="label-registro" for="fecha">Fecha</label>
                        <div class="field-registro">
                            <input class="input-field-registro" type="date" name="fecha" id="fecha">
                        </div>
                        <p id="fechaError"></p>
                    </div>

                    <div class="form-group">
                        <label class="label-registro" for="rol">Rol de usuario</label>
                        <div class="field-registro">
                            <select name="rol" id="rol">
                                <option value="" disabled selected>Selecciona rol de usuario</option>
                                <option value="admin">Admin</option>
                                <option value="gerente">Gerente</option>
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="camarero">Camarero</option>
                            </select>
                        </div>
                        <p id="rolError"></p>
                    </div>
                    
                    <div class="form-group">
                        <label class="label-registro" for="password">Contraseña</label>
                        <div class="field-registro">
                            <input class="input-field-registro" type="password" name="password" id="password" placeholder="Contraseña">
                        </div>
                        <p id="passwordError"></p>
                    </div>

                    <div class="form-group">
                        <label class="label-registro" for="confirma-password">Confirma contraseña</label>
                        <div class="field-registro">
                            <input class="input-field-registro" type="password" name="confirma-password" id="confirma-password" placeholder="Confirma contraseña">
                        </div>
                        <p id="passwordError"></p>
                    </div>

                </div> <!-- cierre registro-grid -->

                <div class="btn-registro">
                    <input type="submit" value="Registrar" name="Registrar" class="button-registro">
                    <a href="./login.php" class="button-registro button-volver">Volver</a>
                </div>

                <?php
                if (isset($_SESSION['errorReg']) && is_array($_SESSION['errorReg'])) {
                    foreach ($_SESSION['errorReg'] as $err) {
                        echo "<p class='error'>$err</p>";
                    }
                    unset($_SESSION['errorReg']);
                } else {
                    echo "<p class='error-message' id='error'></p>";
                }
                ?>

            </form>
        </div> <!-- cierre registro-wrapper -->

    </div> <!-- cierre container-registro -->

</body>
<script src="../js/script.js"></script>
</html>
