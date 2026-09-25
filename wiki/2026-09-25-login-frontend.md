# Pantalla de login (solo frontend)

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `interfaz/src/views/Login.vue`, `router/index.js`, `components/ui/BaseInput.vue`, `components/layout/Navbar.vue`

## Objetivo

Crear una pantalla de inicio de sesión solo visual, sin autenticación real ni API.

## Qué se hizo

- Análisis: se reutilizaron `BaseInput`, `BaseCheckbox`, `BaseButton`, el store `plantilla` (tema) y `config/app.js` (nombre del sistema). No hacía falta un layout nuevo: el login es una ruta fuera de `AdminLayout`.
- `views/Login.vue`: dos columnas. A la izquierda el formulario (usuario o correo, contraseña con botón para mostrarla, "Recordarme", "¿Olvidaste tu contraseña?", botón Ingresar con estado de carga, cambio de tema). A la derecha un panel decorativo con degradado, tarjeta de ejemplo y beneficios. Por debajo de 992 px el panel se oculta.
- Validación solo visual (campos vacíos); los errores se borran al escribir. Con datos cualquiera, tras 0.8 s navega a `/`.
- Ruta `/login` agregada en `router/index.js` como ruta de primer nivel (sin sidebar ni navbar).
- "Cerrar sesión" del menú de usuario del navbar ahora lleva a `/login`.
- `BaseInput` adaptado (sin romper usos existentes): slot `append` para un elemento al final del campo y prop `autocomplete`. Con `append` se quita el ícono de error de Bootstrap, que ocupaba el mismo lugar.
- Verificación: `npm run build` sin errores; en el navegador, a 1280 px y 375 px: validación, mostrar contraseña, ingreso al dashboard y "Cerrar sesión" → `/login`.

## Hallazgos / resultado

- **Rediseño del panel derecho (mismo día, a pedido del usuario: "más sencillo pero elegante")**: se quitaron la tarjeta de ejemplo con barras, los brillos difuminados, la etiqueta y la lista de beneficios. Ahora es un panel índigo oscuro (`#312e81` → `#1e1b4b`) con una cuadrícula muy tenue que se desvanece hacia abajo (`::before` con `mask-image`), el logo arriba y abajo una frase, un subtítulo, una línea corta y el nombre del sistema. Columnas 50/50.
- **Segundo rediseño (a pedido del usuario)**: se quitó el panel lateral. El login es ahora un card centrado horizontal y verticalmente (máx. 420 px), con el logo, el título "Bienvenido a Logy" y el subtítulo dentro del card, el botón de tema en la esquina superior derecha y el pie debajo del card. Probado a 1280 px y 375 px (sin scroll horizontal).
- No había guardia de rutas mientras no existía autenticación. *(Resuelto: ver `2026-09-25-login-jwt.md`.)*

## Pendientes

- [x] Conectar con la API y agregar la guardia de rutas (ver `2026-09-25-login-jwt.md`).
- [ ] "¿Olvidaste tu contraseña?" no tiene pantalla.
