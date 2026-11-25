<?php
/**
 * Page template: plantilla-resultado
 * Auto-converted and enhanced from plantilla_resultado.html
 *
 * @package FGCLM_Theme_v071
 */

if ( ! function_exists( 'fgclm_normalize_search_query' ) ) {
    function fgclm_normalize_search_query( $title ) {
        $title = mb_strtolower( $title, 'UTF-8' );
        
        $title = str_replace( 'cto.', 'campeonato', $title );
        $title = str_replace( 'cto', 'campeonato', $title );
        $title = str_replace( 'clm', 'castilla la mancha', $title );
        $title = str_replace( 'rfeg', 'real federacion española de golf', $title );
        $title = str_replace( 'fgclm', 'federacion de golf de castilla la mancha', $title );
        $title = str_replace( 'p&p', 'pitch putt', $title );
        $title = str_replace( 'p & p', 'pitch putt', $title );
        $title = str_replace( 'pitch & putt', 'pitch putt', $title );
        $title = str_replace( 'pitch and putt', 'pitch putt', $title );
        $title = str_replace( 'cat.', 'categoria', $title );
        $title = str_replace( 'cat', 'categoria', $title );
        
        $title = str_replace(
            array('á','é','í','ó','ú','ü','ñ','–','—','-'),
            array('a','e','i','o','u','u','n',' ',' ',' '),
            $title
        );
        
        $title = preg_replace( '/[^a-z0-9\s]/', ' ', $title );
        $title = preg_replace( '/\s+/', ' ', $title );
        
        return trim( $title );
    }
}

// Intentar redirigir a la noticia real del torneo si existe
$torneo_query_param = '';
if ( isset( $_GET['torneo_name'] ) ) {
    $torneo_query_param = sanitize_text_field( $_GET['torneo_name'] );
} elseif ( isset( $_GET['torneo'] ) ) {
    $torneo_query_param = sanitize_text_field( $_GET['torneo'] );
}

if ( ! empty( $torneo_query_param ) ) {
    // Extraer el año de fecha_lugar si está disponible para acotar la búsqueda
    $year = '';
    $month_num = 0;
    if ( isset( $_GET['fecha_lugar'] ) ) {
        $fecha_lugar_param = sanitize_text_field( $_GET['fecha_lugar'] );
        if ( preg_match( '/\b(20\d{2})\b/', $fecha_lugar_param, $matches ) ) {
            $year = intval( $matches[1] );
        }
        
        $months_map = array(
            'ene' => 1, 'enero' => 1,
            'feb' => 2, 'febrero' => 2,
            'mar' => 3, 'marzo' => 3,
            'abr' => 4, 'abril' => 4,
            'may' => 5, 'mayo' => 5,
            'jun' => 6, 'junio' => 6,
            'jul' => 7, 'julio' => 7,
            'ago' => 8, 'agosto' => 8,
            'sep' => 9, 'septiembre' => 9,
            'oct' => 10, 'octubre' => 10,
            'nov' => 11, 'noviembre' => 11,
            'dic' => 12, 'diciembre' => 12,
        );
        foreach ( $months_map as $m_name => $m_val ) {
            if ( stripos( $fecha_lugar_param, $m_name ) !== false ) {
                $month_num = $m_val;
                break;
            }
        }
    }

    $search_query = fgclm_normalize_search_query( $torneo_query_param );
    $keywords = explode( ' ', $search_query );
    $filler_words = array( 'de', 'del', 'y', 'en', 'la', 'el', 'los', 'las', 'un', 'una', 'para', 'por', 'con', 'i', 'ii', 'iii', 'iv', 'v', 'vi' );
    
    $filtered_keywords = array();
    foreach ( $keywords as $word ) {
        if ( strlen( $word ) > 2 && ! in_array( $word, $filler_words ) ) {
            $filtered_keywords[] = $word;
        }
    }
    
    $query_string = ! empty( $filtered_keywords ) ? implode( ' ', $filtered_keywords ) : $torneo_query_param;

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 15,
        's'              => $query_string,
        'post_status'    => 'publish',
    );

    if ( ! empty( $year ) ) {
        $args['date_query'] = array(
            array(
                'after'     => array(
                    'year'  => $year - 1,
                    'month' => 11,
                    'day'   => 15,
                ),
                'before'    => array(
                    'year'  => $year + 1,
                    'month' => 2,
                    'day'   => 28,
                ),
                'inclusive' => true,
            ),
        );
    }

    $posts = get_posts( $args );

    if ( empty( $posts ) && ! empty( $year ) ) {
        unset( $args['date_query'] );
        $posts = get_posts( $args );
    }

    $best_post = null;
    $best_score = -1;

    foreach ( $posts as $post ) {
        $score = 0;
        
        $post_title = mb_strtolower( $post->post_title, 'UTF-8' );
        $normalized_post_title = fgclm_normalize_search_query( $post->post_title );
        
        $match_count = 0;
        foreach ( $filtered_keywords as $keyword ) {
            if ( strpos( $normalized_post_title, $keyword ) !== false ) {
                $match_count++;
            }
        }
        
        $total_keywords = count( $filtered_keywords );
        $keyword_match_ratio = $total_keywords > 0 ? ( $match_count / $total_keywords ) : 0;
        $score += $keyword_match_ratio * 80;
        
        if ( strpos( $normalized_post_title, $search_query ) !== false ) {
            $score += 20;
        }
        
        $post_date = $post->post_date;
        $post_year = intval( substr( $post_date, 0, 4 ) );
        $post_month = intval( substr( $post_date, 5, 2 ) );
        
        if ( ! empty( $year ) ) {
            if ( $post_year === $year ) {
                $score += 15;
            } elseif ( abs( $post_year - $year ) <= 1 ) {
                $score += 5;
            } else {
                $score -= 30;
            }
        }
        
        if ( $month_num > 0 ) {
            if ( $post_month === $month_num ) {
                $score += 10;
            } elseif ( $post_month === ( $month_num % 12 + 1 ) ) {
                $score += 5;
            } elseif ( abs( $post_month - $month_num ) > 2 ) {
                $score -= 10;
            }
        }
        
        if ( preg_match( '/\b(resultados|clasificacion|triunfo|vence|gana|campeon|ganadores|concluye|finaliza)\b/u', $post_title ) ) {
            $score += 10;
        }
        
        if ( preg_match( '/\b(inscripciones|horarios|salidas|listado|participantes|abierto el plazo)\b/u', $post_title ) ) {
            $score -= 15;
        }

        if ( $score > $best_score ) {
            $best_score = $score;
            $best_post = $post;
        }
    }

    if ( $best_post !== null && $best_score >= 50 ) {
        wp_redirect( get_permalink( $best_post->ID ) );
        exit;
    }
}

