# Arquitectura

## Flujo general

```
[interfaz: Vue 3 SPA]  --HTTP/JSON-->  [api: CodeIgniter 3]  --mysqli-->  [MySQL]
```

- La interfaz es una SPA (Vue + Vite) separada de la API y la consume por HTTP.
- En desarrollo, la interfaz llama a `/api/...` y el **proxy de Vite** (`server.proxy` en `vite.config.js`) lo reenvía a la API en `http://logy.local/index.php/...` (Apache de XAMPP). Interfaz y API comparten origen, así que no hace falta CORS. En producción: **Por definir** (servir `dist/` y la API bajo el mismo dominio, o configurar CORS).
- Autenticación: **JWT sin estado** (HS256, 8 h). La interfaz guarda el token y lo envía en `Authorization: Bearer`. En la API, `Sesion_token` hace de `session` para `General_model`. Ver `api.md` y `decisions/0004-autenticacion-jwt.md`.

## Entornos

| Entorno | Interfaz | API | Notas |
|---|---|---|---|
| Local | `http://localhost:5173` (`npm run dev` en `interfaz/`) | `http://logy.local/index.php/...` (Apache de XAMPP, VirtualHost `logy.local`) | `ENVIRONMENT` = `development` por defecto (variable `CI_ENV`). Requiere el VirtualHost y la línea `127.0.0.1 logy.local` en el archivo `hosts` (ver abajo) |
| Producción | **Por definir** (`npm run build` genera `interfaz/dist/`) | **Por definir** | |

## VirtualHost local de la API

`localhost` en el Apache de este equipo apunta a otro proyecto, así que la API tiene su propio VirtualHost por nombre en `C:
mpppachenfxtrahttpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName logy.local
    DocumentRoot "C:/xampp/htdocs/logy/api"
    <Directory "C:/xampp/htdocs/logy/api">
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    SetEnvIf Authorization "(.+)" HTTP_AUTHORIZATION=$1
    ErrorLog "logs/logy-error.log"
    CustomLog "logs/logy-access.log" common
</VirtualHost>
```

- El `DocumentRoot` es solo `logy/api`: el resto de `logy/` (brain, interfaz, `CLAUDE.md`) no queda expuesto por web.
- `SetEnvIf Authorization` asegura que PHP reciba el token JWT.
- En `C:WindowsSystem32driverstchosts` (se edita como administrador): `127.0.0.1  logy.local`.
- Después de cambiar la configuración se reinicia Apache desde el panel de XAMPP.
- En un equipo nuevo hay que repetir estos dos pasos (VirtualHost y `hosts`).

## Acceso de Claude a la base de datos

La BD es un **MySQL 8.0 remoto**. La conexión (host, usuario y contraseña) está en `api/application/config/database.php` y **nunca se copia al brain**, que es público.

Claude accede **solo en modo lectura** (regla 3 de `instrucciones.md`) con `brain/herramientas/db_lectura.php`:

```
php brain/herramientas/db_lectura.php resumen            # tablas, filas aprox., privilegios
php brain/herramientas/db_lectura.php columnas           # columnas de todas las tablas
php brain/herramientas/db_lectura.php fks                # llaves foráneas
php brain/herramientas/db_lectura.php catalogo <tabla>   # hasta 30 filas (solo catálogos, no datos personales)
```

El script lee las credenciales de `database.php` y abre la sesión con `SET SESSION TRANSACTION READ ONLY` + `START TRANSACTION READ ONLY`, así que **el servidor rechaza cualquier escritura** (probado el 2026-09-25: "Cannot execute statement in a READ ONLY transaction"). Para cualquier otra consulta se usa el mismo patrón de conexión.

**Riesgo de seguridad:** el usuario configurado en `database.php` es un administrador con todos los privilegios del servidor (`ALL` + `GRANT OPTION`, conectable desde cualquier host). Lo recomendable es que la aplicación y Claude usen usuarios con permisos mínimos. Script de un usuario de solo lectura (lo ejecuta el usuario con una cuenta de administrador):

```sql
CREATE USER 'claude_ro'@'localhost' IDENTIFIED BY '<contraseña>';
GRANT SELECT, SHOW VIEW ON `<base_de_datos>`.* TO 'claude_ro'@'localhost';
FLUSH PRIVILEGES;
```

- La contraseña no se escribe en el brain.
- `logy/` es el repositorio **logy-app**. `database.php` y `jwt.php` están en `.gitignore`; se versionan las plantillas `database.example.php` y `jwt.example.php`, sin credenciales.

## Configuración clave de la API

- `api/application/config/config.php`: `base_url` e `encryption_key` están vacíos, `index_page` es `index.php` (aún no hay reescritura de URL).
- `api/application/config/database.php`: `mysqli`, conectado a la BD remota `db_logy` (ver arriba).
- `api/application/config/autoload.php`: carga `database` y `sesion_token` (como `session`), los helpers `api` y `logy`, y el modelo `General_model`.
- `api/application/config/jwt.php`: clave secreta del JWT (**no se versiona ni se copia al brain**), emisor y duración.
- `api/application/config/config.php`: `composer_autoload` apunta a `application/vendor/autoload.php`.
