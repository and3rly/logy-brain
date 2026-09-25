# Logy

Proyecto con backend PHP (`api/`) y frontend (`interfaz/`). El conocimiento del proyecto vive en `brain/`.

Este archivo se carga desde el `CLAUDE.md` de la raíz del proyecto. Las rutas `brain/...` son relativas a la raíz de `logy/`.

## Reglas obligatorias

Estas reglas no se saltan aunque una tarea parezca pequeña.

### 1. Documentar todo en la wiki

- Todo trabajo o análisis que se haga (cambio de código, investigación, consulta a la BD, diagnóstico de un error) queda registrado en `brain/wiki/`, en un archivo `AAAA-MM-DD-tema.md` hecho con `brain/wiki/_plantilla.md`.
- Cada entrada nueva se agrega al índice de `brain/wiki/README.md`, con la más reciente primero.
- Si el trabajo cambia algo estable del proyecto, también se actualiza el archivo correspondiente del brain (`api.md`, `interfaz.md`, `architecture.md`, `conventions.md`).
- Al cerrar una tarea, se actualiza `brain/status.md`.
- La tarea no está terminada hasta que su documentación esté escrita.

### 2. Commit y push solo cuando el usuario lo ordena

- Claude **nunca** hace `git commit` ni `git push` por iniciativa propia. Solo los hace cuando el usuario lo pide explícitamente (por ejemplo, "haz commit"). En ese caso hace el commit y el push al repositorio remoto.
- Mientras tanto, Claude deja los cambios en el directorio de trabajo, dice qué archivos tocó y propone un mensaje de commit.
- Cada commit o push pide confirmación en la herramienta (`ask` en `.claude/settings.json`).
- Nunca, ni siquiera con la orden de commit: `push --force`, `reset --hard`, `rebase`, borrar ramas o tags, ni reescribir el historial, salvo que el usuario pida esa operación concreta.
- Siempre se permiten comandos de solo lectura: `git status`, `git diff`, `git log`, `git show`, `git blame`.
- Hay dos repositorios: **logy-app** (raíz `logy/`: `api/` e `interfaz/`) y **logy-brain** (`brain/`). logy-app excluye el brain, `CLAUDE.md`, `.claude/` y los archivos de configuración con secretos (`database.php`, `jwt.php`; se versionan sus plantillas `*.example.php`).
- El repositorio de `brain/` (**logy-brain**) es **público**. En el brain nunca se escriben credenciales, datos personales, IPs o URLs internas de producción, ni nada sensible.

### 3. Base de datos: solo lectura

- Claude solo ejecuta consultas de lectura: `SELECT`, `SHOW`, `DESCRIBE`, `EXPLAIN`. Para hacerlo usa `brain/herramientas/db_lectura.php` o, si escribe otra consulta, abre la sesión en `READ ONLY` con el mismo patrón que ese script, para que el servidor bloquee cualquier escritura.
- **Prohibido**: `INSERT`, `UPDATE`, `DELETE`, `REPLACE`, `TRUNCATE`, `DROP`, `ALTER`, `CREATE`, `RENAME`, `GRANT`, `REVOKE`, llamar procedimientos que modifiquen datos, y cualquier migración.
- Si hace falta modificar la BD, Claude escribe el SQL, lo documenta en la wiki y se lo entrega al usuario para que lo ejecute.
- Las consultas pesadas llevan `LIMIT`. Los datos personales o sensibles no se copian a la wiki; solo se describe su estructura o se muestran datos agregados.
- Las credenciales de la BD nunca se escriben en el brain ni en la wiki.

### 4. Comprender antes de desarrollar

- Antes de cualquier desarrollo, lee `brain/desarrollo.md` y síguelo. El orden es: **Analizar → Reutilizar → Adaptar → Crear solo lo necesario**.
- No escribas código sin antes analizar el backend, el frontend y cómo se comunican, y sin haber presentado el análisis de impacto.
- Se mantiene la arquitectura MVC y las convenciones existentes. No se reinventa el proyecto ni se agregan librerías o patrones sin justificarlo.

### 5. Los modelos extienden `General_model`

- Todo modelo nuevo en `api/application/models/` extiende `General_model`, no `CI_Model`, y usa sus métodos (`guardar()`, `buscar()`, `cargar()`) antes de escribir consultas propias. Detalle en `brain/api.md`.
- Un modelo que no extienda `General_model` solo se crea con la **autorización explícita del usuario para ese caso**. Claude lo propone, explica por qué y espera la respuesta. La excepción aprobada se anota en la wiki y en un comentario en la cabecera del modelo.
- `General_model.php` no se modifica sin una orden explícita del usuario. Sus limitaciones se rodean desde el modelo hijo (ver los problemas conocidos en `brain/api.md`).

## Contexto que se carga siempre

@overview.md
@status.md
@conventions.md

## Consultar cuando haga falta

- `brain/desarrollo.md`: proceso obligatorio antes y durante cualquier desarrollo
- `brain/architecture.md`: cómo se conectan api e interfaz, entornos y acceso a la BD
- `brain/api.md`: endpoints y modelos (incluye `General_model`)
- `brain/base-de-datos.md`: esquema por módulo, convenciones e inconsistencias de la BD
- `brain/interfaz.md`: pantallas, componentes y estado
- `brain/glossary.md`: términos del dominio
- `brain/decisions/`: registro de decisiones (ADR), una por archivo (`NNNN-titulo.md`, según `0000-plantilla.md`)
- `brain/wiki/`: historial de trabajo y análisis (índice en `README.md`)

## Cómo mantener el brain

- Los archivos que se cargan siempre deben ser cortos. El detalle va en los archivos de consulta.
- No dupliques lo que el código ya dice. Documenta el *por qué* y lo que no es obvio.
- Lo que aún no se decidió se marca como `**Por definir**`. No lo inventes.
- Si cambian el `CLAUDE.md` de la raíz o `.claude/settings.json`, copia el cambio a `brain/plantillas/`. Es lo que usa `brain/instalar.ps1` para configurar un clon nuevo del brain.
