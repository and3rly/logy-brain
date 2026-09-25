# Instalación del brain y análisis de General_model

- **Fecha:** 2026-09-25
- **Tipo:** análisis
- **Archivos / tablas involucrados:** `api/application/models/General_model.php`, `api/application/config/autoload.php`, `api/application/config/config.php`

## Objetivo

Clonar logy-brain, instalarlo en el proyecto y verificar que lo que dice coincide con el código actual.

## Qué se hizo

- Se clonó `https://github.com/and3rly/logy-brain.git` en `logy/brain` (commit `a1ea0b2`) y se ejecutó `brain/instalar.ps1`. Creó `CLAUDE.md` y `.claude/settings.json` en la raíz.
- Al comparar el brain con el código apareció un archivo nuevo sin documentar: `api/application/models/General_model.php`.
- Se revisaron `autoload.php`, `config.php` (sesión, base_url), `database.php` (solo estructura, sin credenciales) y `routes.php`.
- No se modificó código: esto es solo análisis (regla 4).

## Hallazgos / resultado

- `General_model` es un modelo base tipo *Active Record* y define la convención de todos los modelos: tabla deducida del nombre de la clase, propiedades públicas como columnas, `guardar()`/`buscar()`/`cargar()` y mensajes con `setMensaje()`. Quedó documentado en `api.md` y `conventions.md`.
- El código está en español, así que se fijó el español como idioma del código.
- El modelo usa `usuario_id` y `empresa_id` tomados de la sesión: el sistema apunta a ser multiusuario y multiempresa.
- **Bloqueante**: `General_model` usa `$this->db` y `$this->session`, pero `autoload.php` no carga ninguna librería y `sess_save_path` es `NULL`. Cualquier modelo que lo extienda fallará hasta que se configure.
- Errores y riesgos en el código (detalle en `api.md`):
  1. Paginación rota en `buscar()`: `isset(...) ?? 0` devuelve un booleano.
  2. `descripcion` aplica `LIKE` y además `WHERE =`.
  3. `_between` concatena valores en el SQL, con riesgo de inyección.
  4. `cargar()` falla si no encuentra la fila.
  5. Código muerto: `getDatos()`, `getForanea()` (que no existe), `foreignKey`, `_codigo`.
- La base de datos todavía no está configurada (nombre y usuario vacíos).

## Pendientes

- [x] ¿Se corrigen los errores de `General_model`? **No**: el usuario decidió no corregir nada. El archivo queda como está y el código nuevo rodea sus limitaciones (ver `api.md`).
- [ ] Configurar el autoload de `database` y `session`, y `sess_save_path` (no se hizo; sigue pendiente de una orden del usuario).
- [ ] Aclarar si el comportamiento de `setTabla("a.b")` es intencional.
