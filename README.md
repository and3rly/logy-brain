# logy-brain

Base de conocimiento (brain) del proyecto **Logy**: reglas de trabajo, arquitectura, convenciones, decisiones y wiki de lo trabajado. Está pensada para personas y para Claude Code.

## Instalación

El brain va en la carpeta `brain/`, dentro de la raíz del proyecto Logy:

```
logy/
├── CLAUDE.md        ← lo crea el instalador
├── .claude/         ← lo crea el instalador
├── brain/           ← este repositorio
├── api/
└── interfaz/
```

1. Clonar dentro del proyecto:
   ```
   cd ruta\a\logy
   git clone https://github.com/and3rly/logy-brain.git brain
   ```
2. Ejecutar el instalador:
   ```
   powershell -ExecutionPolicy Bypass -File brain\instalar.ps1
   ```
   Crea `CLAUDE.md` (que importa `brain/instrucciones.md`) y `.claude/settings.json` en la raíz. Si ya existen, no los sobrescribe.
3. Abrir Claude Code en la carpeta `logy/`.

## Contenido

| Archivo | Para qué |
|---|---|
| `instrucciones.md` | Reglas obligatorias y guía para Claude (punto de entrada) |
| `overview.md`, `status.md`, `conventions.md` | Contexto que se carga en cada sesión |
| `desarrollo.md` | Proceso antes de desarrollar: analizar → reutilizar → adaptar → crear |
| `architecture.md`, `api.md`, `interfaz.md`, `glossary.md` | Referencia técnica |
| `decisions/` | Registro de decisiones (ADR) |
| `wiki/` | Historial de trabajo y análisis |
| `plantillas/` | Archivos que copia el instalador |

## Importante

Este repositorio es **público**: nunca se escriben credenciales, datos personales ni información sensible.
