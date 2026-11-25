<?php
/**
 * Template Name: Calendario Oficial de Competiciones 2026 - FGCLM
 *
 * @package FGCLM_Theme_v071
 */

get_header();
?>

<style>
/* Estilos del mes resaltado (actual) */
        .mes-resaltado {
            border: 2px solid var(--gold) !important;
            box-shadow: 0 10px 25px rgba(201, 168, 76, 0.15) !important;
            background: linear-gradient(to right, var(--white), #fdfcf7) !important;
            position: relative;
            overflow: hidden;
            animation: fadeInHighlight 0.6s ease;
        }

        .mes-resaltado::after {
            content: "Mes Actual";
            position: absolute;
            top: 20px;
            right: 25px;
            background-color: var(--gold);
            color: var(--green-dark);
            font-family: var(--font-body);
            font-size: 8.5pt;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(201, 168, 76, 0.25);
        }

        .mes-resaltado .mes-title {
            color: var(--green-dark) !important;
            border-bottom: 2px solid var(--gold-light) !important;
            padding-bottom: 12px;
            margin-bottom: 18px;
            font-size: 14pt;
        }

        @keyframes fadeInHighlight {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 600px) {
            .mes-resaltado::after {
                position: static;
                display: inline-block;
                margin-top: 10px;
                margin-bottom: 12px;
            }
        }
</style>


    <div class="page-hero">
        <div class="page-hero-content">
            <div class="page-hero-label">Torneos</div>
            <h1>Calendario Oficial – <em>Inscripciones y Resultados</em></h1>
            <p>Consulta la forma de inscripción y los resultados de los torneos oficiales organizados por la Federación
                de Golf de Castilla-La Mancha. Pincha en cada caso sobre el nombre del campeonato oficial para obtener
                la información deseada.</p>
        </div>
    </div>

    <div class="container">
        
        <div class="competiciones-intro">
            <?php
            // Cargar el contenido escrito en el editor de WordPress (ej. Notas o introducción)
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>

        <?php
        // Definir los nombres de los meses en español para la visualización y orden
        $meses_nombres = array(
            'enero'      => 'Enero',
            'febrero'    => 'Febrero',
            'marzo'      => 'Marzo',
            'abril'      => 'Abril',
            'mayo'       => 'Mayo',
            'junio'      => 'Junio',
            'julio'      => 'Julio',
            'agosto'     => 'Agosto',
            'septiembre' => 'Septiembre',
            'octubre'    => 'Octubre',
            'noviembre'  => 'Noviembre',
            'diciembre'  => 'Diciembre'
        );

        // Obtener todos los posts del CPT Torneo
        $torneos_posts = get_posts( array(
            'post_type'      => 'torneo',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ) );

        // Agrupar torneos por mes
        $torneos_por_mes = array();
        foreach ( $meses_nombres as $slug => $nombre ) {
            $torneos_por_mes[$slug] = array();
        }

        foreach ( $torneos_posts as $post ) {
            $mes = get_post_meta( $post->ID, '_torneo_mes', true );
            $mes = strtolower( trim( $mes ) );
            if ( ! array_key_exists( $mes, $torneos_por_mes ) ) {
                $mes = 'enero'; // Fallback
            }
            
            $fecha_orden = get_post_meta( $post->ID, '_torneo_fecha_orden', true );
            if ( empty( $fecha_orden ) ) {
                $fecha_orden = '9999-12-31'; // Poner al final si no tiene fecha de orden
            }
            
            $noticia_id = get_post_meta( $post->ID, '_torneo_noticia_relacionada', true );
            $noticia_link = $noticia_id ? get_permalink( $noticia_id ) : '';
            
            $torneos_por_mes[$mes][] = array(
                'id'               => $post->ID,
                'title'            => get_the_title( $post ),
                'permalink'        => get_permalink( $post ),
                'fecha'            => get_post_meta( $post->ID, '_torneo_fecha', true ),
                'sede'             => get_post_meta( $post->ID, '_torneo_sede', true ),
                'fecha_orden'      => $fecha_orden,
                'enlace_externo'   => get_post_meta( $post->ID, '_torneo_enlace_externo', true ),
                'es_wagr'          => get_post_meta( $post->ID, '_torneo_es_wagr', true ) === 'yes',
                'es_inscripcion'   => get_post_meta( $post->ID, '_torneo_es_inscripcion', true ) === 'yes',
                'link_inscripcion' => get_post_meta( $post->ID, '_torneo_link_inscripcion', true ),
                'noticia_link'     => $noticia_link,
            );
        }

        // Ordenar torneos cronológicamente dentro de cada mes
        foreach ( $torneos_por_mes as $mes => &$items ) {
            usort( $items, function( $a, $b ) {
                return strcmp( $a['fecha_orden'], $b['fecha_orden'] );
            } );
        }
        unset( $items );

        // Determinar si hay al menos un mes con torneos para mostrar los filtros
        $tiene_torneos = false;
        foreach ( $torneos_por_mes as $items ) {
            if ( ! empty( $items ) ) {
                $tiene_torneos = true;
                break;
            }
        }
        ?>

        <?php if ( $tiene_torneos ) : ?>
            <div class="filtros-container" id="filtrosContainer" style="display: flex; margin-top: 30px;">
                <label for="filtroMes">Filtrar por mes:</label>
                <select id="filtroMes">
                    <option value="todos">Todos los meses</option>
                    <?php
                    foreach ( $meses_nombres as $slug => $nombre ) {
                        if ( ! empty( $torneos_por_mes[$slug] ) ) {
                            printf( '<option value="%s">%s 2026</option>', esc_attr( $slug ), esc_html( strtoupper( $nombre ) ) );
                        }
                    }
                    ?>
                </select>
            </div>
        <?php endif; ?>

        <div id="mesResaltadoContainer" style="display: none; margin-bottom: 25px;"></div>

        <div class="grid-meses" id="gridMeses">
            <?php
            foreach ( $meses_nombres as $slug => $nombre ) {
                if ( empty( $torneos_por_mes[$slug] ) ) {
                    continue;
                }
                ?>
                <div class="mes-bloque" data-mes="<?php echo esc_attr( $slug ); ?>">
                    <div class="mes-title"><?php echo esc_html( strtoupper( $nombre ) ) . ' 2026'; ?></div>
                    <ul class="lista-torneos">
                        <?php
                        foreach ( $torneos_por_mes[$slug] as $torneo ) {
                            // Enlace primario: enlace externo, o noticia relacionada si existe, o el permalink de WordPress
                            $link = $torneo['permalink'];
                            if ( ! empty( $torneo['enlace_externo'] ) ) {
                                $link = $torneo['enlace_externo'];
                            } elseif ( ! empty( $torneo['noticia_link'] ) ) {
                                $link = $torneo['noticia_link'];
                            }
                            
                            // Enlace de inscripción: enlace específico si existe, o el enlace primario
                            $insc_link = ! empty( $torneo['link_inscripcion'] ) ? $torneo['link_inscripcion'] : $link;
                            ?>
                            <li class="torneo-item">
                                <span class="fecha"><?php echo esc_html( $torneo['fecha'] ); ?></span>
                                
                                <?php if ( $link ) : ?>
                                    <a class="nombre-torneo" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $torneo['title'] ); ?></a>
                                <?php else : ?>
                                    <span class="nombre-torneo" style="color: var(--gray-900);"><?php echo esc_html( $torneo['title'] ); ?></span>
                                <?php endif; ?>

                                <?php if ( ! empty( $torneo['sede'] ) ) : ?>
                                    <span class="lugar">— <?php echo esc_html( $torneo['sede'] ); ?></span>
                                <?php endif; ?>

                                <?php if ( $torneo['es_wagr'] ) : ?>
                                    <span class="badge badge-wagr">WAGR</span>
                                <?php endif; ?>

                                <?php if ( $torneo['es_inscripcion'] ) : ?>
                                    <a class="badge badge-inscripcion" href="<?php echo esc_url( $insc_link ); ?>">Inscripciones</a>
                                <?php endif; ?>

                                <?php if ( ! empty( $torneo['noticia_link'] ) ) : ?>
                                    <a class="badge badge-noticia" href="<?php echo esc_url( $torneo['noticia_link'] ); ?>">Noticia</a>
                                <?php endif; ?>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
            ?>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filtroMes = document.getElementById('filtroMes');
            const bloquesMeses = document.querySelectorAll('#gridMeses .mes-bloque');
            const containerResaltado = document.getElementById('mesResaltadoContainer');

            if (!filtroMes || bloquesMeses.length === 0 || !containerResaltado) return;

            // 1. Detectar el mes actual en español
            const nombresMeses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
            const mesActualIdx = new Date().getMonth();

            // 2. Buscar el bloque del mes actual (o el siguiente con torneos si el actual no tiene)
            let bloqueParaResaltar = null;
            let indexBusqueda = mesActualIdx;

            // Buscamos a partir del mes actual hacia adelante
            while (indexBusqueda < 12) {
                const nombreMesBusqueda = nombresMeses[indexBusqueda];
                for (let bloque of bloquesMeses) {
                    const titulo = bloque.querySelector('.mes-title').textContent.toLowerCase();
                    if (titulo.includes(nombreMesBusqueda)) {
                        bloqueParaResaltar = bloque;
                        break;
                    }
                }
                if (bloqueParaResaltar) break;
                indexBusqueda++;
            }

            // Si no se encuentra ninguno (por ejemplo, estamos en diciembre y no hay más), buscar desde el principio del año
            if (!bloqueParaResaltar) {
                for (let index = 0; index < mesActualIdx; index++) {
                    const nombreMesBusqueda = nombresMeses[index];
                    for (let bloque of bloquesMeses) {
                        const titulo = bloque.querySelector('.mes-title').textContent.toLowerCase();
                        if (titulo.includes(nombreMesBusqueda)) {
                            bloqueParaResaltar = bloque;
                            break;
                        }
                    }
                    if (bloqueParaResaltar) break;
                }
            }

            // Guardamos el texto del título del mes destacado
            let highlightedTitleText = "";

            // 3. Mover el bloque al contenedor resaltado si existe y darle la clase de estilo
            if (bloqueParaResaltar) {
                highlightedTitleText = bloqueParaResaltar.querySelector('.mes-title').textContent.trim();

                // Lo movemos de posición en el DOM
                containerResaltado.appendChild(bloqueParaResaltar);
                bloqueParaResaltar.classList.add('mes-resaltado');
                containerResaltado.style.display = 'block';
            }

            // 4. Manejador de cambio de filtro
            filtroMes.addEventListener('change', (e) => {
                const mesSeleccionado = e.target.value.toLowerCase();

                // Manejo de la visualización del bloque resaltado
                if (bloqueParaResaltar) {
                    const tituloHighlight = highlightedTitleText.toLowerCase();
                    if (mesSeleccionado === 'todos') {
                        containerResaltado.style.display = 'block';
                    } else if (tituloHighlight.includes(mesSeleccionado)) {
                        containerResaltado.style.display = 'block';
                    } else {
                        containerResaltado.style.display = 'none';
                    }
                }

                // Manejo de los bloques regulares (que no son el resaltado)
                bloquesMeses.forEach(bloque => {
                    if (bloque === bloqueParaResaltar) return;

                    if (mesSeleccionado === 'todos') {
                        bloque.style.display = 'block';
                    } else {
                        const tituloMes = bloque.querySelector('.mes-title').textContent.toLowerCase();
                        if (tituloMes.includes(mesSeleccionado)) {
                            bloque.style.display = 'block';
                        } else {
                            bloque.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>

<?php
get_footer();
