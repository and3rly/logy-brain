# Análisis del helper logy_helper

- **Fecha:** 2026-09-25
- **Tipo:** análisis
- **Archivos / tablas involucrados:** `api/application/helpers/logy_helper.php` (creado por el usuario), `api/application/config/autoload.php`, `api/application/controllers/Auth.php`, `api/application/models/Usuario_model.php`

## Objetivo

El usuario agregó `logy_helper.php`, que debe estar disponible siempre. Pidió analizarlo, cargarlo como corresponde en CodeIgniter y aplicarlo en el código ya hecho.

## Qué se hizo

- **Carga**: los helpers que se usan siempre van en `config/autoload.php`, en `$autoload['helper']`, con el nombre sin el sufijo `_helper`. Quedó `array('api', 'logy')`. CodeIgniter carga los helpers antes que las librerías y los modelos, así que están disponibles en todo el código.
- **Correcciones** (sin cambiar lo que hace cada función):
  - Se agregó `defined('BASEPATH') OR exit(...)`, como en el resto de los archivos de CodeIgniter.
  - Se quitó el `?>` final: cualquier espacio o salto de línea después de él se envía antes de las cabeceras y puede romper las respuestas JSON.
  - `function_exists('eliminaAcento')` protegía a `eliminarAcento` (nombre distinto): la protección no servía y, si la función existiera en otro lado, daría un error fatal por redeclaración. Igual con `getTipoProducto` / `getTiposProductos`.
  - `generarCodigo()`: `rand()` → `random_int()`. `rand()` no es seguro para códigos que deban ser impredecibles (recuperación de contraseña, invitaciones).
- **Aplicado en el código existente**: `elemento()` reemplaza a `$arreglo['x'] ?? ''` en `Auth::login()` y en `Usuario_model::autenticar()` / `cargarSesion()`. Las librerías (`Sesion_token`, `Token_jwt`) siguen con `??`, porque ahí un `0` debe distinguirse de "no existe".
- Pruebas contra `logy.local`: login vacío 422, alias inexistente 401, `auth/yo` sin token 401, token válido 200, vencido / otra clave / `alg: none` / otro emisor 401. Acceso directo por web al helper: 403.

## Hallazgos / resultado

Observaciones que **no** se cambiaron (quedan a decisión del usuario):

1. `elemento()` y `verPropiedad()` usan `!empty()`: `0`, `"0"`, `""` y `false` cuentan como ausentes y se devuelve el valor por defecto. Es cómodo para textos, pero con cantidades o banderas (`activo = 0`) da un resultado distinto al esperado. Además `elemento()` falla si `$dato` no es un arreglo.
2. `get_tiempo_token()` devuelve 2 horas (`time() + 7200`), pero el JWT del proyecto dura 8 horas (`config/jwt.php`). Hoy no se usa; si se usara, habría dos duraciones distintas.
3. `outputJson()` hace `echo` directo, fuera de la clase `Output` de CodeIgniter y sin el formato `{ exito, datos, mensaje }` ni código HTTP. Para la API se usa `responder()` de `api_helper`.
4. `var_session($data = [])`: el valor por defecto es un arreglo, pero la función lee `$data->id` como objeto (error si se llama sin argumento). Sus claves (`id`, `nombre`, `alias`, `empresa_id`) no coinciden con las del token (`sub`, `empresa_id`, `rol_id`, `alias`).
5. `verConsulta()` usa la opción `uno`, mientras que `General_model::buscar()` usa `_uno`.
6. `getRealIP()` confía en `HTTP_CLIENT_IP` y `X-Forwarded-For`, que el cliente puede falsificar, y `X-Forwarded-For` puede traer varias IPs separadas por coma. Sirve para registrar, no para decisiones de seguridad.
7. `script_tag()` genera `<script>` para vistas PHP; la interfaz es una SPA, así que en la API no tiene uso. La variante con `$print = true` hace una petición HTTP a la propia aplicación (`file_get_contents(base_url(...))`) y necesita `url_helper`.
8. `array_field()` equivale a `array_column($data, $field)` (PHP 7 ya acepta objetos).
9. Mezcla de estilos de nombre: camelCase (`verPropiedad`, `generarCodigo`) y snake_case (`var_session`, `get_tiempo_token`, `sumar_tiempo`).

## Pendientes

- [ ] Decidir sobre las observaciones 1 a 9 (en especial `elemento()` con valores `0` y la duración del token).
