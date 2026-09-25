# 0003: Plantilla visual con Bootstrap 5, Font Awesome y componentes propios

- **Fecha:** 2026-09-25
- **Estado:** aceptada

## Contexto

La interfaz no tenía identidad visual. El usuario pidió una plantilla administrativa reutilizable (layout, dashboard, tabla y formulario de ejemplo) con Bootstrap 5, Font Awesome y CSS propio. La BD ya apuntaba a Bootstrap y Font Awesome (`modulo.icono`, `menu.icono` y los badges usan esas clases).

## Decisión

- **Bootstrap 5.3 solo como CSS** (grid, utilidades, formularios, tablas). Su JavaScript **no** se usa: los desplegables, el modal y el sidebar son componentes Vue propios (`BaseDropdown`, `BaseModal`, `Sidebar`), para que el estado lo controle Vue y no haya dos dueños del DOM.
- **Font Awesome 7 Free** (`@fortawesome/fontawesome-free`) con clases CSS, compatible con las clases `fa-solid …` que guarda la BD.
- **Tipografía Inter** empaquetada localmente (`@fontsource-variable/inter`), sin depender de Google Fonts.
- **Colores en variables CSS** (`src/assets/css/variables.css`); el tema oscuro redefine las mismas variables bajo `[data-theme="dark"]`.
- **Gráficos en SVG propio** (`LineChart`, `DonutChart`), sin librería de gráficos. La paleta categórica (`--chart-1…4`) se validó para daltonismo en claro y oscuro.

## Alternativas descartadas

- Bootstrap JS / BootstrapVue: mezcla manipulación directa del DOM con el estado de Vue; BootstrapVue no está al día con Vue 3.
- Tabler (la BD usa algunas clases suyas, como `bg-green`): más pesada y opinada de lo necesario para una base reutilizable. Se puede adoptar después si hace falta.
- Chart.js / ApexCharts: una dependencia más para dos gráficos de ejemplo. Si aparecen reportes con muchos tipos de gráfico, se reevalúa.

## Consecuencias

- Los componentes interactivos de Bootstrap (tooltip, popover, offcanvas, collapse…) no funcionan con sus atributos `data-bs-*`. Si hacen falta, se crea el componente Vue correspondiente en `components/ui/`.
- Todo color nuevo se agrega como variable en `variables.css`, en ambos temas.
