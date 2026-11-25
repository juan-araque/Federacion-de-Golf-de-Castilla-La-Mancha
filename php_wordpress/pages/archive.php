<?php
/**
 * The template for displaying archive pages
 *
 * @package FGCLM_Theme_v071
 */

get_header();

// Fetch categories for the filter select dropdown
$all_cats = get_categories( array(
    'hide_empty' => true,
) );

// Active filter states
$current_cat = get_query_var( 'cat' );
$current_search = get_search_query();
?>

<div class="page-hero">
  <div class="page-hero-content">
    <div class="page-hero-label">Actualidad FGCLM</div>
    <h1>Archivo de <em>Noticias</em></h1>
    <p>Noticias de la Federación de Golf de Castilla-La Mancha.</p>
  </div>
</div>

<div class="filtros-section">
  <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <!-- In WordPress, post list filter feeds query arguments to the main action URL -->
    <div class="filtros-row">
      <div class="buscador-container">
        <svg class="buscador-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" name="s" id="buscador" placeholder="Buscar noticias..." autocomplete="off" value="<?php echo esc_attr( $current_search ); ?>">
        <input type="hidden" name="post_type" value="post">
        <div id="buscadorResultados" class="autocomplete-dropdown"></div>
      </div>
      
      <select name="cat" id="filtroCat" onchange="this.form.submit();">
        <option value="">Todas las categorías</option>
        <?php foreach ( $all_cats as $cat ) : ?>
          <option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $current_cat, $cat->term_id ); ?>>
            <?php echo esc_html( $cat->name ); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </form>
</div>

<div class="noticias-main-grid">
  <div class="listado-section">
    <div class="listado-header">
      <div class="result-count" id="resultCount">
        <?php
        global $wp_query;
        $total_results = $wp_query->found_posts;
        if ( $total_results > 0 ) {
            echo sprintf( esc_html( _n( '%d noticia encontrada', '%d noticias en total', $total_results, 'fgclm-theme' ) ), $total_results );
        } else {
            echo esc_html__( 'Sin resultados', 'fgclm-theme' );
        }
        ?>
      </div>
    </div>

    <div id="noticiasGrid">
      <?php
      if ( have_posts() ) :
        // Define gradients for matching static tags
        $cat_gradients = array(
          'Masculino'            => 'linear-gradient(135deg,#0a3d2e,#2d9e6b)',
          'Femenino'             => 'linear-gradient(135deg,#3d0a1a,#9e2d5a)',
          'Masculino y Femenino' => 'linear-gradient(135deg,#0a2a3d,#2d7a9e)',
          'Senior'               => 'linear-gradient(135deg,#3d2a0a,#9e7a2d)',
          'Juvenil'              => 'linear-gradient(135deg,#1a3d0a,#5a9e2d)',
          'Profesional'          => 'linear-gradient(135deg,#1a0a3d,#5a2d9e)',
          'Pitch & Putt'         => 'linear-gradient(135deg,#2a1a0a,#7a5a2d)',
          'Federativas'          => 'linear-gradient(135deg,#0a1a3d,#2d4a9e)',
          'Golf Adaptado'        => 'linear-gradient(135deg,#0a3d3d,#2d9e9e)',
          'Reglas y Campos'      => 'linear-gradient(135deg,#2a2a0a,#7a7a2d)',
          'default'              => 'linear-gradient(135deg,#0a3d2e,#1a6645)',
        );

        while ( have_posts() ) :
          the_post();
          $categories = get_the_category();
          $cat_name = ! empty( $categories ) ? $categories[0]->name : 'default';
          $gradient = isset( $cat_gradients[ $cat_name ] ) ? $cat_gradients[ $cat_name ] : $cat_gradients['default'];
          ?>
          <a href="<?php the_permalink(); ?>" class="noticia-fila">
            <div class="noticia-fila-thumb" style="background:<?php echo esc_attr( $gradient ); ?>;">
              <?php if ( has_post_thumbnail() ) : ?>
                <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ); ?>" alt="" loading="lazy">
              <?php else : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo_pagina.png" class="img-fallback" alt="">
              <?php endif; ?>
            </div>
            <div class="noticia-fila-body">
              <div class="noticia-fila-meta">
                <?php if ( ! empty( $categories ) ) : ?>
                  <span class="noticia-cat"><?php echo esc_html( $categories[0]->name ); ?></span>
                <?php endif; ?>
                <span class="noticia-fecha"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></span>
              </div>
              <div class="noticia-fila-titulo">
                <?php
                $title = get_the_title();
                if ( is_search() && ! empty( $current_search ) ) {
                    $terms = array_filter( explode( ' ', trim( $current_search ) ) );
                    if ( ! empty( $terms ) ) {
                        $escaped_terms = array_map( function($t) { return preg_quote($t, '/'); }, $terms );
                        $pattern = '/(' . implode('|', $escaped_terms) . ')/iu';
                        $title = preg_replace( $pattern, '<mark>$1</mark>', esc_html( $title ) );
                    }
                    echo wp_kses_post( $title );
                } else {
                    echo esc_html( $title );
                }
                ?>
              </div>
              <div class="noticia-fila-excerpt">
                <?php
                $excerpt = get_the_excerpt();
                if ( empty( $excerpt ) ) {
                    $excerpt = wp_strip_all_tags( get_the_content() );
                }
                $excerpt = wp_trim_words( $excerpt, 22 );
                if ( is_search() && ! empty( $current_search ) ) {
                    $terms = array_filter( explode( ' ', trim( $current_search ) ) );
                    if ( ! empty( $terms ) ) {
                        $escaped_terms = array_map( function($t) { return preg_quote($t, '/'); }, $terms );
                        $pattern = '/(' . implode('|', $escaped_terms) . ')/iu';
                        $excerpt = preg_replace( $pattern, '<mark>$1</mark>', esc_html( $excerpt ) );
                    }
                    echo wp_kses_post( $excerpt );
                } else {
                    echo esc_html( $excerpt );
                }
                ?>
              </div>
              <div class="noticia-fila-link">Leer noticia completa →</div>
            </div>
          </a>
          <?php
        endwhile;
      else :
        ?>
        <div class="empty-state">
          <strong>Sin resultados</strong>No hay noticias que coincidan con los filtros seleccionados.
        </div>
      <?php
      endif;
      ?>
    </div>

    <!-- Paginación -->
    <div class="paginacion">
      <?php
      $links = paginate_links( array(
          'type'      => 'array',
          'prev_text' => '‹ Anterior',
          'next_text' => 'Siguiente ›',
      ) );

      if ( ! empty( $links ) ) {
          foreach ( $links as $link ) {
              // Convert default WordPress page list links into our theme styled buttons
              $btn = str_replace( 'page-numbers', 'pag-btn', $link );
              if ( strpos( $btn, 'current' ) !== false ) {
                  $btn = str_replace( 'pag-btn', 'pag-btn active', $btn );
              }
              echo $btn;
          }
      }
      ?>
    </div>
  </div>

  <aside class="patroc-sidebar">
    <div class="patroc-sidebar-inner">
      <div class="patroc-sidebar-title">Patrocinadores &amp; Colaboradores</div>
      <div class="patroc-sidebar-body">
        <a href="https://www.castillalamancha.es/" target="_blank" rel="noopener" class="patroc-main">
          <div class="patroc-main-badge">Patrocinador Oficial</div>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo_castilla_la_mancha.png" alt="Junta de Castilla-La Mancha">
          <div class="patroc-main-name">Junta de Castilla-La Mancha</div>
        </a>
        <hr class="patroc-divider">
        <div class="patroc-divider-label">Colaboradores</div>
        <div class="patroc-logos-grid">
          <a href="https://automaor.com.es/" target="_blank" rel="noopener" class="patroc-logo-item" style="grid-column:1/-1;">
            <img src="https://www.fedgolfclm.com/wp-content/uploads/2025/04/Automaor-RENAULT-Golf-CLM-banners-05-1.jpg" alt="Automaor Renault">
          </a>
          <a href="https://www.joma-sport.com/" target="_blank" rel="noopener" class="patroc-logo-item">
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/joma-logo.svg" alt="Joma Sport">
          </a>
          <a href="http://www.rfegolf.es/" target="_blank" rel="noopener" class="patroc-logo-item">
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/rfeg-logo.png" alt="RFEG">
          </a>
          <a href="http://www.fedgolfclm.com/acuerdo-suscrito-por-la-federacion-de-golf-de-castilla-la-mancha-con-golf-estudio/" target="_blank" rel="noopener" class="patroc-logo-item" style="grid-column:1/-1;">
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/golf-estudio-logo.jpg" alt="Golf Estudio">
          </a>
        </div>
      </div>
    </div>
  </aside>
