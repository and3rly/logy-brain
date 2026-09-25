# Estado actual

_Última actualización: 2026-09-25_

## Hecho

- CodeIgniter 3.1.13 instalado en `api/`. Código propio: el modelo base `General_model` (ver `api.md`) y el controlador `Welcome` de ejemplo.
- Carpeta `interfaz/` creada, vacía.
- Brain publicado en https://github.com/and3rly/logy-brain (público) e instalado con `instalar.ps1`.
- Reglas: documentar todo en la wiki; commit y push solo cuando el usuario lo ordena (cada uno pide confirmación); BD solo lectura.

## En curso

- (nada)

## Pendiente

- [ ] Definir objetivo y alcance del proyecto (`overview.md`).
- [ ] Elegir la tecnología del frontend.
- [ ] Crear la base de datos y configurar `api/application/config/database.php`.
- [ ] Cargar `database` y `session` en `autoload.php` y configurar `sess_save_path` (sin esto, `General_model` no funciona).
- [ ] Decidir si se corrigen los errores detectados en `General_model` (ver `api.md`).
- [ ] Configurar `base_url` y `encryption_key` en `api/application/config/config.php`.
- [ ] Definir el formato de las respuestas JSON y la autenticación de la API.
- [ ] Inicializar un repositorio git para el código (`brain/` ya tiene el suyo: `logy-brain`).
- [ ] Crear el usuario MySQL de solo lectura para Claude (script en `architecture.md`).

## Problemas conocidos

- `General_model` depende de las librerías `database` y `session`, que hoy no se cargan.
- `General_model::buscar()`: la paginación con `_inicio` no funciona y `_between` tiene riesgo de inyección SQL.
