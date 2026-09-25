# Helper api_helper en lugar de MY_Controller

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `api/application/helpers/api_helper.php`, `api/application/controllers/Auth.php`, `api/application/config/autoload.php`, `api/application/core/MY_Controller.php` (eliminado)

## Objetivo

El usuario prefirió el patrón habitual de CodeIgniter 3: controladores que extienden `CI_Controller` y las funciones comunes en un helper, en vez de controladores base (`MY_Controller` / `Autenticado_Controller`).

## Qué se hizo

- Nuevo `helpers/api_helper.php` con `responder()`, `responder_error()`, `entrada_json()`, `exigir_metodo()` y `exigir_sesion()`, en snake_case como los helpers de CodeIgniter y protegidas con `function_exists`.
- `autoload.php`: `$autoload['helper'] = array('api');`.
- `Auth` extiende `CI_Controller` y usa las funciones del helper. Se eliminó `core/MY_Controller.php`.
- Se actualizaron `api.md`, `conventions.md` y la decisión 0004.

## Hallazgos / resultado

- Pruebas contra `http://logy.local` (Apache, ya con el VirtualHost activo): `auth/login` → 422 / 401 / 405 según el caso; `auth/yo` → 401 sin token, 405 con POST, 401 con token inválido, vencido, otra clave, `alg: none` u otro emisor, y 200 con un token válido (sin `clave` en la respuesta). Por el proxy de Vite (`localhost:5173/api/...`) también responde.
- El VirtualHost `logy.local` quedó funcionando (el usuario agregó la línea al `hosts` y reinició Apache), y el encabezado `Authorization` llega a PHP.

## Pendientes

- (ninguno)
