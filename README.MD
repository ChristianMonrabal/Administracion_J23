# FAMÍLIA PROFESIONAL: Informática
## CICLO FORMATIVO (Desarrollo de Aplicaciones Web)
### PJ M12 Síntesis
### Actividad 7: Administración de escuela (CRUD del administrador, filtros y correo)

### Objetivo
Generar un CRUD, paginación, transacciones, filtrado y análisis de resultados, y correo.

### Temporización
Aproximadamente 13 horas.

### Organización del espacio
**Metodología**: trabajo colaborativo  
**Agrupación de alumnos**: equipo (cinco alumnos)

### Descripción de la actividad
A partir de la actividad A6 (login escuela) se deben realizar las siguientes ampliaciones:

- Una vez que el administrador haya iniciado sesión, se mostrarán los datos de todos los alumnos.
  
**BONUS**: Para evitar el scroll, el administrador podrá elegir cuántos alumnos se muestran por página.  
Esta página también debe permitir crear, modificar y eliminar alumnos. Además, debe poder filtrar por al menos dos campos simultáneamente.  
Cuando se haga clic en un alumno de la tabla, se debe ir a una página que muestre los datos del alumno y permita introducir sus notas. Una vez introducidas las notas, se debe volver a la página de ver alumnos.  
Cuando se elimine un alumno, también se deben eliminar sus notas. Se debe realizar un procedimiento para eliminar todas las entradas de las dos tablas simultáneamente.  
Cuando se cree un alumno nuevo, se debe ir a una página que permita introducir todos sus datos. Una vez guardado el alumno, se debe volver a la página de ver alumnos.  
También debe haber una página que muestre la información de las notas medias por asignatura, cuál ha sido la asignatura con la media más alta y qué alumnos han obtenido las mejores notas, especificando la asignatura (si dos alumnos han obtenido la mejor nota en una asignatura, solo se debe quedar con el primero).

### Tecnologías utilizadas
- **PHP Procedural**: Para el desarrollo de la lógica de servidor y operaciones con bases de datos.
- **JavaScript**: Para la validación de formularios, interactividad y manipulación del DOM.
- **HTML**: Para la estructura y maquetación de las páginas web.
- **CSS**: Para el diseño y estilo visual, incluyendo el diseño responsive.
- **SQL**: Para la gestión y manipulación de la base de datos (consultas, filtrado, paginación).

### Evaluación de la actividad
- Mockup prototipado de cada versión: 1 punto  
- Diseño responsive: 1 punto  
- Uso correcto de GitHub: 0,5 puntos  
- Crear base de datos en MySQL: 0,5 puntos  
- Generar el CRUD: 1 punto  
- Añadir transacciones: 1 punto  
- Paginación (nº de registros por página): 1 punto  
- Análisis de resultados: 1 punto  
- Filtrado: 1 punto  
- Validación JS utilizando sweetalert2: 1 punto  
- Validaciones en servidor: 1 punto
