# 0001: CodeIgniter 3 para la API

- **Fecha:** 2026-09-25
- **Estado:** aceptada

## Contexto

Hace falta un backend PHP que corra en el XAMPP local (PHP 7.4).

## Decisión

Usar CodeIgniter 3.1.13 en `api/`.

## Alternativas descartadas

- **Por definir**: completar si se evaluaron otras (CodeIgniter 4, Laravel, PHP sin framework…).

## Consecuencias

- CodeIgniter 3 funciona con PHP 7.4 sin configuración extra.
- CodeIgniter 3 ya no recibe mejoras nuevas. Si en algún momento se migra a PHP 8.2 o superior, hay que revisar la compatibilidad o evaluar pasar a CodeIgniter 4.
