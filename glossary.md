# Glosario

Términos del dominio de Logy y lo que significan aquí.

| Término | Significado |
|---|---|
| Empresa | Cliente del sistema (multiempresa). Casi todos los datos se filtran por `empresa_id`. |
| Sucursal | Punto de venta o bodega de una empresa. Las existencias se llevan por sucursal. |
| Presentación | Forma de empaque de un producto con un `factor` de conversión a la unidad base (ej. caja × 12). |
| Bien / Servicio | Tipo de producto (`producto.tipo_producto`): `B` = Bien (físico), `S` = Servicio. |
| Stock / existencia | Cantidad disponible por sucursal, producto, presentación y fecha de vencimiento. |
| Kardex | Historial de movimientos de inventario de un producto (tabla `movimiento`). |
| Ajuste | Corrección manual del inventario, positiva o negativa (merma, dañado, sobrante…). |
| Inventario inicial / cíclico | Toma física de inventario: carga inicial o conteo periódico que genera diferencias. |
| Cotización | Oferta a un cliente. Si se acepta, se **convierte** en venta. |
| Orden de compra | Compra a un proveedor. Al **recibirla** ingresa al inventario. |
| Serie / correlativo | Numeración de documentos: una serie con rango `inicio`–`fin` y el último `correlativo` usado. |
| Anular | Invalidar un documento sin borrarlo; queda registrado quién, cuándo y por qué. |
| FEL | Factura Electrónica en Línea de la SAT de Guatemala. Una venta **certificada** tiene `factura_uuid`, serie y número del DTE. |
| DTE | Documento Tributario Electrónico (la factura certificada). |
| IVA / ISR | Impuesto al Valor Agregado / Impuesto Sobre la Renta, que se calculan en ventas y cotizaciones. |
| CxC / CxP | Cuentas por cobrar (a clientes) / cuentas por pagar (a proveedores), con pagos o abonos. |
| Etiqueta | Clase CSS del badge con el que se muestra un estado (ej. `badge bg-warning`). |
| Quetzal (Q, QTZ) | Moneda de Guatemala; el sistema también maneja Dólar (USD) con `tipo_cambio`. |
