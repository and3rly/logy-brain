# Análisis de la estructura de la base de datos

- **Fecha:** 2026-09-25
- **Tipo:** análisis
- **Archivos / tablas involucrados:** `api/application/config/database.php`, las 46 tablas de `db_logy`

## Objetivo

El usuario agregó la conexión a la BD. Se analizó la estructura para entender de qué trata el proyecto.

## Qué se hizo

- Se leyó `database.php` sin mostrar la contraseña: MySQL 8.0 remoto, base `db_logy`.
- Todas las consultas se hicieron en una sesión `READ ONLY` (regla 3). Se comprobó que el servidor rechaza una escritura.
- Consultas ejecutadas: `SHOW GRANTS`; `information_schema.TABLES`, `COLUMNS`, `KEY_COLUMN_USAGE`, `ROUTINES` y `TRIGGERS`; y `SELECT * … LIMIT 30` solo sobre catálogos sin datos personales (`modulo`, `menu`, `*_estado`, `*_tipo`, `movimiento_tipo`, `unidad_medida`, `moneda`, `forma_pago`, `departamento`). No se leyeron `usuario`, `cliente`, `empresa` ni `proveedor`.
- El script quedó en `brain/herramientas/db_lectura.php` para reutilizarlo.

## Hallazgos / resultado

- **Logy es un ERP/POS multiempresa para negocios de Guatemala**, con catálogos, compras, cotizaciones, ventas con facturación electrónica FEL, inventario (stock por lote de vencimiento, kardex, ajustes, tomas físicas) y finanzas (CxC y CxP). Documentado en `overview.md`, `base-de-datos.md` y `glossary.md`.
- Hay 46 tablas, sin vistas, rutinas ni triggers: toda la lógica va en la API.
- El esquema coincide con `General_model` (PK `id`, tabla con el nombre del modelo, `empresa_id` y `usuario_id`).
- El menú (`modulo`/`menu`) está en la BD, con iconos Font Awesome y URLs de rutas; los estados usan clases de badge de Bootstrap/Tabler. Son pistas para el diseño de la interfaz (`interfaz.md`).
- Hay datos de prueba cargados (pocas filas: 5 ventas, 7 compras, 43 movimientos).
- Se encontraron 10 inconsistencias en el esquema; están listadas en `base-de-datos.md` y no se corrigieron.
- **Seguridad**: el usuario de `database.php` es superadministrador del servidor (`ALL PRIVILEGES` + `GRANT OPTION`, desde cualquier host). No se escribió el host ni el usuario en el brain, porque es público.

## Pendientes

- [x] El usuario confirmó el objetivo del proyecto: ERP/POS multiempresa para Guatemala.
- [x] `producto.tipo_producto`: `B` = Bien, `S` = Servicio (confirmado por el usuario).
- [ ] Usar un usuario MySQL con permisos mínimos para la aplicación y otro de solo lectura para Claude.
- [ ] Decidir si se corrigen las inconsistencias del esquema (lo ejecuta el usuario; Claude solo propone el SQL).
