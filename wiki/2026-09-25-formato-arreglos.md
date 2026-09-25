# Formato: un elemento por línea en arreglos y objetos

- **Fecha:** 2026-09-25
- **Tipo:** trabajo
- **Archivos / tablas involucrados:** `api/application/` (Auth, Usuario_model, Sesion_token, Token_jwt, api_helper), `interfaz/src/` (25 archivos .js y .vue), `brain/conventions.md`

## Objetivo

El usuario pidió que los arreglos con varios parámetros se escriban con un elemento por línea, en todo el proyecto.

## Qué se hizo

- Regla en `conventions.md` (sección Generales): arreglos asociativos de PHP y objetos de JS con **2 o más claves** → una clave por línea. Con una sola clave, o listas sin claves (`['Empresa_model', 'Rol_model']`), puede ir en una línea. No aplica a los enlaces del `<template>` de Vue.
- **PHP**: se expandieron las llamadas `autenticar([...])` en `Auth` y `buscar([...])` del rol en `Usuario_model`, y los arreglos anidados `rol`/`empresa` de `perfil()`. Se quitaron la coma después del último elemento (como en el ejemplo del usuario) y los espacios al final de las líneas. `General_model` no se tocó.
- **JavaScript**: 149 objetos en 25 archivos, con un script que usa `@babel/parser` (ya instalado como dependencia de Vite) para ubicar los objetos de una línea con 2+ propiedades, solo dentro de `<script>` y archivos `.js`. Después se corrigieron a mano los casos que quedaron incómodos: comentarios después de la llave de cierre (`}, // sm | lg` → comentario arriba de la prop), un objeto dentro de un ternario en `BaseSelect` (pasó a `if` + `return`), la sangría del ternario de `DonutChart` y las migas de "Nuevo usuario" en el router (un elemento por línea). En JS se conserva la coma final (estilo del proyecto).
- De paso se corrigió el comentario de `services/api.js`, que todavía decía "servidor de PHP" (ahora es Apache, `logy.local`).

## Hallazgos / resultado

- `npm run build` sin errores; el login carga en el navegador. La API contra `logy.local`: login 401 con credenciales incorrectas; `auth/yo` 200 con token válido y 401 con vencido / otra clave / `alg: none` / otro emisor.
- En la consola apareció `watch is not defined` en `Login.vue`, pero era un registro viejo (15:56) de una recarga en caliente anterior; el import es correcto.

## Pendientes

- (ninguno)
