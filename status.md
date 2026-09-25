# Estado actual

_Última actualización: 2026-09-25_

## Hecho

- CodeIgniter 3.1.13 instalado en `api/`. Código propio: el modelo base `General_model` (ver `api.md`) y el controlador `Welcome` de ejemplo.
- Interfaz creada con Vue 3 + Vite (JavaScript, Vue Router, Pinia).
- Plantilla administrativa lista: layout con sidebar contraíble y menú móvil, navbar, tema claro/oscuro, componentes base y pantallas de ejemplo (dashboard, tabla, formulario) con datos ficticios. Bootstrap 5 (CSS) + Font Awesome 7 + Inter (ver `interfaz.md` y `decisions/0003`).
- BD conectada: MySQL 8.0 remoto `db_logy`, 46 tablas, analizada (ver `base-de-datos.md`).
- Brain publicado en https://github.com/and3rly/logy-brain (público) e instalado con `instalar.ps1`.
- Reglas: documentar todo en la wiki; commit y push solo cuando el usuario lo ordena (cada uno pide confirmación); BD solo lectura.

## En curso

- (nada)

## Pendiente

- [ ] Cambiar el usuario de BD de la aplicación, que hoy es superadministrador, por uno con permisos mínimos.
- [ ] Definir cómo se conecta la interfaz con la API: proxy de Vite o CORS (ver `architecture.md`).
- [ ] Elegir el cliente HTTP de la interfaz (`fetch` o una librería).
- [ ] Cargar el menú del sidebar desde la API (tablas `modulo`/`menu`) en lugar de `interfaz/src/config/menu.js`.
- [ ] Cargar `database` y `session` en `autoload.php` y configurar `sess_save_path` (sin esto, `General_model` no funciona).
- [ ] Configurar `base_url` y `encryption_key` en `api/application/config/config.php`.
- [ ] Definir el formato de las respuestas JSON y la autenticación de la API.
- [ ] Inicializar un repositorio git para el código (`brain/` ya tiene el suyo: `logy-brain`).
- [ ] Crear el usuario MySQL de solo lectura para Claude (script en `architecture.md`).

## Problemas conocidos

- `General_model` depende de las librerías `database` y `session`, que hoy no se cargan.
- `General_model::buscar()`: la paginación con `_inicio` no funciona y `_between` tiene riesgo de inyección SQL. Por decisión del usuario **no se corrige**: se rodea desde el código nuevo.
