# Convenciones

## Generales

- Idioma de la documentación: español.
- Idioma del código (variables, funciones, tablas): **Por definir** (español o inglés; elegir uno y mantenerlo).
- Indentación: la de `api/.editorconfig` (tabs en PHP, como en CodeIgniter).

## API (CodeIgniter 3)

- No se modifica nada en `api/system/`: es el núcleo del framework.
- Controladores en `api/application/controllers/`. Archivo y clase en PascalCase (`Usuarios.php` → `class Usuarios extends CI_Controller`).
- Modelos en `api/application/models/` con sufijo `_model` (`Usuario_model.php`).
- Acceso a la BD solo desde modelos, con Query Builder (`$this->db->...`). Nada de SQL concatenado con datos del usuario.
- Respuestas en JSON: **Por definir** el formato estándar (ej. `{ "ok": true, "data": ..., "error": null }`).
- Rutas propias en `api/application/config/routes.php`.

## Interfaz

- **Por definir** cuando se elija la tecnología.
