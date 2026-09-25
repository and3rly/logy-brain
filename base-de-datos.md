# Base de datos

Análisis del 2026-09-25: MySQL 8.0, base `db_logy`, 46 tablas y ninguna vista, rutina ni trigger. Toda la lógica de negocio va en la aplicación. La conexión está en `api/application/config/database.php`. La consulta se hace con `brain/herramientas/db_lectura.php` (solo lectura, ver `architecture.md`).

## Convenciones del esquema

- Cada tabla tiene `id` INT autoincremental como PK, y el nombre de la tabla es igual al del modelo (encaja con `General_model`).
- FKs con el nombre `<tabla>_id`. Casi todas las tablas llevan `empresa_id`: el sistema es **multiempresa** (SaaS). Excepciones globales: `menu`, `modulo`, `venta_estado`, `pais`, `departamento`, `municipio`.
- `usuario_id` guarda quién creó el registro. `activo` (tinyint) sirve para el borrado lógico en catálogos. `fecha` es un timestamp de creación con valor por defecto.
- **Documentos** (venta, compra, cotización, CxC, CxP): nunca se borran, se **anulan** con `anulado`, `anulado_fecha`, `anulado_usuario` y `anulado_motivo`. En inventario los nombres cambian: `usuario_anulo_id`, `fecha_anulado`.
- **Estados** en tablas `<doc>_estado` con `codigo`, `nombre`, `orden` y `etiqueta`. `etiqueta` guarda la clase CSS del badge (ej. `badge bg-warning`).
- **Series y correlativos** en `<doc>_serie` (`codigo`, `inicio`, `fin`, `correlativo`) para numerar documentos.
- Los detalles copian datos del maestro al momento de la operación (`cliente_nombre`, `producto_nombre`, `precio`, `costo`) para conservar el histórico.
- Montos en `decimal(15,5)` en ventas, cotizaciones y cuentas; en compras, inventario y producto hay `decimal(15,2)` y `decimal(10,x)`.
- FKs compuestas con `empresa_id` en `cotizacion`, `venta` e `inventario_*`: garantizan que el cliente, la serie o el estado sean de la misma empresa.

## Módulos y tablas

### Organización y seguridad
- `empresa`: cada empresa cliente del sistema. `empresa_parametro` guarda su configuración (moneda por defecto, prefijos de numeración `abr_*`, decimales de cantidad y de monto).
- `sucursal` (de una empresa) y `usuario_sucursal` (sucursales asignadas a un usuario, con una `principal`).
- `usuario` (`alias` para el login, `clave` con hash, `rol_id`) y `rol`.
- `modulo` y `menu`: el menú de la aplicación está **en la base de datos**, con nombre, icono Font Awesome, `url` de la ruta del frontend y orden. Es global, no depende de la empresa.
- Geografía: `pais` → `departamento` → `municipio`, cargados con los datos de **Guatemala**.

### Catálogos
- `producto`: código, código de barras, precio, costo, existencia mínima, `control_vence` (controla vencimientos), `tipo_producto` (`B` = Bien, `S` = Servicio; confirmado por el usuario), marca, categoría y unidad.
- `producto_presentacion`: presentaciones con un `factor` de conversión (ej. caja = 12 unidades).
- `categoria`, `marca`, `unidad_medida`, `moneda` (Quetzal y Dólar), `forma_pago` (Contado y Crédito).
- `cliente` y `proveedor`: con condiciones de crédito (`credito`, `credito_limite`, `credito_dias`).

### Compras
- `compra` (orden de compra) → `compra_detalle`. Estados: Creada, Recibida, Anulada.
- Al recibirse, la compra genera movimientos de inventario (tipo REC) y, si es a crédito, una `cuenta_pagar` (`origen` 2).

### Cotizaciones
- `cotizacion` → `cotizacion_detalle`, numerada con `cotizacion_serie`. Estados: Borrador, Enviada, Aceptada, Rechazada, Anulada, Convertida.
- Una cotización **Convertida** pasa a venta (`venta.cotizacion_id`).

### Ventas y facturación
- `venta` → `venta_detalle`, numerada con `venta_serie` (`electronico` indica si es una serie de factura electrónica). Estados: Creado, Facturada, Pagada, Anulada.
- **Facturación electrónica (FEL de Guatemala)**: `certificada`, `certificada_fecha`, `factura_uuid`, `factura_serie` y `factura_numero` guardan la certificación del DTE.
- Impuestos por línea y por total: `iva`, `isr`, `base`, `descuento`, `ganancia` (precio − costo) y `tipo_cambio`.

### Inventario
- `stock`: existencia por **sucursal + producto + unidad + presentación + fecha de vencimiento** (maneja lotes por vencimiento).
- `movimiento`: el **kardex**. Cada entrada o salida apunta al `stock` y al documento que la originó (`compra_id`, `venta_detalle_id`, `inventario_ajuste_id`, `inventario_det_id`). Tipos (`movimiento_tipo`): REC recepción, AJP/AJN ajuste positivo o negativo, IVP/IVN inventario positivo o negativo, VTA venta y VAN anulación de venta.
- `inventario_enc` → `inventario_det`: tomas de inventario (INICIAL o CICLICO) con `cantidad_sistema`, `cantidad_fisica` y `diferencia`. Se pueden cargar desde un archivo (`archivo_nombre`, `archivo_hash`). Estados: Borrador, Validado, Procesado, Anulado.
- `inventario_ajuste` → `inventario_ajuste_detalle`: ajustes manuales por tipo, con naturaleza POSITIVO o NEGATIVO (sobrante, recuperación, faltante, merma, dañado, vencido, consumo interno). Estados: Borrador, Aplicado, Anulado.

### Finanzas
- `cuenta_cobrar` → `cuenta_cobrar_pago`: cuentas de clientes (`origen` 1 = manual, 2 = venta), con `total`, `abono`, `saldo` y `fecha_vence`.
- `cuenta_pagar` → `cuenta_pagar_pago`: cuentas con proveedores (`origen` 1 = manual, 2 = orden de compra).

## Inconsistencias detectadas (documentadas, no corregidas; la BD es de solo lectura para Claude)

1. `venta.cliente_id` y `venta.vendedor_id` no tienen FK. El comentario dice "hasta implementar la tabla cliente", pero `cliente` ya existe.
2. `venta_detalle.producto_precio_costo_id` apunta a una tabla que no existe.
3. `cuenta_cobrar.venta_id` no tiene FK ni índice.
4. `venta_estado` no tiene `empresa_id`, a diferencia de los demás estados.
5. `cliente.telefono` es `int`: pierde ceros a la izquierda y no admite formatos. En las otras tablas es `varchar`.
6. `producto.descripcion` es `varchar(45) NOT NULL`, muy corto para una descripción.
7. Nombres de anulación distintos entre módulos (`anulado_usuario` frente a `usuario_anulo_id`) y encabezado/detalle con `_enc`/`_det` frente a `_detalle`.
8. No hay tabla de permisos entre `rol` y `menu`: todavía no existe control de acceso por rol.
9. `modulo`: dos módulos tienen `orden` 5 (Configuración e Inventario).
10. `compra_estado.etiqueta` guarda solo el color (`primary`, `lime`), mientras que los otros estados guardan la clase completa (`badge bg-…`).
