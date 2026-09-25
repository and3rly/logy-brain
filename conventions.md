# Convenciones

## Generales

- Idioma de la documentación: español.
- Idioma del código (variables, funciones, tablas): **español**, siguiendo a `General_model`.
- Indentación: la de `api/.editorconfig` (tabs en PHP, como en CodeIgniter).

## API (CodeIgniter 3)

- No se modifica nada en `api/system/`: es el núcleo del framework.
- Controladores en `api/application/controllers/`. Archivo y clase en PascalCase (`Usuarios.php` → `class Usuarios extends CI_Controller`).
- Modelos en `api/application/models/` con sufijo `_model` (`Cliente_model.php`), y **extienden `General_model`** (regla 5 de `instrucciones.md`: no extenderlo requiere autorización del usuario para ese caso). La tabla se deduce del nombre (`Cliente_model` → `cliente`), las columnas son las propiedades `public` y lo interno es `protected`. Para guardar se usa `guardar()` y para consultar `buscar()`/`cargar()`, antes de escribir consultas propias. Detalle en `api.md`. **`General_model.php` no se modifica** sin una orden explícita del usuario; sus limitaciones se rodean desde el código nuevo.
- Idioma del código: los nombres del modelo base están en español (`guardar`, `buscar`, `tabla`, `llave`, `mensaje`). Se sigue en español.
- Acceso a la BD solo desde modelos, con Query Builder (`$this->db->...`). Nada de SQL concatenado con datos del usuario.
- Errores de modelo: el método devuelve `false` y deja el texto para el usuario en `setMensaje()`/`getMensaje()`.
- Controladores de la API: extienden `CI_Controller`. Lo común está en el helper `api_helper.php` (se carga en `autoload.php`); no se usan controladores base (`MY_Controller`). Una acción que requiere sesión empieza con `if (!exigir_sesion()) return;`, y una que exige un método HTTP, con `if (!exigir_metodo("post")) return;`.
- Respuestas en JSON con el formato `{ "exito": bool, "datos": mixed, "mensaje": string|null }` y el código HTTP que corresponde (200, 401, 405, 422…). Se usan `responder()` y `responder_error()` del helper, nunca `echo json_encode` a mano.
- El usuario y la empresa de la petición salen del token: `$this->session->userdata("id")` y `userdata("empresa_id")`. No se usa la sesión de PHP.
- Rutas propias en `api/application/config/routes.php`.

## Interfaz (Vue 3 + Vite)

- JavaScript, no TypeScript.
- Imports internos con el alias `@/` (→ `src/`), no con rutas relativas largas.
- Rutas en `src/router/index.js`; estado global en stores de Pinia dentro de `src/stores/`.
- Llamadas a la API **siempre con axios**, a través de la instancia de `src/services/api.js` (agrega el token y maneja el 401). Los errores se muestran con `mensajeError(error)`. No se usa `fetch` directo.
- Componentes con `<script setup>` (Composition API) y `defineModel` para `v-model`.
- UI: Bootstrap 5 (solo CSS) + Font Awesome + componentes propios. Antes de escribir marcado a mano, usar los de `components/ui/` (`BaseCard`, `BaseButton`, `BaseInput`, `BaseTable`…). Detalle en `interfaz.md`.
- Organización: `layouts/` (estructura de página), `components/layout/` (sidebar, navbar), `components/ui/` (componentes base con prefijo `Base`), `views/` (pantallas, una por ruta).
- Props de los componentes base en inglés (vocabulario de Bootstrap: `variant`, `size`, `label`); variables, funciones, stores y datos en español.
- Colores solo con las variables CSS de `assets/css/variables.css`, definidas para los dos temas. Estilos de componente en `<style scoped>`.
- Cada ruta lleva `meta.title` y `meta.breadcrumb`.