</div>

<?php
get_footer();
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('buscador');
    const dropdown = document.getElementById('buscadorResultados');
    if(!input || !dropdown) return;
    
    // Position parent container relative for absolute dropdown
    const container = input.closest('.buscador-container');
    if(container) container.style.position = 'relative';

    let timer;
    input.addEventListener('input', function() {
        clearTimeout(timer);
        const val = this.value.trim();
        if (val.length < 2) {
            dropdown.style.display = 'none';
            return;
        }
        timer = setTimeout(() => {
            const url = '<?php echo esc_url(home_url('/wp-json/wp/v2/posts?per_page=15&search=')); ?>' + encodeURIComponent(val);
            fetch(url)
                .then(r => r.json())
                .then(posts => {
                    dropdown.innerHTML = '';
                    if (posts.length > 0) {
                        // Separar términos para destacar todas las palabras por separado
                        const terms = val.trim().split(/\s+/).filter(Boolean);
                        const escapedTerms = terms.map(t => t.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).join('|');
                        const regex = new RegExp(`(${escapedTerms})`, 'gi');

                        posts.forEach(p => {
                            const a = document.createElement('a');
                            a.href = p.link;
                            a.className = 'autocomplete-item';
                            a.innerHTML = p.title.rendered.replace(regex, '<mark>$1</mark>');
                            dropdown.appendChild(a);
                        });
                        dropdown.style.display = 'flex';
                    } else {
                        dropdown.innerHTML = '<div class="autocomplete-item empty-result" style="color:#777;">Sin resultados</div>';
                        dropdown.style.display = 'flex';
                    }
                })
                .catch(() => {
                    dropdown.style.display = 'none';
                });
        }, 250);
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
});
</script>
