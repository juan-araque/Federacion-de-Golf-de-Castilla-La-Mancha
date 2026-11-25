/**
 * resultados-links-fix.js
 * 
 * Corrige automáticamente todos los enlaces que apuntan a "plantilla_resultado.html"
 * en las tablas de resultados de competiciones de años anteriores.
 * 
 * Los reemplaza con la URL correcta de WordPress:
 *   /plantilla-resultado/?torneo=NOMBRE&fecha_lugar=FECHA, SEDE
 *
 * @package FGCLM_Theme_v071
 */
document.addEventListener('DOMContentLoaded', () => {
    // Obtener la URL base del sitio WordPress (inyectada desde functions.php)
    const homeUrl = (typeof fgclmSettings !== 'undefined' && fgclmSettings.homeUrl)
        ? fgclmSettings.homeUrl
        : '';

    // Buscar todas las tablas de datos en la página
    const dataTables = document.querySelectorAll('.data-table');

    dataTables.forEach(table => {
        // Buscar todos los enlaces que apuntan a plantilla_resultado.html
        const brokenLinks = table.querySelectorAll('a[href*="plantilla_resultado.html"]');

        brokenLinks.forEach(link => {
            const row = link.closest('tr');
            if (!row) return;

            // Extraer datos de la fila de la tabla
            const cells = row.querySelectorAll('td');
            if (cells.length < 2) return;

            // El nombre del torneo es el texto del enlace
            const torneoName = link.textContent.trim();

            // La fecha viene de la primera celda (con clase date-badge)
            const fechaCell = row.querySelector('.date-badge') || cells[0];
            const fecha = fechaCell ? fechaCell.textContent.trim() : '';

            // La sede viene de la última celda (con clase location-text)
            const sedeCell = row.querySelector('.location-text') || cells[cells.length - 1];
            const sede = sedeCell ? sedeCell.textContent.trim() : '';

            // Construir la fecha_lugar combinada
            const fechaLugar = fecha && sede ? (fecha + ', ' + sede) : (fecha || sede);

            // Construir la URL correcta de WordPress
            const newUrl = homeUrl + '/plantilla-resultado/'
                + '?torneo_name=' + encodeURIComponent(torneoName)
                + '&fecha_lugar=' + encodeURIComponent(fechaLugar);

            // Reemplazar el href roto con la URL correcta
            link.setAttribute('href', newUrl);
        });
    });
});
