# Instalación de Vue 3 + Vite en la interfaz

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `interfaz/` (todo)

## Objetivo

Instalar Vite y Vue en `interfaz/`, las tecnologías que eligió el usuario para el frontend.

## Qué se hizo

- Análisis previo: `interfaz/` estaba vacía y no había nada que reutilizar. Node.js no estaba instalado; lo instaló el usuario (v24.21.0, npm 11.19.0).
- El usuario eligió: JavaScript, Vue Router y Pinia; sin ESLint ni Prettier.
- Se creó el proyecto con:
  ```
  npx create-vue@latest interfaz --router --pinia --bare --force
  cd interfaz && npm install
  ```
  Se instalaron 146 paquetes (vue 3.5, vue-router 5.3, pinia 4.0, vite 8.2).
- Verificación: `npm run build` compila sin errores (se borró el `dist/` de la prueba).
- Se documentó en `interfaz.md`, `architecture.md`, `conventions.md`, `overview.md` y en la decisión `0002`.

## Hallazgos / resultado

- La interfaz queda como SPA en otro origen (`localhost:5173`) distinto al de la API (`localhost/logy/api`). Hay que decidir entre proxy de Vite o CORS antes de consumir la API.
- `App.vue` y `stores/counter.js` son de la plantilla y se reemplazarán con la primera pantalla.
- `interfaz/.gitignore` ya excluye `node_modules/` y `dist/`.
- npm avisa que existe una versión mayor (12.1.0); no se actualizó.

## Pendientes

- [ ] Definir cómo se conecta la interfaz con la API (proxy de Vite o CORS).
- [ ] Elegir la librería de UI, CSS e iconos (identidad visual).
- [ ] Elegir el cliente HTTP (`fetch` o axios).
