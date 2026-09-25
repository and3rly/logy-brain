# Repositorio git del brain (logy-brain)

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `brain/` (todo), `CLAUDE.md`, `.claude/settings.json`

## Objetivo

Versionar solo el brain en un repositorio propio y público de GitHub llamado `logy-brain`, subido por Claude.

## Qué se hizo

- `git init -b main` dentro de `brain/`, así el repositorio contiene solo el brain.
- Se cambió la regla 2 de `CLAUDE.md`: Claude ya no tiene prohibido hacer commit, pero solo lo hace (con push) cuando el usuario lo ordena explícitamente ("haz commit").
- En `.claude/settings.json`, `git commit` y `git push` pasaron de `deny` a `ask`, así que cada commit o push pide confirmación.
- Se agregó a la regla 2 que el brain es público y no debe contener información sensible.
- Se instaló GitHub CLI 2.101.0 (`winget install GitHub.cli`) para crear el repositorio desde la terminal.

- El contenido de `CLAUDE.md` se movió a `brain/instrucciones.md` para versionarlo en logy-brain. El `CLAUDE.md` de la raíz quedó como un puntero (`@brain/instrucciones.md`), porque Claude solo lo carga desde la raíz del proyecto.
- El usuario decidió crear el repositorio en GitHub de forma manual.
- Como un clon del brain no trae el `CLAUDE.md` de la raíz, se agregaron:
  - `plantillas/CLAUDE.md` y `plantillas/settings.json`: copias de los archivos de la raíz.
  - `instalar.ps1`: los copia a la raíz del proyecto si no existen (no sobrescribe nada).
  - `README.md`: página del repositorio con los pasos de instalación.
- Se probó el instalador en una carpeta temporal: primera ejecución crea los archivos; segunda ejecución no toca nada; los archivos quedan idénticos a las plantillas.

## Hallazgos / resultado

- Las imports `@` dentro de `instrucciones.md` son relativas a `brain/` (`@overview.md`, etc.).
- `.claude/settings.json` vive en la raíz; en el brain solo va su plantilla.
- Windows PowerShell 5.1 lee los `.ps1` sin BOM como ANSI y corrompe los acentos. Por eso `instalar.ps1` no lleva acentos y copia las plantillas byte a byte (`Copy-Item`).
- Riesgo: si cambian los archivos de la raíz y no se actualizan las plantillas, un clon nuevo queda desactualizado. Se agregó una regla en `instrucciones.md` para evitarlo.
- Si más adelante `logy/` se convierte en un repositorio, habrá que agregar `brain/` a su `.gitignore` o manejarlo como submódulo, para no anidar repositorios.

## Pendientes

- [x] El usuario creó el repo público en GitHub: https://github.com/and3rly/logy-brain (el nombre final es `logy-brain`, no `brain-logy`; se actualizaron todas las referencias). Remoto `origin` configurado.
- [ ] Commit y push inicial (el usuario, o Claude cuando se le ordene "haz commit").
