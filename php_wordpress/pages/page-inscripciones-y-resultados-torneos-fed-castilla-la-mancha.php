<?php
/**
 * Template Name: Inscripciones y Resultados - FGCLM
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

        <div class="nota-importante">
            <h3>Nota Importante</h3>
            <p>En el siguiente listado aparecen las pruebas que integran tanto el calendario general de competiciones
                oficiales como, en la parte inferior, las pruebas locales y comunes que componen los Rankings Locales
                Femenino, Senior y de Pitch & Putt. Asimismo se pueden consultar, en la parte inferior de la página, los
                resultados de temporadas anteriores.</p>
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

        $torneos_todos = array();

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
            
            $torneo_data = array(
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
                'mes'              => $mes,
                'noticia_link'     => $noticia_link,
            );

            $torneos_por_mes[$mes][] = $torneo_data;
            $torneos_todos[] = $torneo_data;
        }

        // Ordenar torneos cronológicamente dentro de cada mes
        foreach ( $torneos_por_mes as $mes => &$items ) {
            usort( $items, function( $a, $b ) {
                return strcmp( $a['fecha_orden'], $b['fecha_orden'] );
            } );
        }
        unset( $items );

        // Ordenar todos los torneos cronológicamente
        usort( $torneos_todos, function( $a, $b ) {
            return strcmp( $a['fecha_orden'], $b['fecha_orden'] );
        } );

        // Clasificar torneos para las categorías específicas
        $torneos_juvenil = array();
        $torneos_pitch_putt = array();
        $torneos_adaptado = array();
        $torneos_rustico = array();

        foreach ( $torneos_todos as $t ) {
            $title_lower = mb_strtolower( $t['title'], 'UTF-8' );
            
            $is_juvenil = ( strpos( $title_lower, 'juvenil' ) !== false || 
                            strpos( $title_lower, 'zonal' ) !== false || 
                            strpos( $title_lower, 'sub 18' ) !== false || 
                            strpos( $title_lower, 'sub-18' ) !== false || 
                            strpos( $title_lower, 'escolar' ) !== false );
                            
            $is_pitch_putt = ( strpos( $title_lower, 'pitch' ) !== false || 
                               strpos( $title_lower, 'p&p' ) !== false );
                               
            $is_adaptado = ( strpos( $title_lower, 'adaptado' ) !== false );
            
            $is_rustico = ( strpos( $title_lower, 'rústico' ) !== false || 
                            strpos( $title_lower, 'rustico' ) !== false || 
                            strpos( $title_lower, 'campos cortos' ) !== false );
            
            if ( $is_juvenil || $is_adaptado ) {
                $torneos_juvenil[] = $t;
            }
            if ( $is_pitch_putt ) {
                $torneos_pitch_putt[] = $t;
            }
            if ( $is_adaptado ) {
                $torneos_adaptado[] = $t;
            }
            if ( $is_rustico ) {
                $torneos_rustico[] = $t;
            }
        }
        ?>

        <!-- =========================================================================
             CALENDARIO GENERAL DE COMPETICIONES OFICIALES 2026
             ========================================================================= -->
        <h2 class="section-title">Calendario General de Competiciones Oficiales 2026</h2>

        <div class="filtros-container">
            <label for="filtroMes">Filtrar por mes:</label>
            <select id="filtroMes">
                <option value="todos">Todos los meses</option>
                <?php
                foreach ( $meses_nombres as $slug => $nombre ) {
                    if ( ! empty( $torneos_por_mes[$slug] ) ) {
                        printf( '<option value="%s">%s</option>', esc_attr( $slug ), esc_html( $nombre ) );
                    }
                }
                ?>
            </select>
        </div>

        <!-- Contenedor del mes actual (resaltado) -->
        <div id="mesResaltadoContainer" style="display: none; margin-bottom: 25px;"></div>

        <div class="grid-meses" id="gridMeses">
            <?php
            foreach ( $meses_nombres as $slug => $nombre ) {
                if ( empty( $torneos_por_mes[$slug] ) ) {
                    continue;
                }
                ?>
                <div class="mes-bloque">
                    <div class="mes-title"><?php echo esc_html( $nombre ) . ' 2026'; ?></div>
                    <ul class="lista-torneos">
                        <?php
                        foreach ( $torneos_por_mes[$slug] as $torneo ) {
                            $link = $torneo['permalink'];
                            if ( ! empty( $torneo['enlace_externo'] ) ) {
                                $link = $torneo['enlace_externo'];
                            } elseif ( ! empty( $torneo['noticia_link'] ) ) {
                                $link = $torneo['noticia_link'];
                            }
                            $insc_link = ! empty( $torneo['link_inscripcion'] ) ? $torneo['link_inscripcion'] : $link;
                            $short_date = fgclm_format_short_date( $torneo['fecha'] );
                            ?>
                            <li class="torneo-item">
                                <span class="fecha"><?php echo esc_html( $short_date ); ?></span>
                                
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

        <!-- =========================================================================
             CATEGORÍAS ESPECÍFICAS (FILTROS / VISTAS)
             ========================================================================= -->

        <h2 class="section-title">Torneos Juveniles y Sub 18 de Castilla La Mancha 2026</h2>
        <div class="mes-bloque">
            <ul class="lista-torneos">
                <?php
                if ( ! empty( $torneos_juvenil ) ) {
                    foreach ( $torneos_juvenil as $torneo ) {
                        $link = $torneo['permalink'];
                        if ( ! empty( $torneo['enlace_externo'] ) ) {
                            $link = $torneo['enlace_externo'];
                        } elseif ( ! empty( $torneo['noticia_link'] ) ) {
                            $link = $torneo['noticia_link'];
                        }
                        $insc_link = ! empty( $torneo['link_inscripcion'] ) ? $torneo['link_inscripcion'] : $link;
                        $short_date = fgclm_format_short_date( $torneo['fecha'] );
                        ?>
                        <li class="torneo-item">
                            <span class="fecha"><?php echo esc_html( $short_date ); ?></span>
                            
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
                } else {
                    echo '<li class="torneo-item" style="color: var(--gray-600);">No hay torneos registrados en esta categoría.</li>';
                }
                ?>
            </ul>
        </div>

        <h2 class="section-title">Torneos de Pitch & Putt Castilla La Mancha 2026</h2>
        <div class="mes-bloque">
            <ul class="lista-torneos">
                <?php
                if ( ! empty( $torneos_pitch_putt ) ) {
                    foreach ( $torneos_pitch_putt as $torneo ) {
                        $link = $torneo['permalink'];
                        if ( ! empty( $torneo['enlace_externo'] ) ) {
                            $link = $torneo['enlace_externo'];
                        } elseif ( ! empty( $torneo['noticia_link'] ) ) {
                            $link = $torneo['noticia_link'];
                        }
                        $insc_link = ! empty( $torneo['link_inscripcion'] ) ? $torneo['link_inscripcion'] : $link;
                        $short_date = fgclm_format_short_date( $torneo['fecha'] );
                        ?>
                        <li class="torneo-item">
                            <span class="fecha"><?php echo esc_html( $short_date ); ?></span>
                            
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
                } else {
                    echo '<li class="torneo-item" style="color: var(--gray-600);">No hay torneos registrados en esta categoría.</li>';
                }
                ?>
            </ul>
        </div>

        <h2 class="section-title">Torneos de Golf Adaptado de Castilla La Mancha 2026</h2>
        <div class="mes-bloque">
            <ul class="lista-torneos">
                <?php
                if ( ! empty( $torneos_adaptado ) ) {
                    foreach ( $torneos_adaptado as $torneo ) {
                        $link = $torneo['permalink'];
                        if ( ! empty( $torneo['enlace_externo'] ) ) {
                            $link = $torneo['enlace_externo'];
                        } elseif ( ! empty( $torneo['noticia_link'] ) ) {
                            $link = $torneo['noticia_link'];
                        }
                        $insc_link = ! empty( $torneo['link_inscripcion'] ) ? $torneo['link_inscripcion'] : $link;
                        $short_date = fgclm_format_short_date( $torneo['fecha'] );
                        ?>
                        <li class="torneo-item">
                            <span class="fecha"><?php echo esc_html( $short_date ); ?></span>
                            
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
                } else {
                    echo '<li class="torneo-item" style="color: var(--gray-600);">No hay torneos registrados en esta categoría.</li>';
                }
                ?>
            </ul>
        </div>

        <h2 class="section-title">Torneos de Campos Rústicos de Castilla La Mancha 2026</h2>
        <div class="mes-bloque">
            <ul class="lista-torneos">
                <?php
                if ( ! empty( $torneos_rustico ) ) {
                    foreach ( $torneos_rustico as $torneo ) {
                        $link = $torneo['permalink'];
                        if ( ! empty( $torneo['enlace_externo'] ) ) {
                            $link = $torneo['enlace_externo'];
                        } elseif ( ! empty( $torneo['noticia_link'] ) ) {
                            $link = $torneo['noticia_link'];
                        }
                        $insc_link = ! empty( $torneo['link_inscripcion'] ) ? $torneo['link_inscripcion'] : $link;
                        $short_date = fgclm_format_short_date( $torneo['fecha'] );
                        ?>
                        <li class="torneo-item">
                            <span class="fecha"><?php echo esc_html( $short_date ); ?></span>
                            
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
                } else {
                    echo '<li class="torneo-item" style="color: var(--gray-600);">No hay torneos registrados en esta categoría.</li>';
                }
                ?>
            </ul>
        </div>

        <!-- =========================================================================
             RANKINGS LOCALES
             ========================================================================= -->
        <h2 class="section-title">Rankings Locales de Castilla La Mancha 2026</h2>
        <div class="rankings-container">
            <div class="ranking-card">
                <h4>Ranking Local Femenino de Castilla La Mancha 2026</h4>
                <p style="color: var(--text-muted); font-size: 10pt; margin-bottom: 15px;">Pruebas Locales</p>
                <a href="<?php echo esc_url( home_url( '/rankings-locales-2026/' ) ); ?>" class="btn-acceso">Acceso</a>
            </div>
            <div class="ranking-card">
                <h4>Ranking Local Senior Masculino de Castilla La Mancha 2026</h4>
                <p style="color: var(--text-muted); font-size: 10pt; margin-bottom: 15px;">Pruebas Locales</p>
                <a href="<?php echo esc_url( home_url( '/rankings-actualizacion-2026/' ) ); ?>" class="btn-acceso">Acceso</a>
            </div>
            <div class="ranking-card">
                <h4>Ranking Local de Pitch & Putt de Castilla La Mancha 2026</h4>
                <p style="color: var(--text-muted); font-size: 10pt; margin-bottom: 15px;">Pruebas Locales</p>
                <a href="<?php echo esc_url( home_url( '/rankings-locales-2026/' ) ); ?>" class="btn-acceso">Acceso</a>
            </div>
        </div>

        <!-- =========================================================================
             RESULTADOS TEMPORADAS ANTERIORES
             ========================================================================= -->
        <h2 class="section-title">Resultados Competiciones FGCLM Años Anteriores</h2>
        <div class="resultados-anteriores">
            <table class="tabla-resultados">
                <thead>
                    <tr>
                        <th>Temporada / Archivo Histórico</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Resultados Competiciones FGCLM 2020</td>
                        <td style="text-align: right;"><a href="<?php echo esc_url( home_url( '/resultados-2020/' ) ); ?>"
                                class="btn-tabla">Acceso</a></td>
                    </tr>
                    <tr>
                        <td>Resultados Competiciones FGCLM 2021</td>
                        <td style="text-align: right;"><a href="<?php echo esc_url( home_url( '/resultados-2021/' ) ); ?>"
                                class="btn-tabla">Acceso</a></td>
                    </tr>
                    <tr>
                        <td>Resultados Competiciones FGCLM 2022</td>
                        <td style="text-align: right;"><a href="<?php echo esc_url( home_url( '/resultados-2022/' ) ); ?>"
                                class="btn-tabla">Acceso</a></td>
                    </tr>
                    <tr>
                        <td>Resultados Competiciones FGCLM 2023</td>
                        <td style="text-align: right;"><a href="<?php echo esc_url( home_url( '/resultados-2023/' ) ); ?>"
                                class="btn-tabla">Acceso</a></td>
                    </tr>
                    <tr>
                        <td>Resultados Competiciones FGCLM 2024</td>
                        <td style="text-align: right;"><a href="<?php echo esc_url( home_url( '/resultados-2024/' ) ); ?>"
                                class="btn-tabla">Acceso</a></td>
                    </tr>
                    <tr>
                        <td>Resultados Competiciones FGCLM 2025</td>
                        <td style="text-align: right;"><a href="<?php echo esc_url( home_url( '/resultados-2025/' ) ); ?>"
                                class="btn-tabla">Acceso</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>




    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filtroMes = document.getElementById('filtroMes');
            const bloquesMeses = document.querySelectorAll('#gridMeses .mes-bloque');
            const containerResaltado = document.getElementById('mesResaltadoContainer');

            // 1. Detectar el mes actual en español
            const nombresMeses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
            const mesActualIdx = new Date().getMonth();
            let mesActualName = nombresMeses[mesActualIdx];

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
