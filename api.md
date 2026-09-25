# API

## Endpoints

| Método | Ruta | Controlador::método | Descripción |
|---|---|---|---|
| GET | `/` | `Welcome::index` | Página de ejemplo de CodeIgniter (se puede eliminar) |
| POST | `auth/login` | `Auth::login` | Recibe `{ alias, clave }`. Devuelve `{ token, expira_en, usuario }`. 422 si faltan datos, 401 si son incorrectos o el usuario, rol o empresa están inactivos |
| GET | `auth/yo` | `Auth::yo` | Requiere token. Devuelve `{ usuario }` y vuelve a comprobar que siga activo |

En desarrollo, la URL completa es `http://logy.local/index.php/<ruta>` (o `/api/<ruta>` desde la interfaz, por el proxy de Vite).

## Formato de respuesta

Todas las respuestas son JSON con el formato `{ "exito": bool, "datos": mixed, "mensaje": string|null }` y el código HTTP que corresponde (200, 401, 405, 422…). Se arman con `responder()` y `responder_error()` del helper `api_helper.php`.

## Autenticación (JWT)

Decisión: `decisions/0004-autenticacion-jwt.md`.

- La interfaz envía `Authorization: Bearer <token>`. El token (HS256, 8 h) lleva `sub` (id del usuario), `empresa_id`, `rol_id`, `alias`, `iss`, `iat`, `nbf` y `exp`.
- `libraries/Token_jwt.php`: genera y valida tokens con firebase/php-jwt 6.10. Configuración en `config/jwt.php` (clave secreta, que nunca va al brain; emisor y duración).
- `libraries/Sesion_token.php`: se carga en `autoload.php` con el nombre **`session`**. Valida el token de la petición y expone `userdata()` (`id`, `empresa_id`, `rol_id`, `alias`), `autenticado()` y `getMensaje()`. Por eso `General_model` completa `usuario_id` y `empresa_id` desde el token.
- `helpers/logy_helper.php` (en `autoload.php`): funciones generales del proyecto: `elemento($arreglo, $clave, $defecto)` y `verPropiedad($objeto, $prop, $defecto)` (devuelven el valor si existe y no está vacío), `Hoy($hora)`, `sumar_tiempo()`, `generarCodigo()`, `eliminarAcento()`, `censurar_mail()`, `array_field()`, `verLetra()`, `getTiposProductos()`, entre otras. Detalle y observaciones en la wiki `2026-09-25-analisis-logy-helper.md`.
- `helpers/api_helper.php` (en `autoload.php`): `responder()`, `responder_error()`, `entrada_json()` (lee el cuerpo JSON que envía axios), `exigir_metodo()` (405) y `exigir_sesion()` (401 sin un token válido). Los controladores extienden `CI_Controller`.
- Dependencias con Composer en `api/` (`vendor` en `application/vendor`). Después de clonar: `composer install` dentro de `api/`.

## Modelos

### Modelos del proyecto

| Modelo | Tabla | Uso |
|---|---|---|
| `Usuario_model` | `usuario` | `autenticar(["alias", "clave"])`, `cargarSesion(["id"])`, `perfil()` (sin la clave), `datosToken()` |
| `Rol_model` | `rol` | Consulta del rol del usuario |
| `Empresa_model` | `empresa` | Consulta de la empresa del usuario |

### `General_model` (modelo base)

`api/application/models/General_model.php`. Es la base de los modelos del proyecto: cada modelo nuevo extiende `General_model` y no `CI_Model`. Solo hay excepciones con autorización explícita del usuario para ese caso (regla 5 de `instrucciones.md`). Implementa un patrón tipo *Active Record*: el objeto del modelo representa una fila de la tabla.

**Cómo funciona**

- **Tabla**: se deduce del nombre de la clase (`Cliente_model` → tabla `cliente`). Se puede cambiar con `setTabla()`.
- **Llave primaria**: por defecto `id`. Se cambia con `setLlave()`.
- **Columnas**: son las **propiedades públicas** del modelo hijo. `guardar()` inserta o actualiza `$this`, y CodeIgniter solo toma las propiedades públicas. Las internas (`_tabla`, `_llave`, `_pk`, `mensaje`, `usr`) son `protected` para que no se guarden.
- **Sesión**: en el constructor lee `$this->session->userdata()` y lo guarda en `$this->usr`.

