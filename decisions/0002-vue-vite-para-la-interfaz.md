# 0002: Vue 3 + Vite para la interfaz

- **Fecha:** 2026-09-25
- **Estado:** aceptada

## Contexto

Hacía falta elegir la tecnología del frontend (`interfaz/`), que consumirá la API de CodeIgniter 3.

## Decisión

El usuario eligió **Vue 3 con Vite**, en **JavaScript**, con **Vue Router** (navegación) y **Pinia** (estado global). El proyecto se creó con `create-vue` (la herramienta oficial) usando la plantilla mínima (`--bare`), sin componentes de ejemplo.

## Alternativas descartadas

- TypeScript: descartado por ahora a favor de JavaScript, que es más simple.
- ESLint + Prettier: no se incluyeron al inicio; se pueden agregar después.

## Consecuencias

- La interfaz es una SPA separada de la API. En desarrollo corre en el servidor de Vite (otro puerto), así que hay que resolver cómo llega a la API: un proxy de Vite o CORS (ver `architecture.md`).
- Hace falta Node.js `^22.18.0 || >=24.12.0` para desarrollar.
