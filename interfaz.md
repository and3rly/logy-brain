# Interfaz

Tecnología: **Vue 3 + Vite**, en **JavaScript** (sin TypeScript), con **Vue Router** y **Pinia**. Creado con `create-vue` (plantilla `--bare`). Ver `decisions/0002-vue-vite-para-la-interfaz.md`.

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

## Estructura

```
interfaz/
├── index.html          punto de entrada HTML
├── vite.config.js      plugins (vue, devtools) y alias @ → src/
├── jsconfig.json       alias @ para el editor
├── public/             archivos estáticos (favicon)
└── src/
    ├── main.js         crea la app y registra Pinia y el router
    ├── App.vue         componente raíz (todavía es el de ejemplo)
    ├── router/index.js rutas (vacío; historial HTML5)
    └── stores/         stores de Pinia (counter.js es de ejemplo)
```

- Imports con alias: `@/` apunta a `src/` (ej. `import x from '@/stores/…'`).
- `App.vue` y `stores/counter.js` son de la plantilla; se reemplazan al construir la primera pantalla.

## Pantallas

(ninguna todavía; por cada pantalla: ruta, propósito y endpoints de la API que consume)

## Componentes compartidos

(ninguno todavía)

## Diseño visual

**Por definir**: plantilla o librería de UI, CSS, colores y tipografía. Lo que se elija con la primera pantalla fija la identidad visual (ver `desarrollo.md`).

Pistas que ya están en la base de datos (hay que respetarlas o migrarlas de forma consciente):
- **Iconos: Font Awesome 6**. `modulo.icono` y `menu.icono` guardan clases como `fa fa-list`, `fas fa-coins` y `fa-solid fa-wallet`.
- **Badges de estados** con clases de Bootstrap: `badge bg-warning`, `bg-danger`, `bg-primary`… Algunos colores (`bg-green`, `lime`) son de **Tabler**, una plantilla de administración basada en Bootstrap. Esto sugiere que el diseño esperado es Bootstrap o Tabler.
- **El menú es dinámico**: módulos e ítems salen de las tablas `modulo`/`menu`, y su `url` debe coincidir con las rutas de Vue Router (`/producto`, `/cuenta-cobrar`, `/kardex`…).

## Estado y comunicación con la API

- Estado global: Pinia (`src/stores/`).
- Cliente HTTP: **Por definir** (`fetch` nativo o una librería como axios).
- Cómo llegar a la API en desarrollo: **Por definir**. Ver `architecture.md`.
