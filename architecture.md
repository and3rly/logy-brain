# Arquitectura

## Flujo general

```
[interfaz: Vue 3 SPA]  --HTTP/JSON-->  [api: CodeIgniter 3]  --mysqli-->  [MySQL]
```

- La interfaz es una SPA (Vue + Vite) separada de la API y la consume por HTTP.
- En desarrollo son **dos orígenes distintos**: Vite en `http://localhost:5173` y la API en `http://localhost/logy/api/`. **Por definir** cómo se comunican:
  - **Proxy de Vite** (`server.proxy` en `vite.config.js`): la interfaz llama a `/api/...` y Vite reenvía la petición a Apache. No necesita CORS y la cookie de sesión de PHP funciona sin configuración extra.
  - **CORS en la API**: la interfaz llama directo a Apache y hay que configurar las cabeceras CORS (y `credentials` si se usa la sesión).
- Autenticación: **Por definir**. `General_model` usa la sesión de PHP (`usuario_id`, `empresa_id`), lo que apunta a autenticación por sesión y cookie.

## Entornos

| Entorno | Interfaz | API | Notas |
|---|---|---|---|
| Local | `http://localhost:5173` (`npm run dev`) | `http://localhost/logy/api/index.php/...` | `ENVIRONMENT` = `development` por defecto (variable `CI_ENV`) |
| Producción | **Por definir** (`npm run build` genera `interfaz/dist/`) | **Por definir** | |

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
- Cuando `logy/` sea un repositorio git, `api/application/config/database.php` **no debe versionarse** con credenciales reales (usar `.gitignore` o una plantilla sin credenciales).

## Configuración clave de la API

- `api/application/config/config.php`: `base_url` e `encryption_key` están vacíos, `index_page` es `index.php` (aún no hay reescritura de URL).
- `api/application/config/database.php`: `mysqli`, conectado a la BD remota `db_logy` (ver arriba).
- `api/application/config/autoload.php`: todavía no carga ninguna librería ni helper.
