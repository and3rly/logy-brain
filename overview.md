# Visión general

## Qué es Logy

**Por definir**: objetivo del proyecto, a quién va dirigido y qué problema resuelve.

## Stack

| Parte | Tecnología | Ubicación |
|---|---|---|
| Backend / API | PHP 7.4 + CodeIgniter 3.1.13 | `api/` |
| Base de datos | MySQL/MariaDB (driver `mysqli`) vía XAMPP | **Por definir**: nombre de la BD |
| Frontend | **Por definir** | `interfaz/` |
| Servidor local | XAMPP (Apache), en `C:\xampp\htdocs\logy` | |

## Estructura

```
logy/
├── CLAUDE.md      ← punto de entrada para Claude
├── brain/         ← conocimiento del proyecto
├── api/           ← CodeIgniter 3 (el código propio va en api/application/)
│   ├── application/   controllers, models, config, views…
│   └── system/        núcleo del framework, NO se modifica
└── interfaz/      ← frontend (vacío por ahora)
```
