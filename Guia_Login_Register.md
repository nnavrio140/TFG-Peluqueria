# Guía Simple: Cómo Funciona el Login y Registro en Esta App

Esta app tiene dos partes: el **frontend** (hecho con React, que es lo que ves en la pantalla) y el **backend** (hecho con PHP/Laravel, que es el servidor que guarda los datos). El login y registro permiten a los usuarios entrar o crear una cuenta. Usan autenticación normal (email/contraseña) y con Google. Explico cada parte de manera muy simple.

## Partes del Frontend (React)

- **AuthContext.jsx** (en `src/context/AuthContext.jsx`): Es como un "almacén global" que guarda si el usuario está logueado o no. Comparte esta info con toda la app para que sepa quién es el usuario y si puede ver páginas privadas. También tiene funciones para hacer login, logout y verificar si el usuario está cargando.

- **authService.js** (en `src/services/authService.js`): Es el "mensajero" que envía mensajes al servidor (backend). Tiene funciones para pedir login, registro, o verificar el token (como una llave secreta). Usa herramientas como Axios para hablar con el servidor.

- **Login.jsx** (en `src/views/Auth/Login.jsx`): Es la página donde el usuario pone su email y contraseña para entrar. Envía los datos al servidor a través del servicio, y si sale bien, guarda el usuario en el context. También tiene un botón para login con Google.

- **Register.jsx** (en `src/views/Auth/Register.jsx`): Es la página para crear una nueva cuenta. El usuario pone nombre, email, contraseña, etc. Envía todo al servidor, y si se registra bien, lo loguea automáticamente.

- **GoogleSuccess.jsx** (en `src/views/Auth/GoogleSuccess.jsx`): Es la página que se abre después de que el usuario se loguea con Google. Recibe la info de Google (como nombre y email), la envía al servidor para crear o verificar la cuenta, y luego redirige al usuario a la página principal.

- **Header.jsx** (en `src/components/Header/Header.jsx`): Es la barra de arriba de la página. Muestra si el usuario está logueado (con su nombre y un menú para cerrar sesión) o botones para login/registro si no lo está. Usa el context para saber el estado del usuario.

## Partes del Backend (PHP/Laravel)

- **Controllers** (en `app/Http/Controllers/`): Son como "jefes" que reciben las peticiones del frontend. Por ejemplo, hay uno para autenticación que maneja login, registro y logout. Verifican los datos, guardan en la base de datos y devuelven respuestas.

- **Models** (en `app/Models/`): Son "plantillas" para los datos, como el modelo de Usuario. Definen cómo se guardan cosas como nombre, email, contraseña en la base de datos.

- **Routes** (en `routes/api.php`): Son las "rutas" o caminos que el frontend usa para hablar con el backend. Por ejemplo, una ruta para "/login" que va al controller de login.

- **Middleware** (en `app/Http/Middleware/`): Son "guardias" que protegen las rutas. Por ejemplo, uno que verifica si el usuario tiene un token válido antes de dejarlo acceder a páginas privadas.

- **Sanctum** (en config): Es una herramienta de Laravel para manejar tokens de autenticación segura, como las "llaves" que el frontend usa para recordar al usuario.

## Flujo Completo (Paso a Paso)

1. **Registro/Login Normal**: El usuario va a la página de registro o login, pone sus datos. El frontend envía al backend. El backend guarda/verifica en la base de datos y devuelve un token. El frontend lo guarda y marca al usuario como logueado.

2. **Login con Google**: El usuario hace clic en "Login con Google". Va a Google, se autentica, y vuelve a la página GoogleSuccess. Ahí, el frontend envía la info de Google al backend, que crea/verifica la cuenta y devuelve un token.

3. **Después de Loguearse**: El context se actualiza, el header muestra el nombre del usuario, y puede acceder a páginas protegidas. Si cierra sesión, borra el token y vuelve al estado no logueado.

4. **Protección**: El backend usa middleware para que solo usuarios logueados vean ciertas cosas. El frontend usa el context para mostrar/ocultar botones.

Esto es lo básico. Si algo no queda claro, pregunta! La app usa Docker para correr el backend fácilmente.