**Métodos públicos**

| Método | Qué hace |
|---|---|
| `cargar($valor)` | Busca la fila donde la llave es igual a `$valor` y la carga en el objeto (también guarda la PK). |
| `guardar($args = [])` | Asigna `$args` y luego: si no hay PK, hace **INSERT**; si hay PK, hace **UPDATE**. Devuelve `true`/`false`; si falla, deja el mensaje en `getMensaje()`. En el INSERT completa `usuario_id` y `empresa_id` con los datos de la sesión si el modelo tiene esas propiedades y vienen vacías. |
| `buscar($args = [])` | Consulta con filtros (ver abajo). Devuelve `result()`, o `row()` si se pasa `_uno`. |
| `setDatos($args)` | Asigna los valores a las propiedades que existan en el modelo. Si la propiedad es otro modelo, le asigna su PK. |
| `getPK()` / `setPK()` | Leen y asignan la llave primaria cargada. |
| `getMensaje()` / `setMensaje()` | Mensajes de error; `setMensaje()` **concatena** al mensaje anterior. |
| `limpiarGeneral()` | Borra la PK y el mensaje, para reutilizar el objeto. |
| `setTabla()` / `setLlave()` | Cambian la tabla o la llave por defecto. |

**Filtros de `buscar()`**

- `campo => valor` → `WHERE tabla.campo = valor`. Si el valor es un array, usa `WHERE IN`. Si el campo trae un punto (`otra.campo`), se usa tal cual.
- `descripcion` → además agrega `LIKE '%…%'` (el `WHERE =` también se aplica, ver problemas).
- `_limite`, `_inicio` → paginación.
- `_between => [campo, desde, hasta]`
- `_orden_asc => campo`, `_orden_desc => campo`, `_orden_rand`
- `_uno` → devuelve una sola fila.
- Las claves que empiezan con `_` son opciones y no se convierten en filtros.

**Requisitos para que funcione**: las librerías `database` y `session` deben estar cargadas. Desde el 2026-09-25, `autoload.php` carga `database` y, como `session`, la librería `Sesion_token` (sesión sin estado desde el JWT; ver arriba). No se usa la sesión de PHP de CodeIgniter.

**Decisión del usuario (2026-09-25): `General_model` no se modifica.** Los problemas de abajo quedan documentados pero **no se corrigen**, y el archivo no se toca salvo que el usuario lo pida explícitamente. Mientras tanto, el código nuevo debe tenerlos en cuenta: no usar `_inicio` para paginar, no pasar a `_between` valores que vengan del usuario, y verificar que la fila exista antes de llamar a `cargar()`.

**Problemas detectados** (2026-09-25; ver wiki `2026-09-25-analisis-general-model.md`):

1. `buscar()`: `$inicio = isset($args["_inicio"]) ?? 0;` guarda `true`/`false` y no el valor, así que la paginación con `_inicio` no funciona.
2. `buscar()` con `descripcion`: agrega el `LIKE` pero no quita la clave, así que también se aplica `WHERE descripcion = …` y el `LIKE` pierde sentido.
3. `buscar()` con `_between`: mete los valores directamente en el SQL, sin escaparlos. Hay riesgo de **inyección SQL** si vienen del usuario.
4. `cargar()`: si no encuentra la fila, falla al leer una propiedad de `null`.
5. `getDatos()` es privado y nadie lo usa; llama a `getForanea()`, que no existe. `foreignKey` tampoco se usa. `setCodigo()` asigna `_codigo`, que no está declarado.
6. `setTabla("a.b")` asigna como llave la parte `b`. El comportamiento no es obvio: **Por definir** si es intencional.

## Base de datos

MySQL 8.0, base `db_logy`, 46 tablas. La estructura completa por módulo, las convenciones y las inconsistencias están en **`base-de-datos.md`**.

El esquema coincide con lo que espera `General_model`: PK `id` en todas las tablas, nombre de tabla igual al del modelo, y `usuario_id`/`empresa_id` en casi todas.
