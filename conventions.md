# Convenciones

## Generales

- Idioma de la documentación: español.
- Idioma del código (variables, funciones, tablas): **español**, siguiendo a `General_model`.
- Indentación: la de `api/.editorconfig` (tabs en PHP, como en CodeIgniter).

## API (CodeIgniter 3)

- No se modifica nada en `api/system/`: es el núcleo del framework.
- Controladores en `api/application/controllers/`. Archivo y clase en PascalCase (`Usuarios.php` → `class Usuarios extends CI_Controller`).
- Modelos en `api/application/models/` con sufijo `_model` (`Cliente_model.php`), y **siempre extienden `General_model`**. La tabla se deduce del nombre (`Cliente_model` → `cliente`), las columnas son las propiedades `public` y lo interno es `protected`. Para guardar se usa `guardar()` y para consultar `buscar()`/`cargar()`, antes de escribir consultas propias. Detalle en `api.md`. **`General_model.php` no se modifica** sin una orden explícita del usuario; sus limitaciones se rodean desde el código nuevo.
- Idioma del código: los nombres del modelo base están en español (`guardar`, `buscar`, `tabla`, `llave`, `mensaje`). Se sigue en español.
- Acceso a la BD solo desde modelos, con Query Builder (`$this->db->...`). Nada de SQL concatenado con datos del usuario.
- Errores de modelo: el método devuelve `false` y deja el texto para el usuario en `setMensaje()`/`getMensaje()`.
- Respuestas en JSON: **Por definir** el formato estándar (ej. `{ "ok": true, "data": ..., "error": null }`).
- Rutas propias en `api/application/config/routes.php`.

## Interfaz (Vue 3 + Vite)

- JavaScript, no TypeScript.
- Imports internos con el alias `@/` (→ `src/`), no con rutas relativas largas.
- Rutas en `src/router/index.js`; estado global en stores de Pinia dentro de `src/stores/`.
- Estilo de componentes, organización de vistas y componentes, y librería de UI: **Por definir**. Lo fija la primera pantalla que se construya.
