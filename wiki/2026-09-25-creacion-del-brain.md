# Creación del brain y reglas del proyecto

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `CLAUDE.md`, `brain/`, `.claude/settings.json`

## Objetivo

Crear una base de conocimiento del proyecto que Claude cargue en cada sesión y definir las reglas de trabajo.

## Qué se hizo

- Revisión inicial: `api/` tiene CodeIgniter 3.1.13 sin código propio; `interfaz/` está vacía; PHP 7.4.33.
- Se creó `CLAUDE.md` en la raíz; carga automáticamente `overview.md`, `status.md` y `conventions.md`.
- Se creó `brain/` con overview, status, conventions, architecture, api, interfaz, glossary, decisions/ y wiki/.
- Se definieron las reglas: documentar todo en la wiki, nunca hacer commit ni push, y usar la base de datos solo en modo lectura.
- Se bloquearon `git commit` y `git push` en `.claude/settings.json`.

## Hallazgos / resultado

El brain está listo, con los puntos no decididos marcados como **Por definir**.

## Pendientes

- [ ] Completar los **Por definir** de `overview.md` y `conventions.md`.
- [ ] Crear un usuario MySQL de solo lectura para Claude (ver `architecture.md`).