get_header();

// We can read parameters from query string to populate the page dynamically
$nombre_torneo = isset($_GET['torneo_name']) ? sanitize_text_field($_GET['torneo_name']) : (isset($_GET['torneo']) ? sanitize_text_field($_GET['torneo']) : 'Plantilla de Resultados de Competición');
$fecha_lugar = isset($_GET['fecha_lugar']) ? sanitize_text_field($_GET['fecha_lugar']) : 'Fecha y Lugar por confirmar';
?>

<style>
    /* MODO OSCURO (A11Y) */
    body.a11y-dark-mode,
    body.a11y-dark-mode .resultados-page-body,
    body.a11y-dark-mode .resultados-container {
        background-color: var(--gray-900, #1a1a18) !important;
        color: var(--gray-100, #ededea) !important;
    }
    body.a11y-dark-mode .header-title {
        color: var(--gold, #c9a84c) !important;
    }
    body.a11y-dark-mode .header-subtitle {
        color: var(--gray-300, #b8b8b4) !important;
    }
    body.a11y-dark-mode .highlight-box {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border-left-color: var(--gold, #c9a84c) !important;
        color: var(--gray-100, #ededea) !important;
    }
    body.a11y-dark-mode h2 {
        color: var(--green-accent, #2d9e6b) !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }
    body.a11y-dark-mode p,
    body.a11y-dark-mode ul li {
        color: var(--gray-100, #ededea) !important;
    }
    body.a11y-dark-mode .data-table th {
        background-color: var(--green-dark, #0a3d2e) !important;
        color: var(--gold, #c9a84c) !important;
        border-bottom: 2px solid var(--gold, #c9a84c) !important;
    }
    body.a11y-dark-mode .data-table td {
        background-color: transparent !important;
        color: var(--gray-100, #ededea) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    body.a11y-dark-mode .data-table tbody tr:hover td {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    body.a11y-dark-mode .date-badge {
        background-color: var(--green-dark, #0a3d2e) !important;
        color: var(--gray-100, #ededea) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }
    body.a11y-dark-mode .link-text {
        color: var(--gold, #c9a84c) !important;
    }
    body.a11y-dark-mode .link-text:hover {
        color: var(--green-accent, #2d9e6b) !important;
    }
    body.a11y-dark-mode .location-text {
        color: var(--gray-300, #b8b8b4) !important;
    }
    body.a11y-dark-mode strong {
        color: var(--white, #ffffff) !important;
    }
    body.a11y-dark-mode .data-table tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }
    body.a11y-dark-mode .data-table tr:nth-child(odd) {
        background-color: transparent !important;
    }

    /* CORRECCIÓN DE LAYOUT */
    .header-banner {
        margin: 0 0 25px 0 !important;
        padding: 35px 30px !important;
        border-radius: 8px;
    }
</style>

<div class="resultados-container">

    <div class="header-banner">
        <table>
            <tr>
                <td>
                    <div class="header-title" id="torneo-nombre"><?php echo esc_html($nombre_torneo); ?></div>
                    <div class="header-subtitle" id="torneo-fecha-lugar"><?php echo esc_html($fecha_lugar); ?></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="highlight-box">
        <p><strong>Nota:</strong> Esta es una plantilla genérica para los resultados de la competición. Al subir al Workspace, esta página será alimentada con los datos reales del torneo.</p>
    </div>

    <h2>Clasificación Final</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Posición</th>
                <th style="width: 50%;">Jugador/a</th>
                <th style="width: 20%;">Licencia</th>
                <th style="width: 15%;">Golpes (Total)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>1</strong></td>
                <td>Jugador Ejemplo 1</td>
                <td>CM00000001</td>
                <td class="math">68</td>
            </tr>
            <tr>
                <td><strong>2</strong></td>
                <td>Jugador Ejemplo 2</td>
                <td>CM00000002</td>
                <td class="math">70</td>
            </tr>
            <tr>
                <td><strong>3</strong></td>
                <td>Jugador Ejemplo 3</td>
                <td>CM00000003</td>
                <td class="math">72</td>
            </tr>
        </tbody>
    </table>

    <h2>Crónica del Torneo</h2>
    <p>Aquí se incluirá un resumen de lo acontecido durante la jornada de competición, destacando los momentos más importantes y el rendimiento de los ganadores en sus respectivas categorías.</p>
    
    <h2>Documentos Adjuntos</h2>
    <table class="data-table" style="max-width: 600px;">
        <tbody>
            <tr>
                <td><a href="#" class="link-text">Clasificación Scratch Absoluta (PDF)</a></td>
            </tr>
            <tr>
                <td><a href="#" class="link-text">Clasificación Handicap (PDF)</a></td>
            </tr>
            <tr>
                <td><a href="#" class="link-text">Galería de Imágenes</a></td>
            </tr>
        </tbody>
    </table>
    
    <div style="margin-top: 40px; text-align: center;">
        <a href="javascript:history.back()" class="btn-secondary" id="btn-volver">Volver al Calendario</a>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);

        // Poblar el título del torneo
        const nombreTorneo = params.get('torneo_name') || params.get('torneo');
        if(nombreTorneo) {
            document.getElementById('torneo-nombre').textContent = decodeURIComponent(nombreTorneo);
            // Actualizar también el título de la página para SEO y pestaña del navegador
            document.title = decodeURIComponent(nombreTorneo) + ' — FGCLM';
        }

        // Poblar la fecha y lugar
        const fechaLugar = params.get('fecha_lugar');
        if(fechaLugar) {
            document.getElementById('torneo-fecha-lugar').textContent = decodeURIComponent(fechaLugar);
        }

        // Botón "Volver" inteligente: detectar el año para volver a la página de resultados correcta
        const btnVolver = document.getElementById('btn-volver');
        if (btnVolver && fechaLugar) {
            const decoded = decodeURIComponent(fechaLugar);
            // Buscar un año de 4 dígitos (20XX) en la fecha_lugar
            const yearMatch = decoded.match(/\b(20\d{2})\b/);
            if (yearMatch) {
                const year = yearMatch[1];
                const homeUrl = (typeof fgclmSettings !== 'undefined' && fgclmSettings.homeUrl)
                    ? fgclmSettings.homeUrl
                    : '';
                btnVolver.href = homeUrl + '/resultados-' + year + '/';
                btnVolver.textContent = 'Volver a Resultados ' + year;
            } else if (document.referrer) {
                btnVolver.href = document.referrer;
            }
        }
    });
</script>

<?php
get_footer();
