# Arquitectura

## Flujo general

```
[interfaz]  --HTTP/JSON-->  [api: CodeIgniter 3]  --mysqli-->  [MySQL]
```

- La interfaz consume la API por HTTP. **Por definir**: si se sirve desde el mismo origen o si hace falta CORS.
- Autenticación: **Por definir** (sesión PHP, token, JWT…).

## Entornos

| Entorno | URL de la API | Notas |
|---|---|---|
| Local (XAMPP) | `http://localhost/logy/api/index.php/...` | `ENVIRONMENT` = `development` por defecto (variable `CI_ENV`) |
| Producción | **Por definir** | |

## Acceso de Claude a la base de datos

Claude accede **solo en modo lectura** (ver regla 3 en `CLAUDE.md`). Además de la regla, lo recomendable es que se conecte con un usuario MySQL que solo tenga `SELECT`, así la restricción la aplica el propio servidor. Script para crearlo (lo ejecuta el usuario con una cuenta de administrador):

```sql
CREATE USER 'claude_ro'@'localhost' IDENTIFIED BY '<contraseña>';
GRANT SELECT, SHOW VIEW ON `<base_de_datos>`.* TO 'claude_ro'@'localhost';
FLUSH PRIVILEGES;
```

- Usuario: **Por definir** (sugerido `claude_ro`).
- Cliente: `C:\xampp\mysql\bin\mysql.exe`.
- La contraseña no se escribe en el brain. Se guarda fuera del repo; por ejemplo, en un archivo `my.cnf` local o en una variable de entorno.

## Configuración clave de la API

- `api/application/config/config.php`: `base_url` e `encryption_key` están vacíos, `index_page` es `index.php` (aún no hay reescritura de URL).
- `api/application/config/database.php`: `mysqli` en `localhost`, sin usuario ni base de datos configurados.
- `api/application/config/autoload.php`: todavía no carga ninguna librería ni helper.
