# VirtualHost logy.local para la API

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `C:\xampp\apache\conf\extra\httpd-vhosts.conf`, archivo `hosts` de Windows, `interfaz/vite.config.js`, `interfaz/package.json`, `.claude/launch.json`

## Objetivo

Servir la API con el Apache de XAMPP en lugar de levantar a mano el servidor de PHP (`npm run api`). El usuario pidió un VirtualHost `logy.local`.

## Qué se hizo

- Se respaldó `httpd-vhosts.conf` en `httpd-vhosts.conf.bak-2026-09-25` y se agregó el VirtualHost `logy.local` (configuración completa en `architecture.md`). El VirtualHost existente de otro proyecto (`gacela.local`) no se tocó; sigue siendo el primero, así que `localhost` no cambia.
- `DocumentRoot` = `logy/api`, para no exponer el resto de `logy/`. Se agregó `SetEnvIf Authorization` para que PHP reciba el token JWT.
- `httpd -t`: `Syntax OK`.
- `vite.config.js`: el proxy `/api` ahora apunta a `http://logy.local` (`changeOrigin` envía `Host: logy.local`, que es lo que elige el VirtualHost).
- Se quitaron el script `npm run api` y la configuración `api` de `.claude/launch.json`, y se detuvo el servidor `php -S`.

## Hallazgos / resultado

- Apache corre en consola desde el panel de XAMPP (no como servicio), así que el reinicio lo hace el usuario desde el panel.
- Claude no edita el archivo `hosts` (es configuración del sistema y requiere permisos de administrador): la línea la agrega el usuario.

## Pendientes

- [x] (Usuario) Agregar `127.0.0.1  logy.local` al archivo `hosts`.
- [x] (Usuario) Reiniciar Apache desde XAMPP.
- [x] `http://logy.local/index.php/auth/yo` responde 401 en JSON y el proxy de Vite llega a la API (ver `2026-09-25-helper-api.md`). Falta que el usuario pruebe el login con su contraseña.
