# Plantilla administrativa de la interfaz

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `interfaz/` (index.html, package.json, src/*), `.claude/launch.json`

## Objetivo

Crear una plantilla administrativa moderna y responsive (Vue 3 + Vite, Bootstrap 5, Font Awesome, CSS propio) con layout, dashboard, página de tabla y página de formulario de ejemplo. Solo diseño: sin login, API, roles ni lógica de negocio.

## Qué se hizo

- Análisis previo: `interfaz/` solo tenía el esqueleto de `create-vue` (sin pantallas ni componentes), así que no había nada que reutilizar; esta plantilla **establece** la convención visual (ver `desarrollo.md`). Se reutilizaron Vue Router y Pinia, ya instalados.
- Dependencias nuevas: `bootstrap@5.3`, `@fortawesome/fontawesome-free@7`, `@fontsource-variable/inter`. Decisión en `decisions/0003-plantilla-visual-bootstrap.md`.
- Se borró `src/stores/counter.js` (ejemplo de create-vue) y se reemplazó `App.vue`.
- Estructura creada:
  - `assets/css/variables.css` (tokens, tema claro/oscuro) y `main.css` (base, ajustes a Bootstrap, layout).
  - `layouts/AdminLayout.vue`; `components/layout/` Sidebar, Navbar, Breadcrumb.
  - `components/ui/` BaseCard, BaseButton, BaseInput, BaseSelect, BaseCheckbox, BaseBadge, BaseModal, BaseTable, BasePagination, BaseDropdown, PageHeader.
  - `components/charts/` LineChart y DonutChart (SVG propio).
  - `views/` Dashboard, TableExample (`/usuarios`), FormExample (`/usuarios/nuevo`), EnConstruccion (resto del menú), NotFound.
  - `config/app.js` (nombre del sistema, usuario de demostración), `config/menu.js` (menú), `data/demo.js` (datos ficticios), `stores/plantilla.js` (tema, sidebar, menú móvil).
- Se agregó `.claude/launch.json` en la raíz para levantar `npm run dev` desde el panel de vista previa.
- Verificación: `npm run build` sin errores; revisado en el navegador a 1280 px (sidebar expandido y contraído, tema claro y oscuro, tooltip del gráfico, validación del formulario) y a 375 px (menú deslizable, tabla con scroll horizontal, sin scroll horizontal de la página). Consola sin errores.

## Hallazgos / resultado

- **Trampa de Vue**: en `<style scoped>`, `:global(.a) .b` compila a `.a` a secas (se descarta lo que sigue). Eso aplicaba el ancho del sidebar contraído a todo el layout. Las reglas que dependen de clases del padre (`.admin-layout.sidebar-collapsed …`) van en un segundo bloque `<style>` sin `scoped` (así está en `Sidebar.vue`).
- El tema inicial se aplica con un script en `index.html` antes de montar Vue, para evitar el parpadeo claro → oscuro. Si no hay preferencia guardada, sigue la del sistema operativo.
- Claves de `localStorage`: `logy.tema` (`light`/`dark`) y `logy.sidebar-contraido` (`1`/`0`). Todo acceso va en `try/catch`.
- Paleta de gráficos validada (daltonismo y contraste) con el orden índigo, naranja, verde agua, ámbar; los colores con contraste bajo sobre blanco siempre van acompañados de etiqueta y valor en la leyenda.

## Pendientes

- [ ] Cargar el menú desde la API (tablas `modulo`/`menu`) en lugar de `config/menu.js`.
- [ ] Reemplazar `usuarioDemo` por el usuario autenticado cuando exista el login.
- [ ] El buscador del navbar y el atajo "Ctrl K" son solo visuales.
