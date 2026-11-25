<?php
/**
 * FGCLM Theme v0.5 — functions and definitions
 *
 * All functions use the fgclm_v071_ prefix to avoid conflicts with any
 * previously installed theme.
 *
 * @package FGCLM_Theme_v071
 */

// ─────────────────────────────────────────────────────────────
// 1. THEME SETUP
// ─────────────────────────────────────────────────────────────
if ( ! function_exists( 'fgclm_v071_theme_setup' ) ) :
    function fgclm_v071_theme_setup() {
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo', array(
            'height'      => 80,
            'width'       => 80,
            'flex-height' => true,
            'flex-width'  => true,
        ) );

        register_nav_menus( array(
            'primary' => esc_html__( 'Menú Principal', 'fgclm-v071' ),
        ) );

        add_theme_support( 'html5', array(
            'search-form', 'comment-form', 'comment-list',
            'gallery', 'caption', 'style', 'script',
        ) );
    }
endif;
add_action( 'after_setup_theme', 'fgclm_v071_theme_setup' );


// ─────────────────────────────────────────────────────────────
// 2. ENQUEUE SCRIPTS & STYLES
// ─────────────────────────────────────────────────────────────
function fgclm_v071_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'fgclm-v071-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap',
        array(), null
    );

    // Theme main stylesheet (style.css)
    wp_enqueue_style( 'fgclm-v071-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );

    // Shared stylesheet
    wp_enqueue_style( 'fgclm-v071-shared', get_template_directory_uri() . '/shared.css', array(), wp_get_theme()->get( 'Version' ) );

    // Core Theme JavaScript
    wp_enqueue_script( 'fgclm-v071-theme-shared', get_template_directory_uri() . '/theme-shared.js', array(), wp_get_theme()->get( 'Version' ), true );
    wp_localize_script( 'fgclm-v071-theme-shared', 'fgclmSettings', array(
        'homeUrl' => esc_url( home_url() ),
    ) );

    // Weather widget JS
    wp_enqueue_script( 'fgclm-v071-weather', get_template_directory_uri() . '/weather.js', array(), wp_get_theme()->get( 'Version' ), true );

    // Page-specific assets
    if ( is_front_page() || is_page_template( 'page-pagina-inicio.php' ) ) {
        wp_enqueue_script( 'fgclm-v071-calendario-index', get_template_directory_uri() . '/calendario_index.js', array(), '0.7.1', true );
        wp_enqueue_script( 'fgclm-v071-noticias-index', get_template_directory_uri() . '/noticias_index.js', array(), '0.7.1', true );
    }

    // Estilos para entradas individuales (noticias)
    if ( is_single() ) {
        wp_enqueue_style( 'fgclm-v071-single', get_template_directory_uri() . '/single.css', array( 'fgclm-v071-style' ), '0.7.1' );
    }

    // Estilos para el archivo/listado de noticias
    if ( is_home() || is_archive() || is_search() ) {
        wp_enqueue_style( 'fgclm-v071-noticias-archive', get_template_directory_uri() . '/noticias-archive.css', array( 'fgclm-v071-style' ), '0.7.1' );
    }

    // Script para corregir enlaces rotos en páginas de resultados de años anteriores
    if ( is_page() ) {
        $page_slug = get_post_field( 'post_name', get_queried_object_id() );
        if ( strpos( $page_slug, 'resultados-20' ) === 0 ) {
            wp_enqueue_script( 'fgclm-v071-resultados-links-fix', get_template_directory_uri() . '/resultados-links-fix.js', array( 'fgclm-v071-theme-shared' ), '0.7.1', true );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'fgclm_v071_scripts' );

/**
 * Remove default WordPress block library CSS that conflicts with our design.
 */
function fgclm_v071_dequeue_block_styles() {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' );
    wp_dequeue_style( 'classic-theme-styles' );
    wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'fgclm_v071_dequeue_block_styles', 100 );


// ─────────────────────────────────────────────────────────────
// 3. LATEST NEWS HELPER (for homepage carousel)
// ─────────────────────────────────────────────────────────────
function fgclm_v071_format_news_item() {
    $cats = array();
    $categories = get_the_category();
    if ( ! empty( $categories ) ) {
        foreach ( $categories as $c ) {
            $cats[] = $c->name;
        }
    }
    if ( empty( $cats ) ) {
        $cats[] = 'Actualidad';
    }

    $excerpt = get_the_excerpt();
    if ( empty( $excerpt ) ) {
        $excerpt = wp_strip_all_tags( get_the_content() );
    }
    if ( strlen( $excerpt ) > 140 ) {
        $excerpt = substr( $excerpt, 0, 137 ) . '...';
    }

    return array(
        'id'         => get_the_ID(),
        'titulo'     => get_the_title(),
        'fecha'      => get_the_date( 'j M Y' ),
        'anyo'       => get_the_date( 'Y' ),
        'categorias' => $cats,
        'imagen'     => get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: '',
        'excerpt'    => $excerpt,
        'permalink'  => get_permalink(),
    );
}

function fgclm_v071_get_latest_news_data() {
    $today = current_time( 'mysql' );

    // Query 1: Active pinned news (where _fgclm_sticky_until exists and is >= today)
    $pinned_query = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 11,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => '_fgclm_sticky_until',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'DATETIME',
            ),
        ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    $pinned_ids = array();
    $news = array();

    if ( $pinned_query->have_posts() ) {
        while ( $pinned_query->have_posts() ) {
            $pinned_query->the_post();
            $id = get_the_ID();
            $pinned_ids[] = $id;
            $news[] = fgclm_v071_format_news_item();
        }
        wp_reset_postdata();
    }

    // Query 2: Regular news (excluding the pinned ones)
    $remaining_count = 11 - count( $news );
    if ( $remaining_count > 0 ) {
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $remaining_count,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        if ( ! empty( $pinned_ids ) ) {
            $args['post__not_in'] = $pinned_ids;
        }

        $regular_query = new WP_Query( $args );
        if ( $regular_query->have_posts() ) {
            while ( $regular_query->have_posts() ) {
                $regular_query->the_post();
                $news[] = fgclm_v071_format_news_item();
            }
            wp_reset_postdata();
        }
    }

    return $news;
}

// Custom Meta Box for Sticky News (Pinning)
function fgclm_add_sticky_meta_box() {
    add_meta_box(
        'fgclm_sticky_meta',
        'Destacar Noticia en Portada (Fijar)',
        'fgclm_render_sticky_meta_box',
        'post',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'fgclm_add_sticky_meta_box' );

function fgclm_render_sticky_meta_box( $post ) {
    wp_nonce_field( 'fgclm_sticky_save', 'fgclm_sticky_nonce' );
    $sticky_type = get_post_meta( $post->ID, '_fgclm_sticky_type', true );
    if ( ! $sticky_type ) {
        $sticky_type = 'no';
    }
    $sticky_until = get_post_meta( $post->ID, '_fgclm_sticky_until', true );
    ?>
    <p>
        <label for="fgclm_sticky_type">Fijar en el carrusel de portada por:</label>
        <select name="fgclm_sticky_type" id="fgclm_sticky_type" style="width: 100%; margin-top: 5px;">
            <option value="no" <?php selected( $sticky_type, 'no' ); ?>>No fijar</option>
            <option value="1_week" <?php selected( $sticky_type, '1_week' ); ?>>Fijar durante 1 semana</option>
            <option value="2_weeks" <?php selected( $sticky_type, '2_weeks' ); ?>>Fijar durante 2 semanas</option>
            <option value="1_month" <?php selected( $sticky_type, '1_month' ); ?>>Fijar durante 1 mes</option>
            <option value="2_months" <?php selected( $sticky_type, '2_months' ); ?>>Fijar durante 2 meses</option>
            <option value="3_months" <?php selected( $sticky_type, '3_months' ); ?>>Fijar durante 3 meses</option>
            <option value="6_months" <?php selected( $sticky_type, '6_months' ); ?>>Fijar durante 6 meses</option>
        </select>
    </p>
    <?php if ( $sticky_until ) : ?>
        <p style="font-size: 11px; color: #666; margin-top: 8px;">
            <strong>Fijada hasta:</strong> <?php echo esc_html( date_i18n( 'j M Y H:i', strtotime( $sticky_until ) ) ); ?>
        </p>
    <?php endif; ?>
    <?php
}

function fgclm_save_sticky_meta_box( $post_id ) {
    if ( ! isset( $_POST['fgclm_sticky_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['fgclm_sticky_nonce'], 'fgclm_sticky_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['fgclm_sticky_type'] ) ) {
        $sticky_type = sanitize_text_field( $_POST['fgclm_sticky_type'] );
        update_post_meta( $post_id, '_fgclm_sticky_type', $sticky_type );

        if ( $sticky_type === 'no' ) {
            delete_post_meta( $post_id, '_fgclm_sticky_until' );
        } else {
            $post = get_post( $post_id );
            $pub_date = $post->post_date;
            $interval = '+1 month';
            switch ( $sticky_type ) {
                case '1_week':
                    $interval = '+1 week';
                    break;
                case '2_weeks':
                    $interval = '+2 weeks';
                    break;
                case '1_month':
                    $interval = '+1 month';
                    break;
                case '2_months':
                    $interval = '+2 months';
                    break;
                case '3_months':
                    $interval = '+3 months';
                    break;
                case '6_months':
                    $interval = '+6 months';
                    break;
            }
            $until_date = date( 'Y-m-d H:i:s', strtotime( $interval, strtotime( $pub_date ) ) );
            update_post_meta( $post_id, '_fgclm_sticky_until', $until_date );
        }
    }
}
add_action( 'save_post', 'fgclm_save_sticky_meta_box' );


// ─────────────────────────────────────────────────────────────
// 4. CUSTOM NAVIGATION WALKER
// ─────────────────────────────────────────────────────────────
class FGCLM_V03_Nav_Walker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }

    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        if ( in_array( 'menu-item-has-children', $classes ) ) {
            $classes[] = 'dropdown';
        }
        if ( in_array( 'current-menu-item', $classes ) || in_array( 'current-page-ancestor', $classes ) ) {
            $classes[] = 'active';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= $indent . '<li' . $class_names .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );

        if ( strtolower($title) === 'en vivo' ) {
            $title = '<span class="recording-indicator"></span>' . $title;
        }

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}


// ─────────────────────────────────────────────────────────────
// 5. FALLBACK MENU (when no menu is assigned)
// ─────────────────────────────────────────────────────────────
function fgclm_v071_default_menu() {
    ?>
    <ul class="nav-links" id="navLinks">
        <li><a href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>">Noticias</a></li>
        <li class="dropdown">
            <a href="<?php echo esc_url( home_url( '/presidente/' ) ); ?>">Federación</a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo esc_url( home_url( '/presidente/' ) ); ?>">Bienvenida del Presidente</a></li>
                <li><a href="<?php echo esc_url( home_url( '/junta-directiva-y-asamblea-general/' ) ); ?>">Junta Directiva y Asamblea</a></li>
                <li><a href="<?php echo esc_url( home_url( '/comites/' ) ); ?>">Comités y Delegaciones</a></li>
                <li><a href="<?php echo esc_url( home_url( '/licencia/' ) ); ?>">Licencia y Hándicap</a></li>
                <li><a href="<?php echo esc_url( home_url( '/subvenciones/' ) ); ?>">Subvenciones</a></li>
                <li><a href="<?php echo esc_url( home_url( '/transparencia/' ) ); ?>">Transparencia</a></li>
                <li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Contacto</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="<?php echo esc_url( home_url( '/reglas-golf/' ) ); ?>">Reglamentos</a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo esc_url( home_url( '/reglas-golf/' ) ); ?>">Reglas de Golf</a></li>
                <li><a href="<?php echo esc_url( home_url( '/circulares-rfeg/' ) ); ?>">Circulares RFEG</a></li>
                <li><a href="<?php echo esc_url( home_url( '/circulares-fgclm/' ) ); ?>">Circulares FGCLM</a></li>
                <li><a href="<?php echo esc_url( home_url( '/reglas-locales/' ) ); ?>">Reglas Locales</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="<?php echo esc_url( home_url( '/rankings/' ) ); ?>">Rankings</a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo esc_url( home_url( '/rankings/' ) ); ?>">Rankings Castilla-La Mancha</a></li>
                <li><a href="<?php echo esc_url( home_url( '/circuito-5categoria/' ) ); ?>">Circuito 5ª Categoría</a></li>
            </ul>
        </li>
        <li><a href="<?php echo esc_url( home_url( '/clubes/' ) ); ?>">Clubes</a></li>
        <li class="dropdown">
            <a href="<?php echo esc_url( home_url( '/competiciones/' ) ); ?>">Torneos</a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo esc_url( home_url( '/competiciones/' ) ); ?>">Calendario</a></li>
                <li><a href="https://rfegolf.es/CompetenciaPaginas/AllCompetitions.aspx" target="_blank" rel="noopener">Calendario RFEG</a></li>
                <li><a href="<?php echo esc_url( home_url( '/en-vivo/' ) ); ?>"><span class="recording-indicator"></span>En vivo</a></li>
            </ul>
        </li>
        <li><a href="<?php echo esc_url( home_url( '/escuela/' ) ); ?>">Escuela</a></li>
    </ul>
    <?php
}


// ─────────────────────────────────────────────────────────────
// 6. CUSTOM POST TYPE: TORNEO (for tournament detail pages)
// ─────────────────────────────────────────────────────────────
function fgclm_v071_register_torneo_cpt() {
    register_post_type( 'torneo', array(
        'labels' => array(
            'name'               => 'Torneos',
            'singular_name'      => 'Torneo',
            'add_new'            => 'Añadir Torneo',
            'add_new_item'       => 'Añadir Nuevo Torneo',
            'edit_item'          => 'Editar Torneo',
            'view_item'          => 'Ver Torneo',
            'all_items'          => 'Todos los Torneos',
            'search_items'       => 'Buscar Torneos',
            'not_found'          => 'No se encontraron torneos.',
        ),
        'public'             => true,
        'has_archive'        => false,
        'rewrite'            => array( 'slug' => 'torneo', 'with_front' => false ),
        'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ),
        'menu_icon'          => 'dashicons-calendar-alt',
        'show_in_rest'       => true,
    ) );
}
add_action( 'init', 'fgclm_v071_register_torneo_cpt' );


// ─────────────────────────────────────────────────────────────
// 7. AUTO-CREATE PAGES ON THEME ACTIVATION
// ─────────────────────────────────────────────────────────────
function fgclm_v071_create_pages() {
    // Main site pages
    $pages = array(
        'presidente'          => 'Bienvenida del Presidente',
        'junta-directiva'     => 'Junta Directiva y Asamblea',
        'comites'             => 'Comités y Delegaciones',
        'licencia'            => 'Licencia y Hándicap',
        'subvenciones'        => 'Subvenciones',
        'transparencia'       => 'Transparencia',
        'contacto'            => 'Contacto',
        'reglas-golf'         => 'Reglas de Golf',
        'circulares-rfeg'     => 'Circulares RFEG',
        'circulares-fgclm'    => 'Circulares FGCLM',
        'circulares'          => 'Circulares',
        'reglas-locales'      => 'Reglas Locales',
        'rankings'            => 'Rankings Castilla-La Mancha',
        'circuito-5categoria' => 'Circuito 5ª Categoría',
        'clubes'              => 'Clubes',
        'competiciones'       => 'Competiciones y Calendario',
        'en-vivo'             => 'En vivo',
        'enlace-en-vivo'      => 'Enlace En Vivo',
        'escuela'             => 'Escuela de Golf',
        'noticias'            => 'Noticias',
        'aviso-legal'         => 'Aviso Legal',
        'privacidad'          => 'Política de Privacidad',
        'cookies'             => 'Política de Cookies',
        'pagina-inicio'       => 'Página de Inicio',
        'autonomicos-2026'    => 'Autonómicos 2026',
        // Rankings subpages
        'rankings-autonomicos-2021' => 'Rankings Autonómicos 2021',
        'rankings-autonomicos-2022' => 'Rankings Autonómicos 2022',
        'rankings-autonomicos-2023' => 'Rankings Autonómicos 2023',
        'rankings-autonomicos-2024' => 'Rankings Autonómicos 2024',
        'rankings-autonomicos-2025' => 'Rankings Autonómicos 2025',
        'rankings-autonomicos-2026' => 'Rankings Autonómicos 2026',
        'rankings-juvenil-2021'     => 'Ranking Juvenil 2021',
        'rankings-juvenil-2022'     => 'Ranking Juvenil 2022',
        'rankings-juvenil-2023'     => 'Ranking Juvenil 2023',
        'rankings-juvenil-2024'     => 'Ranking Juvenil 2024',
        'rankings-juvenil-2025'     => 'Ranking Juvenil 2025',
        'rankings-masculino-2021'   => 'Ranking Masculino 2021',
        'rankings-masculino-2022'   => 'Ranking Masculino 2022',
        'rankings-masculino-2023'   => 'Ranking Masculino 2023',
        'rankings-masculino-2024'   => 'Ranking Masculino 2024',
        'rankings-masculino-2025'   => 'Ranking Masculino 2025',
        'rankings-locales-2026'     => 'Ranking Locales 2026',
        'rankings-actualizacion-2026' => 'Actualización Rankings 2026',
        'rankings-reglamento-juvenil-2026' => 'Reglamento Ranking Juvenil 2026',
        // LicenHandi subpages
        'obtencion-handicap'  => 'Obtención de Hándicap',
        'seguro-accidente'    => 'Seguro de Accidente',
        'tramites-cuotas'     => 'Trámites y Cuotas',
        // Resultados subpages
        'resultados-2020'     => 'Resultados 2020',
        'resultados-2021'     => 'Resultados 2021',
        'resultados-2022'     => 'Resultados 2022',
        'resultados-2023'     => 'Resultados 2023',
        'resultados-2024'     => 'Resultados 2024',
        'resultados-2025'     => 'Resultados 2025',
        'plantilla-resultado' => 'Plantilla de Resultados',
    );

    foreach ( $pages as $slug => $title ) {
        // Only create if a page with this slug doesn't already exist
        $existing = get_page_by_path( $slug );
        if ( ! $existing ) {
            $inserted_id = wp_insert_post( array(
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
            ) );

            if ( $inserted_id && ! is_wp_error( $inserted_id ) ) {
                // If a page-{$slug}.php file exists, assign it as page template
                if ( file_exists( get_template_directory() . "/page-{$slug}.php" ) ) {
                    update_post_meta( $inserted_id, '_wp_page_template', "page-{$slug}.php" );
                }
            }
        }
    }

    // Set "Noticias" page as the posts page and "Página de Inicio" as the front page
    $noticias_page = get_page_by_path( 'noticias' );
    $inicio_page   = get_page_by_path( 'pagina-inicio' );
    if ( $noticias_page ) {
        update_option( 'page_for_posts', $noticias_page->ID );
    }
    if ( $inicio_page ) {
        update_option( 'page_on_front', $inicio_page->ID );
    }
    update_option( 'show_on_front', 'page' );

    // Create tournament posts (custom post type)
    fgclm_v071_create_tournament_posts();

    // Flush rewrite rules to register new CPT permalinks
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'fgclm_v071_create_pages' );

/**
 * Run page creation on init, guarded by an option so it only runs once per release/update.
 */
function fgclm_v071_create_pages_on_init() {
    if ( ! get_option( 'fgclm_v071_pages_created_v2_8' ) ) {
        fgclm_v071_create_pages();
        update_option( 'fgclm_v071_pages_created_v2_8', 1 );
    }
}
add_action( 'init', 'fgclm_v071_create_pages_on_init' );

/**
 * Create all tournament detail posts from extracted data.
 */
function fgclm_v071_create_tournament_posts() {
    $torneos = array(
        // FEBRERO
        array( 'slug' => 'torneo-provincial-toledo', 'title' => 'Torneo Provincial de Toledo', 'fecha' => '14 - 15 de febrero de 2026', 'sede' => 'Palomarejos y Layos', 'mes' => 'febrero', 'fecha_orden' => '2026-02-14' ),
        // MARZO
        array( 'slug' => 'campeonato-rusticos', 'title' => 'I Campeonato de Castilla La Mancha Individual de Campos Rústicos', 'fecha' => '1 de marzo de 2026', 'sede' => 'Golf Mudela', 'mes' => 'marzo', 'fecha_orden' => '2026-03-01' ),
        array( 'slug' => 'ranking-locales', 'title' => 'Final Ranking Locales Femenino, Senior y Pitch & Putt', 'fecha' => '7 - 8 de marzo de 2026', 'sede' => 'Playa Serena', 'mes' => 'marzo', 'fecha_orden' => '2026-03-07' ),
        array( 'slug' => 'campeonato-sub18', 'title' => 'V Campeonato de Castilla La Mancha Sub 18', 'fecha' => '13 - 15 de marzo de 2026', 'sede' => 'Cabanillas Casa de Campo Golf', 'mes' => 'marzo', 'fecha_orden' => '2026-03-13' ),
        array( 'slug' => 'puntuable-zonal-1', 'title' => 'I Puntuable Zonal Madrid – Castilla La Mancha', 'fecha' => '14 - 15 de marzo de 2026', 'sede' => 'Layos', 'mes' => 'marzo', 'fecha_orden' => '2026-03-14' ),
        array( 'slug' => 'puntuable-ranking-juvenil-1', 'title' => 'I Puntuable Ranking Juvenil de Castilla La Mancha', 'fecha' => '21 de marzo de 2026', 'sede' => 'Señorío de Illescas', 'mes' => 'marzo', 'fecha_orden' => '2026-03-21' ),
        array( 'slug' => 'jornada-inaugural-liga-femenina', 'title' => 'Jornada Inaugural Liga Femenina por Equipos de Castilla La Mancha', 'fecha' => '22 de marzo de 2026', 'sede' => 'Torrijos Golf', 'mes' => 'marzo', 'fecha_orden' => '2026-03-22' ),
        array( 'slug' => 'jornada-inaugural-liga-senior-masculina', 'title' => 'Jornada Inaugural Liga Senior Masculina por Equipos de Castilla La Mancha', 'fecha' => '22 de marzo de 2026', 'sede' => 'Layos', 'mes' => 'marzo', 'fecha_orden' => '2026-03-22' ),
        array( 'slug' => 'campeonato-parejas-four-ball', 'title' => 'XXXIII Campeonato de Castilla La Mancha de Parejas Four Ball', 'fecha' => '28 de marzo de 2026', 'sede' => 'Golf Ciudad Real', 'mes' => 'marzo', 'fecha_orden' => '2026-03-28' ),
        array( 'slug' => 'liga-escolar-ranking-juvenil-2', 'title' => 'I Torneo Liga Escolar y II Puntuable Ranking Juvenil de Castilla La Mancha', 'fecha' => '28 de marzo de 2026', 'sede' => 'Olalla Golf', 'mes' => 'marzo', 'fecha_orden' => '2026-03-28' ),
        array( 'slug' => 'puntuable-ranking-juvenil-3', 'title' => 'III Puntuable Ranking Juvenil de Castilla La Mancha', 'fecha' => '30 de marzo de 2026', 'sede' => 'Olalla Golf', 'mes' => 'marzo', 'fecha_orden' => '2026-03-30' ),
        array( 'slug' => 'puntuable-ranking-juvenil-4', 'title' => 'IV Puntuable Ranking Juvenil de Castilla La Mancha', 'fecha' => '31 de marzo de 2026', 'sede' => 'Olalla Golf', 'mes' => 'marzo', 'fecha_orden' => '2026-03-31' ),
        // ABRIL
        array( 'slug' => 'puntuable-ranking-juvenil-5', 'title' => 'V Puntuable Ranking Juvenil de Castilla La Mancha', 'fecha' => '11 de abril de 2026', 'sede' => 'Golf Ciudad Real', 'mes' => 'abril', 'fecha_orden' => '2026-04-11' ),
        array( 'slug' => 'jornada-2-liga-femenina', 'title' => 'II Jornada Liga Femenina por Equipos de Castilla La Mancha', 'fecha' => '12 de abril de 2026', 'sede' => 'Olalla Golf', 'mes' => 'abril', 'fecha_orden' => '2026-04-12' ),
        array( 'slug' => 'jornada-2-liga-senior-masculina', 'title' => 'II Jornada Liga Senior Masculina por Equipos de Castilla La Mancha', 'fecha' => '12 de abril de 2026', 'sede' => 'Montealvar', 'mes' => 'abril', 'fecha_orden' => '2026-04-12' ),
        array( 'slug' => 'torneo-provincial-ciudad-real', 'title' => 'Torneo Provincial de Ciudad Real', 'fecha' => '18 de abril de 2026', 'sede' => 'Golf Ciudad Real', 'mes' => 'abril', 'fecha_orden' => '2026-04-18' ),
        array( 'slug' => 'campeonato-interclubes-pitch-putt', 'title' => 'Campeonato de Castilla La Mancha Interclubes de Pitch & Putt', 'fecha' => '19 de abril de 2026', 'sede' => 'Abedul Golf', 'mes' => 'abril', 'fecha_orden' => '2026-04-19' ),
        array( 'slug' => 'campeonato-parejas-rusticos', 'title' => 'II Campeonato de Castilla La Mancha por Parejas de Campos Rústicos', 'fecha' => '19 de abril de 2026', 'sede' => 'Golf Cuesta Blanca', 'mes' => 'abril', 'fecha_orden' => '2026-04-19' ),
        array( 'slug' => 'puntuable-ranking-juvenil-maj', 'title' => 'Puntuable Ranking Juvenil de Castilla La Mancha – CIRCUITO MAJ', 'fecha' => '19 de abril de 2026', 'sede' => 'Las Pinaillas', 'mes' => 'abril', 'fecha_orden' => '2026-04-19' ),
        array( 'slug' => 'campeonato-interclubes', 'title' => 'Campeonato de Castilla La Mancha Interclubes', 'fecha' => '25 de abril de 2026', 'sede' => 'Layos', 'mes' => 'abril', 'fecha_orden' => '2026-04-25' ),
        // MAYO
        array( 'slug' => 'campeonato-campos-cortos-parejas', 'title' => 'Campeonato de Castilla La Mancha de Campos Cortos por Parejas', 'fecha' => '1 de mayo de 2026', 'sede' => 'Cifuentes', 'mes' => 'mayo', 'fecha_orden' => '2026-05-01' ),
        array( 'slug' => 'torneo-provincial-albacete', 'title' => 'Torneo Provincial de Albacete', 'fecha' => '1 o 2 de mayo de 2026', 'sede' => 'Las Pinaillas', 'mes' => 'mayo', 'fecha_orden' => '2026-05-01' ),
        array( 'slug' => 'campeonato-absoluto-masculino', 'title' => 'XVII Campeonato de Castilla La Mancha Absoluto Masculino', 'fecha' => '8 - 10 de mayo de 2026', 'sede' => 'Layos', 'mes' => 'mayo', 'fecha_orden' => '2026-05-08' ),
        array( 'slug' => 'campeonato-absoluto-femenino', 'title' => 'XX Campeonato de Castilla La Mancha Absoluto Femenino', 'fecha' => '9 de mayo de 2026', 'sede' => 'Señorío de Illescas', 'mes' => 'mayo', 'fecha_orden' => '2026-05-09' ),
        array( 'slug' => 'campeonato-campos-cortos', 'title' => 'Campeonato de Castilla La Mancha de Campos Cortos', 'fecha' => '9 de mayo de 2026', 'sede' => 'La Vereda', 'mes' => 'mayo', 'fecha_orden' => '2026-05-09' ),
        array( 'slug' => 'puntuable-ranking-juvenil-6', 'title' => 'VI Puntuable Ranking Juvenil de Castilla La Mancha', 'fecha' => '16 de mayo de 2026', 'sede' => 'Layos', 'mes' => 'mayo', 'fecha_orden' => '2026-05-16' ),
        array( 'slug' => 'puntuable-zonal-2', 'title' => 'II Puntuable Zonal Juvenil de Castilla La Mancha – Madrid', 'fecha' => '16 - 17 de mayo de 2026', 'sede' => 'Cabanillas Casa de Campo Golf', 'mes' => 'mayo', 'fecha_orden' => '2026-05-16' ),
        array( 'slug' => 'jornada-3-liga-femenina', 'title' => 'III Jornada Liga Femenina por Equipos de Castilla La Mancha', 'fecha' => '17 de mayo de 2026', 'sede' => 'La Roda', 'mes' => 'mayo', 'fecha_orden' => '2026-05-17' ),
        array( 'slug' => 'jornada-3-liga-senior-masculina', 'title' => 'III Jornada Liga Senior Masculina por Equipos de Castilla La Mancha', 'fecha' => '17 de mayo de 2026', 'sede' => 'Palomarejos Golf', 'mes' => 'mayo', 'fecha_orden' => '2026-05-17' ),
        array( 'slug' => 'campeonato-parejas-pitch-putt', 'title' => 'Campeonato de Castilla La Mancha de Parejas de Pitch & Putt', 'fecha' => '23 de mayo de 2026', 'sede' => 'Talavera', 'mes' => 'mayo', 'fecha_orden' => '2026-05-23' ),
        array( 'slug' => 'puntuable-ranking-juvenil-7', 'title' => 'VII Puntuable Ranking Juvenil de Castilla La Mancha', 'fecha' => '30 de mayo de 2026', 'sede' => 'La Lagunilla', 'mes' => 'mayo', 'fecha_orden' => '2026-05-30' ),
        array( 'slug' => 'copa-federacion', 'title' => 'XXXVII Copa Federación de Castilla La Mancha', 'fecha' => '31 de mayo de 2026', 'sede' => 'Golf Montealvar', 'mes' => 'mayo', 'fecha_orden' => '2026-05-31' ),
        // JUNIO
        array( 'slug' => 'puntuable-ranking-juvenil-8', 'title' => 'VIII Puntuable Ranking Juvenil de Castilla La Mancha', 'fecha' => '6 de junio de 2026', 'sede' => 'La Roda', 'mes' => 'junio', 'fecha_orden' => '2026-06-06' ),
        array( 'slug' => 'jornada-4-liga-femenina', 'title' => 'IV Jornada Liga Femenina por Equipos de Castilla La Mancha', 'fecha' => '7 de junio de 2026', 'sede' => 'Cabanillas Casa de Campo Golf', 'mes' => 'junio', 'fecha_orden' => '2026-06-07' ),
        array( 'slug' => 'jornada-4-liga-senior-masculina', 'title' => 'IV Jornada Liga Senior por Equipos de Castilla La Mancha', 'fecha' => '7 de junio de 2026', 'sede' => 'Olalla Golf', 'mes' => 'junio', 'fecha_orden' => '2026-06-07' ),
        array( 'slug' => 'puntuable-ranking-juvenil-9', 'title' => 'IX Puntuable Ranking Juvenil de Castilla La Mancha', 'fecha' => '13 de junio de 2026', 'sede' => 'Cabanillas Casa de Campo Golf', 'mes' => 'junio', 'fecha_orden' => '2026-06-13' ),
        array( 'slug' => 'campeonato-senior-masculino-femenino', 'title' => 'XVII Campeonato de Castilla La Mancha Senior Masculino y Femenino', 'fecha' => '13-14 de junio de 2026', 'sede' => 'Torrijos Golf', 'mes' => 'junio', 'fecha_orden' => '2026-06-13' ),
        array( 'slug' => 'campeonato-match-play-pitch-putt', 'title' => 'Campeonato de Castilla La Mancha Match Play de Pitch & Putt', 'fecha' => '20-21 de junio de 2026', 'sede' => 'La Roda', 'mes' => 'junio', 'fecha_orden' => '2026-06-20' ),
        array( 'slug' => 'torneo-provincial-cuenca', 'title' => 'Torneo Provincial de Cuenca', 'fecha' => '27 de junio de 2026', 'sede' => 'Olalla Golf', 'mes' => 'junio', 'fecha_orden' => '2026-06-27' ),
    );

    foreach ( $torneos as $torneo ) {
        // Check if this tournament already exists
        $existing = get_posts( array(
            'post_type'   => 'torneo',
            'name'        => $torneo['slug'],
            'post_status' => 'any',
            'numberposts' => 1,
        ) );

        if ( empty( $existing ) ) {
            $post_id = wp_insert_post( array(
                'post_title'   => $torneo['title'],
                'post_name'    => $torneo['slug'],
                'post_status'  => 'publish',
                'post_type'    => 'torneo',
                'post_content' => '',
            ) );

            if ( $post_id && ! is_wp_error( $post_id ) ) {
                update_post_meta( $post_id, '_torneo_fecha', $torneo['fecha'] );
                update_post_meta( $post_id, '_torneo_sede',  $torneo['sede'] );
                update_post_meta( $post_id, '_torneo_mes',   $torneo['mes'] );
                update_post_meta( $post_id, '_torneo_fecha_orden', $torneo['fecha_orden'] );
            }
        }
    }
}

/**
 * Migration for existing tournaments to ensure they have the proper sort date.
 */
function fgclm_v071_migrate_torneo_dates() {
    if ( get_option( 'fgclm_v071_torneo_dates_migrated_v2' ) ) {
        return;
    }
    $torneo_dates = array(
        'torneo-provincial-toledo' => '2026-02-14',
        'campeonato-rusticos' => '2026-03-01',
        'ranking-locales' => '2026-03-07',
        'campeonato-sub18' => '2026-03-13',
        'puntuable-zonal-1' => '2026-03-14',
        'puntuable-ranking-juvenil-1' => '2026-03-21',
        'jornada-inaugural-liga-femenina' => '2026-03-22',
        'jornada-inaugural-liga-senior-masculina' => '2026-03-22',
        'campeonato-parejas-four-ball' => '2026-03-28',
        'liga-escolar-ranking-juvenil-2' => '2026-03-28',
        'puntuable-ranking-juvenil-3' => '2026-03-30',
        'puntuable-ranking-juvenil-4' => '2026-03-31',
        'puntuable-ranking-juvenil-5' => '2026-04-11',
        'jornada-2-liga-femenina' => '2026-04-12',
        'jornada-2-liga-senior-masculina' => '2026-04-12',
        'torneo-provincial-ciudad-real' => '2026-04-18',
        'campeonato-interclubes-pitch-putt' => '2026-04-19',
        'campeonato-parejas-rusticos' => '2026-04-19',
        'puntuable-ranking-juvenil-maj' => '2026-04-19',
        'campeonato-interclubes' => '2026-04-25',
        'campeonato-campos-cortos-parejas' => '2026-05-01',
        'torneo-provincial-albacete' => '2026-05-01',
        'campeonato-absoluto-masculino' => '2026-05-08',
        'campeonato-absoluto-femenino' => '2026-05-09',
        'campeonato-campos-cortos' => '2026-05-09',
        'puntuable-ranking-juvenil-6' => '2026-05-16',
        'puntuable-zonal-2' => '2026-05-16',
        'jornada-3-liga-femenina' => '2026-05-17',
        'jornada-3-liga-senior-masculina' => '2026-05-17',
        'campeonato-parejas-pitch-putt' => '2026-05-23',
        'puntuable-ranking-juvenil-7' => '2026-05-30',
        'copa-federacion' => '2026-05-31',
        'puntuable-ranking-juvenil-8' => '2026-06-06',
        'jornada-4-liga-femenina' => '2026-06-07',
        'jornada-4-liga-senior-masculina' => '2026-06-07',
        'puntuable-ranking-juvenil-9' => '2026-06-13',
        'campeonato-senior-masculino-femenino' => '2026-06-13',
        'campeonato-match-play-pitch-putt' => '2026-06-20',
        'torneo-provincial-cuenca' => '2026-06-27',
    );

    foreach ( $torneo_dates as $slug => $fecha_orden ) {
        $posts = get_posts( array(
            'post_type'   => 'torneo',
            'name'        => $slug,
            'post_status' => 'any',
            'numberposts' => 1,
        ) );
        if ( ! empty( $posts ) ) {
            update_post_meta( $posts[0]->ID, '_torneo_fecha_orden', $fecha_orden );
        }
    }
    update_option( 'fgclm_v071_torneo_dates_migrated_v2', 1 );
}
add_action( 'init', 'fgclm_v071_migrate_torneo_dates' );

// Add custom columns to Torneos admin list
function fgclm_v071_torneo_columns( $columns ) {
    $columns['torneo_fecha'] = 'Fecha';
    $columns['torneo_sede']  = 'Sede / Campo';
    $columns['torneo_mes']   = 'Mes';
    $columns['torneo_fecha_orden'] = 'Fecha Orden';
    return $columns;
}
add_filter( 'manage_torneo_posts_columns', 'fgclm_v071_torneo_columns' );

function fgclm_v071_torneo_custom_column( $column, $post_id ) {
    switch ( $column ) {
        case 'torneo_fecha':
            echo esc_html( get_post_meta( $post_id, '_torneo_fecha', true ) );
            break;
        case 'torneo_sede':
            echo esc_html( get_post_meta( $post_id, '_torneo_sede', true ) );
            break;
        case 'torneo_mes':
            echo esc_html( ucfirst( get_post_meta( $post_id, '_torneo_mes', true ) ) );
            break;
        case 'torneo_fecha_orden':
            echo esc_html( get_post_meta( $post_id, '_torneo_fecha_orden', true ) );
            break;
    }
}
add_action( 'manage_torneo_posts_custom_column', 'fgclm_v071_torneo_custom_column', 10, 2 );

function fgclm_v071_torneo_sortable_columns( $columns ) {
    $columns['torneo_fecha_orden'] = 'torneo_fecha_orden';
    $columns['torneo_mes'] = 'torneo_mes';
    return $columns;
}
add_filter( 'manage_edit-torneo_sortable_columns', 'fgclm_v071_torneo_sortable_columns' );

function fgclm_v071_torneo_orderby( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( 'torneo_fecha_orden' === $query->get( 'orderby' ) ) {
        $query->set( 'meta_key', '_torneo_fecha_orden' );
        $query->set( 'orderby', 'meta_value' );
    }
    if ( 'torneo_mes' === $query->get( 'orderby' ) ) {
        $query->set( 'meta_key', '_torneo_mes' );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'fgclm_v071_torneo_orderby' );


// ─────────────────────────────────────────────────────────────
// 8. TORNEO META BOXES (Admin UI for tournament fields)
// ─────────────────────────────────────────────────────────────
function fgclm_v071_torneo_meta_boxes() {
    add_meta_box(
        'fgclm_v071_torneo_info',
        'Información del Torneo',
        'fgclm_v071_torneo_meta_box_html',
        'torneo',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'fgclm_v071_torneo_meta_boxes' );

function fgclm_v071_torneo_meta_box_html( $post ) {
    wp_nonce_field( 'fgclm_v071_torneo_meta', 'fgclm_v071_torneo_nonce' );
    $fecha            = get_post_meta( $post->ID, '_torneo_fecha', true );
    $sede             = get_post_meta( $post->ID, '_torneo_sede',  true );
    $mes              = get_post_meta( $post->ID, '_torneo_mes',   true );
    $fecha_orden      = get_post_meta( $post->ID, '_torneo_fecha_orden', true );
    $enlace_externo   = get_post_meta( $post->ID, '_torneo_enlace_externo', true );
    $es_wagr          = get_post_meta( $post->ID, '_torneo_es_wagr', true );
    $es_inscripcion   = get_post_meta( $post->ID, '_torneo_es_inscripcion', true );
    $link_inscripcion = get_post_meta( $post->ID, '_torneo_link_inscripcion', true );
    ?>
    <p>
        <label for="torneo_fecha"><strong>Fecha del Torneo (Texto):</strong></label><br>
        <input type="text" id="torneo_fecha" name="torneo_fecha" value="<?php echo esc_attr( $fecha ); ?>" class="regular-text" placeholder="Ej: 14 - 15 de febrero de 2026">
        <span class="description" style="display:block; margin-top:4px;">El texto que se mostrará en el listado.</span>
    </p>
    <p>
        <label for="torneo_fecha_orden"><strong>Fecha para Ordenar (Cronológica):</strong></label><br>
        <input type="date" id="torneo_fecha_orden" name="torneo_fecha_orden" value="<?php echo esc_attr( $fecha_orden ); ?>" class="regular-text">
        <span class="description" style="display:block; margin-top:4px;">Se utiliza para ordenar cronológicamente los torneos en el calendario.</span>
    </p>
    <p>
        <label for="torneo_sede"><strong>Sede / Campo:</strong></label><br>
        <input type="text" id="torneo_sede" name="torneo_sede" value="<?php echo esc_attr( $sede ); ?>" class="regular-text" placeholder="Ej: Palomarejos y Layos">
    </p>
    <p>
        <label for="torneo_mes"><strong>Mes:</strong></label><br>
        <select id="torneo_mes" name="torneo_mes">
            <?php
            $meses = array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
            foreach ( $meses as $m ) {
                printf( '<option value="%s"%s>%s</option>', $m, selected( $mes, $m, false ), ucfirst( $m ) );
            }
            ?>
        </select>
    </p>
    <p>
        <label for="torneo_enlace_externo"><strong>Enlace Externo Alternativo (Opcional):</strong></label><br>
        <input type="url" id="torneo_enlace_externo" name="torneo_enlace_externo" value="<?php echo esc_url( $enlace_externo ); ?>" class="regular-text" placeholder="https://ejemplo.com/circular.pdf">
        <span class="description" style="display:block; margin-top:4px;">Si se introduce, el enlace del torneo irá a esta URL en lugar de a la página interna del torneo.</span>
    </p>
    <p>
        <label>
            <input type="checkbox" name="torneo_es_wagr" value="yes" <?php checked( $es_wagr, 'yes' ); ?>>
            <strong>Puntuable para WAGR (World Amateur Golf Ranking)</strong>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" id="torneo_es_inscripcion" name="torneo_es_inscripcion" value="yes" <?php checked( $es_inscripcion, 'yes' ); ?>>
            <strong>Habilitar Botón de Inscripción</strong>
        </label>
    </p>
    <p id="torneo_link_inscripcion_container">
        <label for="torneo_link_inscripcion"><strong>Enlace de Inscripción Personalizado (Opcional):</strong></label><br>
        <input type="url" id="torneo_link_inscripcion" name="torneo_link_inscripcion" value="<?php echo esc_url( $link_inscripcion ); ?>" class="regular-text" placeholder="https://ejemplo.com/inscripcion">
        <span class="description" style="display:block; margin-top:4px;">Si está vacío y la inscripción está habilitada, usará el enlace del propio torneo (o enlace externo alternativo).</span>
    </p>
    <?php
    $selected_noticia = get_post_meta( $post->ID, '_torneo_noticia_relacionada', true );
    $news_posts = get_posts( array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ) );
    ?>
    <p>
        <label for="torneo_noticia_relacionada"><strong>Noticia Relacionada (Opcional):</strong></label><br>
        <select id="torneo_noticia_relacionada" name="torneo_noticia_relacionada" style="width:100%; max-width:400px; margin-top:5px;">
            <option value="">-- Seleccionar Noticia Relacionada --</option>
            <?php foreach ( $news_posts as $news ) : ?>
                <option value="<?php echo esc_attr( $news->ID ); ?>" <?php selected( $selected_noticia, $news->ID ); ?>>
                    <?php echo esc_html( get_the_title( $news ) ); ?> (<?php echo esc_html( get_the_date( 'Y-m-d', $news ) ); ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <span class="description" style="display:block; margin-top:4px;">Si se selecciona, se mostrará un botón "Noticia" en el calendario que redirigirá a esta noticia.</span>
    </p>
    <?php
}

function fgclm_v071_save_torneo_meta( $post_id ) {
    if ( ! isset( $_POST['fgclm_v071_torneo_nonce'] ) || ! wp_verify_nonce( $_POST['fgclm_v071_torneo_nonce'], 'fgclm_v071_torneo_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['torneo_fecha'] ) ) {
        update_post_meta( $post_id, '_torneo_fecha', sanitize_text_field( $_POST['torneo_fecha'] ) );
    }
    if ( isset( $_POST['torneo_fecha_orden'] ) ) {
        update_post_meta( $post_id, '_torneo_fecha_orden', sanitize_text_field( $_POST['torneo_fecha_orden'] ) );
    }
    if ( isset( $_POST['torneo_sede'] ) ) {
        update_post_meta( $post_id, '_torneo_sede', sanitize_text_field( $_POST['torneo_sede'] ) );
    }
    if ( isset( $_POST['torneo_mes'] ) ) {
        update_post_meta( $post_id, '_torneo_mes', sanitize_text_field( $_POST['torneo_mes'] ) );
    }
    if ( isset( $_POST['torneo_enlace_externo'] ) ) {
        update_post_meta( $post_id, '_torneo_enlace_externo', esc_url_raw( $_POST['torneo_enlace_externo'] ) );
    }
    
    $es_wagr = isset( $_POST['torneo_es_wagr'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_torneo_es_wagr', $es_wagr );

    $es_inscripcion = isset( $_POST['torneo_es_inscripcion'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_torneo_es_inscripcion', $es_inscripcion );

    if ( isset( $_POST['torneo_link_inscripcion'] ) ) {
        update_post_meta( $post_id, '_torneo_link_inscripcion', esc_url_raw( $_POST['torneo_link_inscripcion'] ) );
    }
    if ( isset( $_POST['torneo_noticia_relacionada'] ) ) {
        update_post_meta( $post_id, '_torneo_noticia_relacionada', sanitize_text_field( $_POST['torneo_noticia_relacionada'] ) );
    }
}
add_action( 'save_post_torneo', 'fgclm_v071_save_torneo_meta' );


// ─────────────────────────────────────────────────────────────
// 9. PERMALINK STRUCTURE (ensure pretty permalinks)
// ─────────────────────────────────────────────────────────────
function fgclm_v071_set_permalink_structure() {
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );
    $wp_rewrite->flush_rules();
}
add_action( 'after_switch_theme', 'fgclm_v071_set_permalink_structure' );

/**
 * Ensure pretty permalinks are enabled on init (guarded).
 */
function fgclm_v071_set_permalink_structure_on_init() {
    if ( ! get_option( 'fgclm_v071_permalink_set_v2_1' ) ) {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( '/%postname%/' );
        $wp_rewrite->flush_rules();
        update_option( 'fgclm_v071_permalink_set_v2_1', 1 );
    }
}
add_action( 'init', 'fgclm_v071_set_permalink_structure_on_init' );

/**
 * Add custom body classes for specific templates.
 */
function fgclm_v071_body_classes( $classes ) {
    if ( is_page_template( 'page-competiciones.php' ) ) {
        $classes[] = 'competiciones-page-body';
    }
    if ( is_page_template( 'page-clubes.php' ) ) {
        $classes[] = 'clubes-page-body';
    }
    return $classes;
}
add_filter( 'body_class', 'fgclm_v071_body_classes' );

/**
 * Convierte un formato de fecha larga en español a un formato corto (ej: "14-15 feb")
 * para su uso en el calendario de competiciones.
 */
function fgclm_format_short_date( $date_str ) {
    $date_str = trim( $date_str );
    if ( empty( $date_str ) ) {
        return '';
    }
    
    $meses_short = array(
        'enero'      => 'ene',
        'febrero'    => 'feb',
        'marzo'      => 'mar',
        'abril'      => 'abr',
        'mayo'       => 'may',
        'junio'      => 'jun',
        'julio'      => 'jul',
        'agosto'     => 'ago',
        'septiembre' => 'sep',
        'octubre'    => 'oct',
        'noviembre'  => 'nov',
        'diciembre'  => 'dic'
    );
    
    // Formato 1: rango de días con "o" / "ó" -> "1 o 2 de mayo de 2026"
    if ( preg_match( '/^(\d+)\s+(?:o|ó)\s+(\d+)\s+de\s+(\w+)(?:\s+de\s+\d+)?$/iu', $date_str, $matches ) ) {
        $mes = strtolower( $matches[3] );
        $mes_s = isset( $meses_short[$mes] ) ? $meses_short[$mes] : substr( $mes, 0, 3 );
        return $matches[1] . ' ó ' . $matches[2] . ' ' . $mes_s;
    }
    
    // Formato 2: rango de días con "-" / "–" / "—" -> "14 - 15 de febrero de 2026"
    if ( preg_match( '/^(\d+)\s*[-–—]\s*(\d+)\s+de\s+(\w+)(?:\s+de\s+\d+)?$/iu', $date_str, $matches ) ) {
        $mes = strtolower( $matches[3] );
        $mes_s = isset( $meses_short[$mes] ) ? $meses_short[$mes] : substr( $mes, 0, 3 );
        return $matches[1] . '-' . $matches[2] . ' ' . $mes_s;
    }
    
    // Formato 3: día único -> "1 de mayo de 2026" o "16 de mayo de 2026"
    if ( preg_match( '/^(\d+)\s+de\s+(\w+)(?:\s+de\s+\d+)?$/iu', $date_str, $matches ) ) {
        $mes = strtolower( $matches[2] );
        $mes_s = isset( $meses_short[$mes] ) ? $meses_short[$mes] : substr( $mes, 0, 3 );
        return $matches[1] . ' ' . $mes_s;
    }
    
    // Fallback por si ya viene en formato corto o diferente
    return $date_str;
}

// ─────────────────────────────────────────────────────────────
// 10. EN VIVO PAGE META BOX (Admin UI to update live tournament link weekly)
// ─────────────────────────────────────────────────────────────
function fgclm_v071_en_vivo_meta_box() {
    add_meta_box(
        'fgclm_v071_en_vivo_link_box',
        'Enlace En Vivo de la Semana',
        'fgclm_v071_en_vivo_meta_box_html',
        'page',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'fgclm_v071_en_vivo_meta_box' );

function fgclm_v071_en_vivo_meta_box_html( $post ) {
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( 'page-en-vivo.php' !== $template && 'page-enlace-en-vivo.php' !== $template && $post->post_name !== 'en-vivo' && $post->post_name !== 'enlace-en-vivo' ) {
        echo '<p>Este panel de enlace en vivo solo está disponible para la página de "En vivo".</p>';
        return;
    }
    wp_nonce_field( 'fgclm_v071_en_vivo_meta', 'fgclm_v071_en_vivo_nonce' );
    $enlace = get_post_meta( $post->ID, '_en_vivo_link', true );
    if ( empty( $enlace ) ) {
        $enlace = 'https://torneos.fgclmgolf.com'; // Default fallback
    }
    $enlace_boton = get_post_meta( $post->ID, '_en_vivo_button_link', true );
    $multitorneos = get_post_meta( $post->ID, '_en_vivo_multitorneos', true );
    ?>
    <p>
        <label for="en_vivo_link"><strong>Enlace del Iframe (Clasificación embebida):</strong></label><br>
        <input type="url" id="en_vivo_link" name="en_vivo_link" value="<?php echo esc_url( $enlace ); ?>" style="width:100%; margin-top: 5px;" placeholder="https://torneos.fgclmgolf.com/...">
        <span class="description" style="display:block; margin-top:8px;">URL que se carga dentro del iframe de la página. Si se deja vacío, se utilizará el valor predeterminado (https://torneos.fgclmgolf.com).</span>
    </p>
    <p>
        <label for="en_vivo_button_link"><strong>Enlace del Botón "Ver Clasificación en Directo":</strong></label><br>
        <input type="url" id="en_vivo_button_link" name="en_vivo_button_link" value="<?php echo esc_url( $enlace_boton ); ?>" style="width:100%; margin-top: 5px;" placeholder="https://torneos.fgclmgolf.com/clasificacion">
        <span class="description" style="display:block; margin-top:8px;">URL a la que lleva el botón "Ver Clasificación en Directo". Si se deja vacío, usará el mismo enlace del iframe.</span>
    </p>
    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ccc;">
    <p>
        <label for="en_vivo_multitorneos"><strong>Múltiples Torneos en Vivo (Opcional):</strong></label><br>
        <textarea id="en_vivo_multitorneos" name="en_vivo_multitorneos" rows="6" style="width:100%; margin-top: 5px; font-family: monospace; font-size: 11px;" placeholder="Nombre Torneo 1 | URL Iframe 1 | URL Botón 1&#10;Nombre Torneo 2 | URL Iframe 2 | URL Botón 2"><?php echo esc_textarea( $multitorneos ); ?></textarea>
        <span class="description" style="display:block; margin-top:8px;">
            Introduce <strong>un torneo por línea</strong> con el formato:<br>
            <code>Nombre | Enlace Iframe | Enlace Botón Directo</code><br><br>
            Ejemplo:<br>
            <code>Absoluto Masculino | https://torneos.fgclmgolf.com/1 | https://torneos.fgclmgolf.com/1/directo</code><br>
            <code>Absoluto Femenino | https://torneos.fgclmgolf.com/2 | https://torneos.fgclmgolf.com/2/directo</code>
        </span>
    </p>
    <?php
}

function fgclm_v071_save_en_vivo_meta( $post_id ) {
    if ( ! isset( $_POST['fgclm_v071_en_vivo_nonce'] ) || ! wp_verify_nonce( $_POST['fgclm_v071_en_vivo_nonce'], 'fgclm_v071_en_vivo_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['en_vivo_link'] ) ) {
        update_post_meta( $post_id, '_en_vivo_link', esc_url_raw( $_POST['en_vivo_link'] ) );
    }
    if ( isset( $_POST['en_vivo_button_link'] ) ) {
        update_post_meta( $post_id, '_en_vivo_button_link', esc_url_raw( $_POST['en_vivo_button_link'] ) );
    }
    if ( isset( $_POST['en_vivo_multitorneos'] ) ) {
        update_post_meta( $post_id, '_en_vivo_multitorneos', sanitize_textarea_field( $_POST['en_vivo_multitorneos'] ) );
    }
}
add_action( 'save_post_page', 'fgclm_v071_save_en_vivo_meta' );


// ─────────────────────────────────────────────────────────────
// 11. CUSTOM POST TYPE: TRANSPARENCIA (for transparency documents)
// ─────────────────────────────────────────────────────────────
function fgclm_v31_register_transparencia_cpt() {
    register_post_type( 'transparencia_doc', array(
        'labels' => array(
            'name'               => 'Transparencia',
            'singular_name'      => 'Documento',
            'add_new'            => 'Añadir Documento',
            'add_new_item'       => 'Añadir Nuevo Documento',
            'edit_item'          => 'Editar Documento',
            'view_item'          => 'Ver Documento',
            'all_items'          => 'Todos los Documentos',
            'search_items'       => 'Buscar Documentos',
            'not_found'          => 'No se encontraron documentos.',
        ),
        'public'             => true,
        'publicly_queryable' => false,
        'exclude_from_search'=> true,
        'has_archive'        => false,
        'supports'           => array( 'title' ),
        'menu_icon'          => 'dashicons-media-document',
        'show_in_rest'       => true,
    ) );
}
add_action( 'init', 'fgclm_v31_register_transparencia_cpt' );

// Enqueue media scripts for Transparencia and Circulares CPT edit screen
function fgclm_v31_enqueue_transp_admin_scripts( $hook ) {
    global $post_type;
    if ( 'transparencia_doc' === $post_type || 'circular_doc' === $post_type ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'fgclm_v31_enqueue_transp_admin_scripts' );

// Add Meta Box for Transparencia Doc details
function fgclm_v31_add_transp_meta_box() {
    add_meta_box(
        'fgclm_transp_info',
        'Datos del Documento de Transparencia',
        'fgclm_v31_transp_meta_box_html',
        'transparencia_doc',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'fgclm_v31_add_transp_meta_box' );

function fgclm_v31_transp_meta_box_html( $post ) {
    wp_nonce_field( 'fgclm_transp_meta', 'fgclm_transp_nonce' );
    $year     = get_post_meta( $post->ID, '_transp_doc_year', true );
    if ( empty( $year ) ) {
        $year = date( 'Y' );
    }
    $pdf_file = get_post_meta( $post->ID, '_transp_pdf_file', true );
    $icon     = get_post_meta( $post->ID, '_transp_doc_icon', true );
    if ( empty( $icon ) ) {
        $icon = 'archivo';
    }
    ?>
    <p>
        <label for="transp_doc_year"><strong>Año / Ejercicio:</strong></label><br>
        <input type="text" id="transp_doc_year" name="transp_doc_year" value="<?php echo esc_attr( $year ); ?>" class="regular-text" style="width:120px; margin-top:5px;" placeholder="Ej: 2026">
        <span class="description" style="display:block; margin-top:4px;">El año en el que se agrupará el documento.</span>
    </p>
    <p>
        <label for="transp_pdf_file"><strong>Archivo PDF:</strong></label><br>
        <input type="text" id="transp_pdf_file" name="transp_pdf_file" value="<?php echo esc_url( $pdf_file ); ?>" class="regular-text" style="width:75%; margin-top:5px;" placeholder="https://...">
        <button type="button" class="button" id="transp_pdf_upload_btn" style="margin-top:4px;">Subir / Seleccionar PDF</button>
        <span class="description" style="display:block; margin-top:4px;">Haz clic en el botón para subir un PDF o seleccionar uno de la biblioteca de medios.</span>
    </p>
    <p>
        <label for="transp_doc_icon"><strong>Icono del Documento:</strong></label><br>
        <select id="transp_doc_icon" name="transp_doc_icon" style="width:200px; margin-top:5px;">
            <option value="dolar" <?php selected( $icon, 'dolar' ); ?>>Dólar / Presupuesto</option>
            <option value="usuarios" <?php selected( $icon, 'usuarios' ); ?>>Usuarios / Censo</option>
            <option value="archivo" <?php selected( $icon, 'archivo' ); ?>>Archivo / Balance</option>
            <option value="actividad" <?php selected( $icon, 'actividad' ); ?>>Actividad / Ejecución</option>
        </select>
        <span class="description" style="display:block; margin-top:4px;">El icono que se mostrará junto al documento en la página.</span>
    </p>

    <script>
    jQuery(document).ready(function($){
        $('#transp_pdf_upload_btn').click(function(e) {
            e.preventDefault();
            var custom_uploader = wp.media({
                title: 'Seleccionar PDF de Transparencia',
                button: {
                    text: 'Usar este archivo'
                },
                multiple: false
            }).on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#transp_pdf_file').val(attachment.url);
            }).open();
        });
    });
    </script>
    <?php
}

function fgclm_v31_save_transp_meta( $post_id ) {
    if ( ! isset( $_POST['fgclm_transp_nonce'] ) || ! wp_verify_nonce( $_POST['fgclm_transp_nonce'], 'fgclm_transp_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['transp_doc_year'] ) ) {
        update_post_meta( $post_id, '_transp_doc_year', sanitize_text_field( $_POST['transp_doc_year'] ) );
    }
    if ( isset( $_POST['transp_pdf_file'] ) ) {
        update_post_meta( $post_id, '_transp_pdf_file', esc_url_raw( $_POST['transp_pdf_file'] ) );
    }
    if ( isset( $_POST['transp_doc_icon'] ) ) {
        update_post_meta( $post_id, '_transp_doc_icon', sanitize_text_field( $_POST['transp_doc_icon'] ) );
    }
}
add_action( 'save_post_transparencia_doc', 'fgclm_v31_save_transp_meta' );


// ─────────────────────────────────────────────────────────────
// 12. CUSTOM POST TYPE: CIRCULARES (for dynamic circular uploads)
// ─────────────────────────────────────────────────────────────
function fgclm_v32_register_circular_cpt() {
    register_post_type( 'circular_doc', array(
        'labels' => array(
            'name'               => 'Circulares',
            'singular_name'      => 'Circular',
            'add_new'            => 'Añadir Circular',
            'add_new_item'       => 'Añadir Nueva Circular',
            'edit_item'          => 'Editar Circular',
            'view_item'          => 'Ver Circular',
            'all_items'          => 'Todas las Circulares',
            'search_items'       => 'Buscar Circulares',
            'not_found'          => 'No se encontraron circulares.',
        ),
        'public'             => true,
        'publicly_queryable' => false,
        'exclude_from_search'=> true,
        'has_archive'        => false,
        'supports'           => array( 'title' ),
        'menu_icon'          => 'dashicons-text-page',
        'show_in_rest'       => true,
    ) );
}
add_action( 'init', 'fgclm_v32_register_circular_cpt' );

// Add Meta Box for Circular Doc details
function fgclm_v32_add_circular_meta_box() {
    add_meta_box(
        'fgclm_circular_info',
        'Datos del Documento de Circular',
        'fgclm_v32_circular_meta_box_html',
        'circular_doc',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'fgclm_v32_add_circular_meta_box' );

function fgclm_v32_circular_meta_box_html( $post ) {
    wp_nonce_field( 'fgclm_circular_meta', 'fgclm_circular_nonce' );
    $year       = get_post_meta( $post->ID, '_circular_year', true );
    if ( empty( $year ) ) {
        $year = date( 'Y' );
    }
    $pdf_file   = get_post_meta( $post->ID, '_circular_pdf', true );
    $type       = get_post_meta( $post->ID, '_circular_type', true );
    if ( empty( $type ) ) {
        $type = 'fgclm';
    }
    $badge_text = get_post_meta( $post->ID, '_circular_badge_text', true );
    $is_new     = get_post_meta( $post->ID, '_circular_is_new', true );
    $subtitle   = get_post_meta( $post->ID, '_circular_subtitle', true );
    ?>
    <p>
        <label for="circular_year"><strong>Año:</strong></label><br>
        <input type="text" id="circular_year" name="circular_year" value="<?php echo esc_attr( $year ); ?>" class="regular-text" style="width:120px; margin-top:5px;" placeholder="Ej: 2026">
        <span class="description" style="display:block; margin-top:4px;">El año en el que se agrupará la circular.</span>
    </p>
    <p>
        <label for="circular_pdf"><strong>Archivo PDF:</strong></label><br>
        <input type="text" id="circular_pdf" name="circular_pdf" value="<?php echo esc_url( $pdf_file ); ?>" class="regular-text" style="width:75%; margin-top:5px;" placeholder="https://...">
        <button type="button" class="button" id="circular_pdf_upload_btn" style="margin-top:4px;">Subir / Seleccionar PDF</button>
        <span class="description" style="display:block; margin-top:4px;">Haz clic en el botón para subir un PDF o seleccionar uno de la biblioteca de medios.</span>
    </p>
    <p>
        <label for="circular_type"><strong>Tipo de Circular:</strong></label><br>
        <select id="circular_type" name="circular_type" style="width:250px; margin-top:5px;">
            <option value="rfeg" <?php selected( $type, 'rfeg' ); ?>>Circular RFEG (Tab RFEG)</option>
            <option value="fgclm" <?php selected( $type, 'fgclm' ); ?>>Circular FGCLM (Tab FGCLM / Listado General)</option>
            <option value="locales" <?php selected( $type, 'locales' ); ?>>Reglas Locales (Tab Reglas Locales)</option>
        </select>
        <span class="description" style="display:block; margin-top:4px;">Define en qué pestaña y bajo qué icono se mostrará el documento.</span>
    </p>
    <p>
        <label for="circular_badge_text"><strong>Texto del Badge / Etiqueta (Opcional):</strong></label><br>
        <input type="text" id="circular_badge_text" name="circular_badge_text" value="<?php echo esc_attr( $badge_text ); ?>" class="regular-text" style="width:200px; margin-top:5px;" placeholder="Ej: Nueva, Vigente, Campo">
        <span class="description" style="display:block; margin-top:4px;">Texto para mostrar en la etiqueta (ej: "Nueva"). Déjalo vacío para no mostrar etiqueta o mostrar el año.</span>
    </p>
    <p>
        <label>
            <input type="checkbox" name="circular_is_new" value="yes" <?php checked( $is_new, 'yes' ); ?>>
            <strong>Destacar Etiqueta en Dorado (Clase 'nueva')</strong>
        </label>
        <span class="description" style="display:block; margin-top:4px;">Si se activa, el badge tendrá fondo dorado/amarillo en lugar del verde estándar.</span>
    </p>
    <p>
        <label for="circular_subtitle"><strong>Subtítulo / Metadatos personalizado (Opcional):</strong></label><br>
        <input type="text" id="circular_subtitle" name="circular_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="regular-text" style="width:100%; margin-top:5px;" placeholder="Ej: Publicada: enero 2026 · FGCLM">
        <span class="description" style="display:block; margin-top:4px;">Texto secundario que se muestra bajo el título. Si se deja vacío se generará un texto por defecto según el tipo.</span>
    </p>

    <script>
    jQuery(document).ready(function($){
        $('#circular_pdf_upload_btn').click(function(e) {
            e.preventDefault();
            var custom_uploader = wp.media({
                title: 'Seleccionar PDF de Circular',
                button: {
                    text: 'Usar este archivo'
                },
                multiple: false
            }).on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#circular_pdf').val(attachment.url);
            }).open();
        });
    });
    </script>
    <?php
}

function fgclm_v32_save_circular_meta( $post_id ) {
    if ( ! isset( $_POST['fgclm_circular_nonce'] ) || ! wp_verify_nonce( $_POST['fgclm_circular_nonce'], 'fgclm_circular_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['circular_year'] ) ) {
        update_post_meta( $post_id, '_circular_year', sanitize_text_field( $_POST['circular_year'] ) );
    }
    if ( isset( $_POST['circular_pdf'] ) ) {
        update_post_meta( $post_id, '_circular_pdf', esc_url_raw( $_POST['circular_pdf'] ) );
    }
    if ( isset( $_POST['circular_type'] ) ) {
        update_post_meta( $post_id, '_circular_type', sanitize_text_field( $_POST['circular_type'] ) );
    }
    if ( isset( $_POST['circular_badge_text'] ) ) {
        update_post_meta( $post_id, '_circular_badge_text', sanitize_text_field( $_POST['circular_badge_text'] ) );
    }
    
    $is_new = isset( $_POST['circular_is_new'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_circular_is_new', $is_new );

    if ( isset( $_POST['circular_subtitle'] ) ) {
        update_post_meta( $post_id, '_circular_subtitle', sanitize_text_field( $_POST['circular_subtitle'] ) );
    }
}
add_action( 'save_post_circular_doc', 'fgclm_v32_save_circular_meta' );

// ─────────────────────────────────────────────────────────────
// 13. ESCUELA DE GOLF SETTINGS (Admin UI to update year & links easily)
// ─────────────────────────────────────────────────────────────
function fgclm_v32_add_escuela_meta_box() {
    add_meta_box(
        'fgclm_escuela_info',
        'Configuración de la Escuela de Golf',
        'fgclm_v32_escuela_meta_box_html',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'fgclm_v32_add_escuela_meta_box' );

function fgclm_v32_escuela_meta_box_html( $post ) {
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( 'page-escuela.php' !== $template && $post->post_name !== 'escuela' ) {
        echo '<p>Este panel solo está disponible para la página que utiliza la plantilla de la Escuela de Golf.</p>';
        return;
    }
    wp_nonce_field( 'fgclm_escuela_meta', 'fgclm_escuela_nonce' );
    
    $escuela_year   = get_post_meta( $post->ID, '_escuela_year', true );
    if ( empty( $escuela_year ) ) {
        $escuela_year = '2026';
    }
    $pdf_tarifas    = get_post_meta( $post->ID, '_escuela_pdf_tarifas', true );
    $pdf_condic     = get_post_meta( $post->ID, '_escuela_pdf_condiciones', true );
    $pdf_cal_t1     = get_post_meta( $post->ID, '_escuela_pdf_calendario_t1', true );
    $pdf_cal_t2     = get_post_meta( $post->ID, '_escuela_pdf_calendario_t2', true );
    $form_juvenil   = get_post_meta( $post->ID, '_escuela_form_juvenil', true );
    $form_adultos   = get_post_meta( $post->ID, '_escuela_form_adultos', true );
    ?>
    <p>
        <label for="escuela_year"><strong>Año Activo de la Escuela:</strong></label><br>
        <input type="text" id="escuela_year" name="escuela_year" value="<?php echo esc_attr( $escuela_year ); ?>" class="regular-text" style="width:120px;" placeholder="Ej: 2026">
        <span class="description" style="display:block; margin-top:4px;">El año que se mostrará en los títulos del formulario y documentación (ej: "Escuela Juvenil FGCLM 2026").</span>
    </p>
    <hr>
    <h4>Documentación en PDF:</h4>
    <p>
        <label for="escuela_pdf_tarifas"><strong>PDF Tarifas y Servicios:</strong></label><br>
        <input type="text" id="escuela_pdf_tarifas" name="escuela_pdf_tarifas" value="<?php echo esc_url( $pdf_tarifas ); ?>" class="regular-text" style="width:80%;" placeholder="https://...">
        <button type="button" class="button escuela-upload-btn" data-target="escuela_pdf_tarifas">Seleccionar</button>
    </p>
    <p>
        <label for="escuela_pdf_condiciones"><strong>PDF Términos y Condiciones:</strong></label><br>
        <input type="text" id="escuela_pdf_condiciones" name="escuela_pdf_condiciones" value="<?php echo esc_url( $pdf_condic ); ?>" class="regular-text" style="width:80%;" placeholder="https://...">
        <button type="button" class="button escuela-upload-btn" data-target="escuela_pdf_condiciones">Seleccionar</button>
    </p>
    <p>
        <label for="escuela_pdf_calendario_t1"><strong>PDF Calendario clases Trimestre 1 (Ene-Feb-Mar):</strong></label><br>
        <input type="text" id="escuela_pdf_calendario_t1" name="escuela_pdf_calendario_t1" value="<?php echo esc_url( $pdf_cal_t1 ); ?>" class="regular-text" style="width:80%;" placeholder="https://...">
        <button type="button" class="button escuela-upload-btn" data-target="escuela_pdf_calendario_t1">Seleccionar</button>
    </p>
    <p>
        <label for="escuela_pdf_calendario_t2"><strong>PDF Calendario clases Trimestre 2 (Abr-May-Jun):</strong></label><br>
        <input type="text" id="escuela_pdf_calendario_t2" name="escuela_pdf_calendario_t2" value="<?php echo esc_url( $pdf_cal_t2 ); ?>" class="regular-text" style="width:80%;" placeholder="https://...">
        <button type="button" class="button escuela-upload-btn" data-target="escuela_pdf_calendario_t2">Seleccionar</button>
    </p>
    <hr>
    <h4>Enlaces a Formularios de Google:</h4>
    <p>
        <label for="escuela_form_juvenil"><strong>Enlace Formulario Escuela Juvenil:</strong></label><br>
        <input type="url" id="escuela_form_juvenil" name="escuela_form_juvenil" value="<?php echo esc_url( $form_juvenil ); ?>" class="regular-text" style="width:100%;" placeholder="https://docs.google.com/forms/...">
    </p>
    <p>
        <label for="escuela_form_adultos"><strong>Enlace Formulario Escuela Adultos:</strong></label><br>
        <input type="url" id="escuela_form_adultos" name="escuela_form_adultos" value="<?php echo esc_url( $form_adultos ); ?>" class="regular-text" style="width:100%;" placeholder="https://docs.google.com/forms/...">
    </p>

    <script>
    jQuery(document).ready(function($){
        $('.escuela-upload-btn').click(function(e) {
            e.preventDefault();
            var target_id = $(this).data('target');
            var custom_uploader = wp.media({
                title: 'Seleccionar PDF para la Escuela',
                button: {
                    text: 'Usar este archivo'
                },
                multiple: false
            }).on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#' + target_id).val(attachment.url);
            }).open();
        });
    });
    </script>
    <?php
}

function fgclm_v32_save_escuela_meta( $post_id ) {
    if ( ! isset( $_POST['fgclm_escuela_nonce'] ) || ! wp_verify_nonce( $_POST['fgclm_escuela_nonce'], 'fgclm_escuela_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['escuela_year'] ) ) {
        update_post_meta( $post_id, '_escuela_year', sanitize_text_field( $_POST['escuela_year'] ) );
    }
    if ( isset( $_POST['escuela_pdf_tarifas'] ) ) {
        update_post_meta( $post_id, '_escuela_pdf_tarifas', esc_url_raw( $_POST['escuela_pdf_tarifas'] ) );
    }
    if ( isset( $_POST['escuela_pdf_condiciones'] ) ) {
        update_post_meta( $post_id, '_escuela_pdf_condiciones', esc_url_raw( $_POST['escuela_pdf_condiciones'] ) );
    }
    if ( isset( $_POST['escuela_pdf_calendario_t1'] ) ) {
        update_post_meta( $post_id, '_escuela_pdf_calendario_t1', esc_url_raw( $_POST['escuela_pdf_calendario_t1'] ) );
    }
    if ( isset( $_POST['escuela_pdf_calendario_t2'] ) ) {
        update_post_meta( $post_id, '_escuela_pdf_calendario_t2', esc_url_raw( $_POST['escuela_pdf_calendario_t2'] ) );
    }
    if ( isset( $_POST['escuela_form_juvenil'] ) ) {
        update_post_meta( $post_id, '_escuela_form_juvenil', esc_url_raw( $_POST['escuela_form_juvenil'] ) );
    }
    if ( isset( $_POST['escuela_form_adultos'] ) ) {
        update_post_meta( $post_id, '_escuela_form_adultos', esc_url_raw( $_POST['escuela_form_adultos'] ) );
    }
}
add_action( 'save_post_page', 'fgclm_v32_save_escuela_meta' );

// Enqueue media scripts for page templates that use Escuela meta boxes
function fgclm_v32_enqueue_escuela_admin_scripts( $hook ) {
    global $post_type;
    if ( 'page' === $post_type ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'fgclm_v32_enqueue_escuela_admin_scripts' );

// Helper function to return SVG for Circulares based on type and context
function fgclm_get_circular_icon_svg( $type, $context = 'main' ) {
    if ( $context === 'fgclm_page' ) {
        // SVG used in page-circulares-fgclm.php (File with circles)
        return '<svg viewBox="0 0 64 64" width="20" height="20" fill="none" stroke="currentColor" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 8v48h36V24L34 8H14z M34 8v16h16 M30 34h12 M30 44h12" /><circle cx="22" cy="34" r="2.5" fill="currentColor" stroke="none" /><circle cx="22" cy="44" r="2.5" fill="currentColor" stroke="none" /></svg>';
    }

    // Default main landing page (page-circulares.php) context
    switch ( $type ) {
        case 'rfeg':
            // RFEG Document icon
            return '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>';
        case 'fgclm':
            // FGCLM Clipboard icon
            return '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>';
        case 'locales':
            // Reglas Locales Flag icon
            return '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>';
        default:
            return '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>';
    }
}

// Redirect single tournament view to its related news post if set
function fgclm_v32_redirect_torneo_to_noticia() {
    if ( is_singular( 'torneo' ) ) {
        $post_id = get_the_ID();
        $noticia_id = get_post_meta( $post_id, '_torneo_noticia_relacionada', true );
        if ( ! empty( $noticia_id ) ) {
            $noticia_link = get_permalink( $noticia_id );
            if ( $noticia_link ) {
                wp_redirect( $noticia_link, 302 );
                exit;
            }
        }
    }
}
add_action( 'template_redirect', 'fgclm_v32_redirect_torneo_to_noticia' );


