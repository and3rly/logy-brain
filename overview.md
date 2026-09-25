# Visión general

## Qué es Logy

**Sistema de gestión comercial (ERP/POS) multiempresa para negocios de Guatemala** (deducido de la base de datos el 2026-09-25 y confirmado por el usuario). Varias empresas usan el mismo sistema, cada una con sus sucursales, usuarios, catálogos y documentos.

Módulos, tal como están en la tabla `modulo`/`menu`:

| Módulo | Contenido |
|---|---|
| Catálogos | Moneda, unidad de medida, categorías, marcas, productos, clientes |
| Compra | Órdenes de compra, proveedores |
| Venta | Ventas con facturación electrónica (FEL) |
| Cotización | Cotizaciones que se convierten en venta |
| Inventario | Existencias, kardex, ajustes, inventario inicial y cíclico |
| Finanzas | Cuentas por cobrar y por pagar, con abonos |
| Configuración | Parámetros, usuarios, roles, sucursales, menú |

Contexto local: moneda Quetzal (y Dólar), geografía de Guatemala, IVA e ISR, y facturas certificadas ante SAT (FEL).

Detalle del modelo de datos: `base-de-datos.md`. Términos: `glossary.md`.

## Stack

| Parte | Tecnología | Ubicación |
|---|---|---|
| Backend / API | PHP 7.4 + CodeIgniter 3.1.13 | `api/` |
| Base de datos | MySQL 8.0 remoto, base `db_logy` (driver `mysqli`) | conexión en `api/application/config/database.php` |
| Frontend | Vue 3 + Vite (JavaScript), Vue Router, Pinia | `interfaz/` |
| Servidor local | XAMPP (Apache), en `C:\xampp\htdocs\logy` | |

## Estructura

```
logy/
├── CLAUDE.md      ← punto de entrada para Claude
├── brain/         ← conocimiento del proyecto (repo logy-brain)
├── api/           ← CodeIgniter 3 (el código propio va en api/application/)
│   ├── application/   controllers, models, config, views…
│   └── system/        núcleo del framework, NO se modifica
└── interfaz/      ← frontend Vue 3 + Vite (código en interfaz/src/)
```
