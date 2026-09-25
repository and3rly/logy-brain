# Proceso de desarrollo: analizar y reutilizar antes de crear

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `CLAUDE.md`, `brain/desarrollo.md`

## Objetivo

Incorporar al brain las instrucciones del usuario sobre cómo abordar cualquier desarrollo: comprender el proyecto antes de programar, reutilizar antes de crear y mantener MVC y las convenciones existentes.

## Qué se hizo

- Se creó `brain/desarrollo.md` con el proceso: análisis del backend, del frontend y de su integración; reutilización; análisis de impacto previo; y mantener la línea del proyecto.
- Se agregó la regla 4 ("Comprender antes de desarrollar") a `CLAUDE.md`, que remite a `desarrollo.md`.
- No se duplicó lo que ya estaba documentado (reglas de wiki, git y BD).

## Hallazgos / resultado

- Todavía no hay código propio, así que el análisis y la reutilización aplicarán a medida que exista. Mientras tanto, lo primero que se construya fija la convención y debe quedar en `conventions.md`.
- Las instrucciones llaman "app" al backend, pero la carpeta real es `api/`.

## Pendientes

- (ninguno)
