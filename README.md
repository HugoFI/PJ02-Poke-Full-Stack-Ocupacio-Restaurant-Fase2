
# Pok-Full-Stack-Ocupaci-Restaurant

## Descripción del proyecto

Pok-Full-Stack-Ocupaci-Restaurant es una aplicación web para la gestión de ocupación de mesas en un restaurante temático Pokémon. Permite a los camareros registrar su usuario, iniciar sesión, gestionar el estado de las mesas (libre/ocupada), consultar el historial de ocupación y filtrar por salas y mesas. El sistema está desarrollado en PHP, JavaScript y utiliza una base de datos MySQL.

## Funcionamiento de la aplicación web

**1. Registro de camarero:**

- Accede a la página de registro y completa el formulario con tus datos.
- El sistema valida los campos y muestra mensajes de error si hay datos incorrectos.

**2. Login:**

- Inicia sesión con tu usuario y contraseña.
- Si los datos son correctos, accedes al panel de selección de sala.

**3. Selección de sala:**

- Elige un tipo de sala (Terraza, Comedor, Sala Privada) para ver las salas disponibles y dentro de las salas podras ver las mesas y el estado en el que se encuentran (ocupada o libre).

**4. Gestión de mesas:**

- Puedes ocupar una mesa libre o liberar una mesa ocupada (esto ultimo solo si eres el camarero que la ocupó).
- El estado de la mesa se actualiza y se registra en el historial.

**5. Historial:**

- Puedes consultar el historial de ocupación de cada mesa clicando en el boton de historial de mesa cuando tienes seleccionada la mesa que desees o ver el de todas las mesas de la sala.

**6. Cerrar sesión:**

- Utiliza el botón de cerrar sesión para salir de la aplicación de forma segura.

## Miembros del equipo

- Manuel Gálvez
- Oscar Rodríguez
- David Honorato
- Hugo Fernández

## Estructura del proyecto

- `index.php`: Redirección al login.
- `pages/`: Páginas principales de la aplicación (login, registro, selección de sala, historial, gestión de salas y mesas).
- `pages/salas/`: Contiene las carpetas de las diferentes tipos de salas (Terraza, Comedor, Sala Privada) y sus respectivas páginas.
- `pages/salas/comedores`: Páginas de las salas Comedores (Sinnoh, Unova).
- `pages/salas/salas_privadas/`: Paginas de las Salas privadas (Kalos, Alola, Paldea).
- `pages/salas/terrazas`: Páginas de las salas Terrazas (Kanto, Johto, Hoenn).
- `database/`: Conexión y script SQL de la base de datos.
- `js/`: Scripts JavaScript para validaciones y SweetAlert2.
- `styles/`: Hojas de estilo CSS.
- `img/`: Imágenes y logos.
- `processes/`: Lógica de procesos (login, registro, logout).

## Requisitos para pruebas

1. Tener instalado un servidor local (WAMP, XAMPP, etc.) y MySQL.
2. Importar el script `database/database.sql` para crear la base de datos y las tablas necesarias.
3. Configurar correctamente la conexión en `database/conexion.php`.
4. Acceder a `index.php` desde el navegador para comenzar.

## Notas importantes

- La aplicación está pensada para uso interno de camareros.
- El historial y la gestión de mesas están protegidos por sesión.
- Se recomienda probar todas las funcionalidades (registro, login, ocupación/liberación de mesas, historial, filtros).
