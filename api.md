# API

## Endpoints

| Método | Ruta | Controlador::método | Descripción |
|---|---|---|---|
| GET | `/` | `Welcome::index` | Página de ejemplo de CodeIgniter (se puede eliminar) |

## Modelos

### `General_model` (modelo base)

`api/application/models/General_model.php`. Es la base de **todos** los modelos del proyecto: cada modelo nuevo extiende `General_model` y no `CI_Model` (ver `conventions.md`). Implementa un patrón tipo *Active Record*: el objeto del modelo representa una fila de la tabla.

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

**Requisitos para que funcione**: las librerías `database` y `session` deben estar cargadas. Hoy **no lo están** (`autoload.php` está vacío) y la sesión no tiene `sess_save_path` configurado.

**Problemas detectados** (2026-09-25; ver wiki `2026-09-25-analisis-general-model.md`):

1. `buscar()`: `$inicio = isset($args["_inicio"]) ?? 0;` guarda `true`/`false` y no el valor, así que la paginación con `_inicio` no funciona.
2. `buscar()` con `descripcion`: agrega el `LIKE` pero no quita la clave, así que también se aplica `WHERE descripcion = …` y el `LIKE` pierde sentido.
3. `buscar()` con `_between`: mete los valores directamente en el SQL, sin escaparlos. Hay riesgo de **inyección SQL** si vienen del usuario.
4. `cargar()`: si no encuentra la fila, falla al leer una propiedad de `null`.
5. `getDatos()` es privado y nadie lo usa; llama a `getForanea()`, que no existe. `foreignKey` tampoco se usa. `setCodigo()` asigna `_codigo`, que no está declarado.
6. `setTabla("a.b")` asigna como llave la parte `b`. El comportamiento no es obvio: **Por definir** si es intencional.

## Base de datos

Nombre: **Por definir**

Convenciones que el modelo base da por hechas: llave primaria `id`; tabla con el mismo nombre que el modelo (sin `_model`, en minúsculas); columnas opcionales `usuario_id` y `empresa_id`, que se completan desde la sesión (sugiere un sistema multiempresa).

### Tablas

(ninguna todavía; por cada tabla: propósito, columnas clave y relaciones)
