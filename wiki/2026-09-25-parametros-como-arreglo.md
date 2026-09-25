# Convención: parámetros como arreglo en los modelos

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `api/application/models/Usuario_model.php`, `api/application/controllers/Auth.php`, `brain/conventions.md`, `brain/api.md`

## Objetivo

El usuario pidió que las funciones reciban un arreglo de parámetros en vez de parámetros sueltos, porque otras funciones tendrán varios.

## Qué se hizo

- `Usuario_model::autenticar($alias, $clave)` → `autenticar($args = [])` con las claves `alias` y `clave`. Ahora también valida dentro del modelo que no vengan vacías.
- `Usuario_model::cargarSesion($id)` → `cargarSesion($args = [])` con la clave `id`.
- `Auth` actualizado: `autenticar(['alias' => $alias, 'clave' => $clave])` y `cargarSesion(['id' => ...])`.
- Nueva convención en `conventions.md` (sigue el estilo de `guardar($args)` y `buscar($args)` de `General_model`).

## Hallazgos / resultado

- Alcance: métodos públicos de los **modelos**. Los métodos sin parámetros (`perfil()`, `datosToken()`), los internos que reciben una fila (`validarAcceso($fila)`), las funciones del helper (`responder($datos, $mensaje, $estado)`) y las librerías (`Token_jwt::validar($token)`) quedan como están.
- Pruebas contra `logy.local`: login vacío → 422, alias inexistente → 401; `auth/yo` con token válido → 200 y vencido → 401.

## Pendientes

- (ninguno)
