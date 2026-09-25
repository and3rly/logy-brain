# Repositorio git de la aplicación (logy-app)

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `logy/.gitignore`, `logy/README.md`, `api/.gitignore`, `api/application/config/database.example.php`, `api/application/config/jwt.example.php`

## Objetivo

El usuario creó el repositorio https://github.com/and3rly/logy-app.git para el código: todo `logy/` menos lo relacionado con el brain.

## Qué se hizo

- `git init -b main` en `logy/` y remoto `origin` → logy-app (el remoto estaba vacío). **Sin commit**: se espera la orden del usuario (regla 2).
- `.gitignore` de la raíz: excluye `brain/` (repo aparte), `CLAUDE.md` y `.claude/` (los instala `brain/instalar.ps1`), `api/application/config/database.php` y `jwt.php` (secretos) y `api/application/vendor/` (Composer).
- Plantillas sin credenciales: `database.example.php` (hostname, username, password y database vacíos) y `jwt.example.php` (clave vacía, con el comando para generarla). Se generaron con `sed` sin mostrar los valores reales.
- `api/.gitignore` (el de CodeIgniter) ignoraba `composer.lock`; se quitó esa línea para que el lock se versione y todos instalen la misma versión de php-jwt.
- **Un solo `.gitignore`** (a pedido del usuario): se unieron en el de la raíz las reglas de `api/.gitignore` y `interfaz/.gitignore`, con rutas desde la raíz, y se borraron esos dos. Se dejaron fuera las reglas que solo servían para desarrollar CodeIgniter (`user_guide_src`, `tests`, archivos de Sublime/NetBeans). Ojo: una regla con `/` en medio (`.vscode/*`) solo aplica desde la raíz; para subcarpetas se usa `**/.vscode/*`.
- `README.md` en la raíz con los pasos de instalación (Composer, plantillas de configuración, VirtualHost `logy.local`, `npm install` / `npm run dev`).

## Hallazgos / resultado

- `git check-ignore` confirma que quedan fuera `brain`, `CLAUDE.md`, `.claude`, `database.php`, `jwt.php` y `vendor`. `node_modules` y `dist` los excluye `interfaz/.gitignore`.
- Entrarían 309 archivos (206 de `api/system`, el núcleo de CodeIgniter).
- Se buscó la clave JWT y la contraseña de la BD en todos los archivos que se subirían: sin coincidencias.

## Pendientes

- [x] Primer commit y push de logy-app.
