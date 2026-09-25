# Regla: los modelos extienden General_model

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `brain/instrucciones.md`, `brain/conventions.md`, `brain/api.md`

## Objetivo

El usuario pidió agregar una regla importante: los modelos del backend se extienden de `General_model`.

## Qué se hizo

- Se agregó la **regla 5** a `instrucciones.md` (reglas obligatorias): todo modelo nuevo extiende `General_model`. Uno que no lo extienda requiere la autorización explícita del usuario para ese caso; Claude lo propone, explica el motivo y espera la respuesta.
- `conventions.md` y `api.md` decían "siempre" / "todos"; se ajustaron para remitir a la regla 5 y su excepción.

## Hallazgos / resultado

- El usuario dijo que "la mayoría" de modelos extienden `General_model`. Ante la pregunta de cuáles serían las excepciones, eligió que **no haya un criterio general**: cada excepción se autoriza caso por caso.
- Las excepciones aprobadas se registran en la wiki y en un comentario en la cabecera del modelo, para que quede el motivo.

## Pendientes

- (ninguno)
