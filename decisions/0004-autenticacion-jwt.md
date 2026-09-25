# 0004: Autenticación con JWT sin estado

- **Fecha:** 2026-09-25
- **Estado:** aceptada

## Contexto

La interfaz necesitaba un login real contra la BD. El usuario pidió usar JWT. El proyecto usa PHP 7.4 y `General_model` (que no se modifica) lee `$this->session->userdata()` en su constructor para completar `usuario_id` y `empresa_id` al insertar.

## Decisión

- **Librería `firebase/php-jwt` 6.10** (la última compatible con PHP 7.4), instalada con Composer en `api/` (`vendor` en `api/application/vendor`, protegido por el `.htaccess` de `application/`). Envuelta en la librería `Token_jwt`.
- **HS256** con clave secreta de 64 bytes aleatorios en `api/application/config/jwt.php`. Token de **8 horas**, sin token de renovación.
- **Sesión sin estado**: la librería `Sesion_token` lee `Authorization: Bearer <token>`, lo valida y se carga en `autoload.php` **con el nombre `session`**, antes que los modelos. Así `General_model` recibe `id` y `empresa_id` del token sin modificarlo, y el servidor no guarda sesiones.
- **Login solo con `usuario.alias`** y `password_verify()` (las claves ya estaban en bcrypt). Se exige usuario, empresa y rol activos.
- **Respuestas JSON** con el formato `{ exito, datos, mensaje }` y el código HTTP correspondiente (helper `api_helper.php`; al inicio fue un controlador base `MY_Controller`, que el usuario cambió por el helper).
- **Interfaz**: axios (`src/services/api.js`), store de Pinia `sesion`, guardia de rutas y proxy de Vite (`/api` → API), sin CORS.

## Alternativas descartadas

- **php-jwt 7.x**: corrige el aviso de seguridad, pero necesita PHP 8.
- **Implementación propia de HS256**: se llegó a escribir y probar, pero el usuario prefirió la librería estándar.
- **Sesión de CodeIgniter + JWT**: copiar los datos del token en la sesión de PHP en cada petición. Crea archivos de sesión y cookie, así que deja de ser sin estado.
- **Login con alias o correo**: el usuario eligió solo alias, que es el campo de login en la BD.

## Consecuencias

- **Aviso de seguridad aceptado**: `PKSA-y2cr-5h3j-g3ys` / `GHSA-2x45-7fc3-mxwq` (severidad baja, php-jwt < 7 acepta claves HMAC cortas). Se ignora solo ese ID en `api/composer.json` (`config.policy.advisories.ignore-id`). Mitigación: `Token_jwt` exige una clave de al menos 32 bytes (la API no arranca si no) y fija el algoritmo en HS256. **Al pasar a PHP 8 se actualiza a php-jwt 7.x y se quita la excepción.**
- Un token robado sirve hasta que vence (máximo 8 h) y no se puede revocar. Si hace falta revocar (cambio de clave, desactivar un usuario al instante), habrá que agregar una lista de tokens revocados o tokens de renovación, lo que requiere tablas nuevas.
- Desactivar un usuario, su rol o su empresa lo saca en la siguiente llamada a `auth/yo` (que la interfaz hace al cargar), pero no en las demás rutas mientras no se revise en cada petición.
- El token se guarda en `localStorage` o `sessionStorage`: queda expuesto si la interfaz tuviera un fallo XSS. Es el compromiso habitual de una SPA.
- Si se filtra `jwt_clave`, cualquiera puede fabricar tokens: se cambia y se invalidan todas las sesiones. `config/jwt.php` y `config/database.php` **no se versionan** con valores reales.
- `api/composer.json` ya no es el del framework CodeIgniter (ese solo servía para desarrollar el framework); ahora es el del proyecto.
