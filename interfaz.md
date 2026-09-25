# Interfaz

Tecnología: **Vue 3 + Vite**, en **JavaScript** (sin TypeScript), con **Vue Router** y **Pinia**. Creado con `create-vue` (plantilla `--bare`). Ver `decisions/0002-vue-vite-para-la-interfaz.md`. Plantilla visual: Bootstrap 5 + Font Awesome + componentes propios (`decisions/0003-plantilla-visual-bootstrap.md`).

## Requisitos y comandos

- Node.js `^22.18.0 || >=24.12.0` (instalado: v24.21.0, npm 11.19.0).
- Desde `interfaz/`:

| Comando | Qué hace |
|---|---|
| `npm install` | Instala dependencias (después de clonar o si cambia `package.json`) |
| `npm run dev` | Servidor de desarrollo de Vite (por defecto `http://localhost:5173`) |
| `npm run build` | Genera la versión de producción en `interfaz/dist/` (ignorada por git) |
| `npm run preview` | Sirve localmente lo generado en `dist/` |

## Versiones principales

| Paquete | Versión |
|---|---|
| vue | ^3.5.42 |
| vue-router | ^5.3.1 |
| pinia | ^4.0.3 |
| vite | ^8.2.2 |
| @vitejs/plugin-vue | ^6.0.8 |
| vite-plugin-vue-devtools | ^8.2.1 (solo desarrollo) |
| bootstrap | ^5.3.8 (solo CSS) |
| @fortawesome/fontawesome-free | ^7.3.1 |
| @fontsource-variable/inter | ^5.3.0 |

## Estructura

```
interfaz/
├── index.html              entrada HTML; aplica el tema guardado antes de montar Vue
├── vite.config.js          plugins (vue, devtools) y alias @ → src/
└── src/
    ├── main.js             registra Pinia y el router; importa Bootstrap, Font Awesome, Inter y el CSS propio
    ├── App.vue             solo <router-view />; inicializa el store `plantilla`
    ├── assets/css/
    │   ├── variables.css   tokens de diseño (colores, sombras, radios, medidas) y tema oscuro
    │   └── main.css        base, ajustes a Bootstrap, layout y utilidades (.icon-box, .tone-*, .avatar)
    ├── config/
    │   ├── app.js          nombre del sistema y usuario de demostración
    │   └── menu.js         menú lateral (secciones → ítems → submenús)
    ├── data/demo.js        datos ficticios de los ejemplos
    ├── stores/plantilla.js tema, sidebar contraído, menú móvil (persistencia en localStorage)
    ├── layouts/AdminLayout.vue   sidebar + navbar + <router-view> + pie
    ├── components/
    │   ├── layout/         Sidebar, Navbar, Breadcrumb
    │   ├── ui/             componentes base reutilizables (ver abajo)
    │   └── charts/         LineChart, DonutChart (SVG propio)
    ├── views/              pantallas
    └── router/index.js     rutas hijas de AdminLayout; meta.title y meta.breadcrumb
```

- Imports con alias: `@/` apunta a `src/`.

## Pantallas

Todas son de demostración (datos de `data/demo.js`, sin API).

| Ruta | Vista | Propósito |
|---|---|---|
| `/` | `Dashboard.vue` | 4 KPI, gráfico de líneas, dona, actividad reciente, tareas |
| `/usuarios` | `TableExample.vue` | Tabla con buscador, filtro, paginación, badges, acciones y modales |
| `/usuarios/nuevo` | `FormExample.vue` | Formulario con input, select, textarea, fecha, checkbox y switch |
| resto del menú | `EnConstruccion.vue` | Marcador para opciones del menú sin pantalla |
| cualquier otra | `NotFound.vue` | Error 404 dentro del layout |

## Componentes compartidos

En `components/ui/`. Props en inglés (vocabulario de Bootstrap: `variant`, `size`…), lógica interna en español.

| Componente | Uso |
|---|---|
| `BaseCard` | Tarjeta. Props `title`, `subtitle`, `icon`, `noPadding`, `hover`. Slots `header`, `actions`, `footer` |
| `BaseButton` | Botón. `variant` (primary, light, ghost, soft-primary, danger…), `size`, `icon`, `iconRight`, `iconOnly`, `loading`, `to` (enlace del router) |
| `BaseInput` | Input con `label`, `icon`, `help`, `error`, `required`; `type="textarea"` dibuja textarea. `v-model` |
| `BaseSelect` | Select; `options` acepta textos o `{ value, label }`. `v-model` |
| `BaseCheckbox` | Checkbox, o switch con la prop `switch`. `v-model` booleano |
| `BaseBadge` | Estado. `variant`, `solid`, `dot`, `icon` |
| `BaseModal` | Modal propio (Teleport). `v-model`, `title`, `size`, `persistent`; slot `footer` recibe `cerrar` |
| `BaseTable` | Tabla con scroll horizontal. `columns` `{ key, label, align, width }`, `rows`; slot `cell-<key>` |
| `BasePagination` | Paginación. `v-model` (página), `total`, `perPage` |
| `BaseDropdown` | Desplegable. Slot `trigger` y default (recibe `cerrar`); clase `.dropdown-link` para los ítems |
| `PageHeader` | Título y subtítulo de página; slot `actions` |

## Diseño visual

Decisión: `decisions/0003-plantilla-visual-bootstrap.md`.

- Bootstrap 5.3 **solo CSS**; los componentes interactivos son Vue propios (no se usan atributos `data-bs-*`).
- Colores: **siempre** con las variables de `variables.css` (`--primary-color`, `--surface-bg`, `--text-muted`…), nunca hex sueltos en los componentes. Tonos suaves con `.tone-<variante>`.
- Tema claro/oscuro: atributo `data-theme` (y `data-bs-theme`) en `<html>`; botón en el navbar; se guarda en `localStorage` (`logy.tema`).
- Sidebar: 264 px; contraído 80 px (se expande al pasar el mouse); por debajo de 992 px es un menú deslizable con fondo oscuro. Estado en el store `plantilla`.
- Tipografía Inter (local). Iconos Font Awesome 7 (`fa-solid`, `fa-regular`).
- Gráficos: paleta `--chart-1…4` en ese orden (validada para daltonismo); no reutilizar los colores de estado como series.
- Reglas CSS que dependen de clases de un componente padre van en un `<style>` sin `scoped` (ver la trampa de `:global` en la wiki del 2026-09-25).

Pistas de la base de datos:
- `modulo.icono` y `menu.icono` guardan clases de Font Awesome; `config/menu.js` usa el mismo formato para poder cargarse después desde la API.
- Algunos badges de la BD usan colores de Tabler (`bg-green`, `lime`), que Bootstrap no trae: habrá que mapearlos a `BaseBadge`.
- El menú real es dinámico (`modulo`/`menu`), y su `url` debe coincidir con las rutas de Vue Router.

## Estado y comunicación con la API

- Estado global: Pinia (`src/stores/`).
- Cliente HTTP: **Por definir** (`fetch` nativo o una librería como axios).
- Cómo llegar a la API en desarrollo: **Por definir**. Ver `architecture.md`.
