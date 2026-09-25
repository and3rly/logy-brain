# Proceso de desarrollo

Se aplica a toda funcionalidad nueva o cambio de código. Cuando todavía no hay código propio en la parte que se toca, no hay nada que analizar ni reutilizar. En ese caso, lo que se construya **establece** la convención, y se documenta en `conventions.md` para que el resto del código la siga.

**Orden obligatorio:** Analizar → Reutilizar → Adaptar → Crear solo lo necesario.

## 1. Analizar antes de escribir código

Primero se revisa el brain. Después se revisa el código para confirmar o completar lo que dice el brain, porque el brain puede estar desactualizado.

**Backend (`api/`)**: modelos y sus relaciones, controladores, rutas, helpers, librerías y dependencias, clases auxiliares, hooks o middleware, validaciones, manejo de errores, configuración, consultas a la BD, nombres y organización de archivos, y el flujo de una solicitud de principio a fin.

**Frontend (`interfaz/`)**: plantilla visual, layouts, vistas, menús y navegación, componentes reutilizables (formularios, tablas, modales, alertas), estilos, librerías JS y CSS, iconos, colores, tipografías, responsive, cómo se carga y muestra la información, y cómo se organizan las vistas.

**Integración**: cómo consume la interfaz a la API (URLs, formato de la petición y la respuesta, manejo de errores y autenticación).

Si en el análisis aparece algo que no estaba documentado, se agrega al archivo del brain que corresponda.

## 2. Reutilizar y adaptar antes que crear

Antes de crear algo, busca en el proyecto si ya existe una funcionalidad similar, o un modelo, controlador, helper, componente, vista, plantilla, consulta o librería instalada que resuelva la necesidad.

- Si existe, se reutiliza. Si se parece, se evalúa adaptarlo. Solo si no hay nada equivalente se crea algo nuevo.
- No se duplica lógica ni se crean helpers, componentes o clases que ya tengan un equivalente.

## 3. Análisis de impacto (presentarlo antes de programar)

Antes de escribir código, se presenta brevemente al usuario:

1. La estructura actual relevante.
2. El código existente que se puede reutilizar.
3. Los componentes que se pueden adaptar.
4. Los archivos que habría que modificar.
5. Los archivos nuevos que realmente hacen falta.
6. Los riesgos de afectar funcionalidades existentes.
7. El enfoque recomendado, siguiendo la arquitectura actual.

Si el cambio es trivial (un typo, un ajuste de una línea), basta con decir en una frase qué archivo se toca. Si no lo es, se espera la aprobación del usuario antes de programar.

## 4. Mantener la línea del proyecto

- El código nuevo debe parecer que siempre fue parte del proyecto: misma estructura, nombres, validación, manejo de errores, forma de consultar la BD, forma de consumir la API y diseño visual.
- Se mantiene la arquitectura **MVC**. No se introduce otra arquitectura ni se reorganizan carpetas sin necesidad.
- No se reemplaza una implementación que funciona solo por preferencia personal.
- No se agregan tecnologías, librerías ni patrones nuevos sin comprobar primero que el proyecto no tiene ya una solución, y sin una razón clara. Si se agrega uno, se registra en `decisions/`.
- En la interfaz, cada vista o componente nuevo debe integrarse con la identidad visual existente: consistente, profesional y sin romper el diseño actual.
