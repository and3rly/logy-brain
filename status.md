# Estado actual

_Última actualización: 2026-09-25_

## Hecho

- CodeIgniter 3.1.13 instalado en `api/`. Código propio: el modelo base `General_model` (ver `api.md`) y el controlador `Welcome` de ejemplo.
- Interfaz creada con Vue 3 + Vite (JavaScript, Vue Router, Pinia).
- Plantilla administrativa lista: layout con sidebar contraíble y menú móvil, navbar, tema claro/oscuro, componentes base y pantallas de ejemplo (dashboard, tabla, formulario) con datos ficticios. Bootstrap 5 (CSS) + Font Awesome 7 + Inter (ver `interfaz.md` y `decisions/0003`).
- Login con JWT funcionando: `POST auth/login` y `GET auth/yo` en la API (firebase/php-jwt 6.10, sesión sin estado con `Sesion_token`), y en la interfaz axios, store `sesion`, guardia de rutas y proxy de Vite. Ver `api.md` y `decisions/0004`.
- Formato estándar de respuestas JSON: `{ exito, datos, mensaje }`.
- BD conectada: MySQL 8.0 remoto `db_logy`, 46 tablas, analizada (ver `base-de-datos.md`).
- Brain publicado en https://github.com/and3rly/logy-brain (público) e instalado con `instalar.ps1`.
- Código publicado en https://github.com/and3rly/logy-app (api + interfaz, sin el brain ni los secretos; un solo `.gitignore`).
- Reglas: documentar todo en la wiki; commit y push solo cuando el usuario lo ordena (cada uno pide confirmación); BD solo lectura; los modelos extienden `General_model` (excepciones solo con autorización).

## En curso

- (nada)

## Pendiente

- [ ] Cambiar el usuario de BD de la aplicación, que hoy es superadministrador, por uno con permisos mínimos.
- [ ] Probar el login con un usuario real (Claude no conoce contraseñas).
- [ ] Definir cómo llega la interfaz a la API en producción (sin el proxy de Vite).
- [ ] Opcional: índice único en `usuario.alias` (SQL en la wiki `2026-09-25-login-jwt.md`, lo ejecuta el usuario).
- [ ] Cargar el menú del sidebar desde la API (tablas `modulo`/`menu`) en lugar de `interfaz/src/config/menu.js`.
- [ ] Configurar `base_url` en `api/application/config/config.php` (`encryption_key` no hace falta mientras no se use la librería Encryption).
- [ ] Crear el usuario MySQL de solo lectura para Claude (script en `architecture.md`).

## Problemas conocidos

- php-jwt 6.10 tiene un aviso de seguridad de severidad baja (claves débiles), aceptado y mitigado; al pasar a PHP 8 se actualiza a la 7.x (ver `decisions/0004`).
- Los tokens no se pueden revocar antes de que venzan (máx. 8 h).
- `General_model::buscar()`: la paginación con `_inicio` no funciona y `_between` tiene riesgo de inyección SQL. Por decisión del usuario **no se corrige**: se rodea desde el código nuevo.
