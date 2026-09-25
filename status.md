# Estado actual

_Última actualización: 2026-09-25_

## Hecho

- CodeIgniter 3.1.13 instalado en `api/`, sin código propio todavía (solo el controlador `Welcome` de ejemplo).
- Carpeta `interfaz/` creada, vacía.
- Brain y `CLAUDE.md` creados, con wiki y reglas obligatorias (documentar todo, no hacer commit ni push, BD solo lectura).
- Commit y push: Claude solo los hace cuando el usuario lo ordena; cada uno pide confirmación (`.claude/settings.json`).

## En curso

- (nada)

## Pendiente

- [ ] Definir objetivo y alcance del proyecto (`overview.md`).
- [ ] Elegir la tecnología del frontend.
- [ ] Crear la base de datos y configurar `api/application/config/database.php`.
- [ ] Configurar `base_url` y `encryption_key` en `api/application/config/config.php`.
- [ ] Definir el formato de las respuestas JSON y la autenticación de la API.
- [ ] Inicializar un repositorio git para el código (`brain/` ya tiene el suyo: `logy-brain`).
- [ ] Hacer el primer commit y el push de `logy-brain` (repo creado: https://github.com/and3rly/logy-brain, remoto ya configurado).
- [ ] Crear el usuario MySQL de solo lectura para Claude (script en `architecture.md`).

## Problemas conocidos

- (ninguno)
