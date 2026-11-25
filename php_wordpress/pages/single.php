<?php
/**
 * The template for displaying all single posts
 *
 * @package FGCLM_Theme_v071
 */

get_header();
?>

<?php
if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();

    // Get current categories
    $categories = get_the_category();
    $cat_name = ! empty( $categories ) ? $categories[0]->name : 'Actualidad';
    
    // Determine category gradient class or style (matching static rules)
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
    $gradient = isset( $cat_gradients[ $cat_name ] ) ? $cat_gradients[ $cat_name ] : $cat_gradients['default'];

    $cat_emojis = array(
      'Masculino'            => '♂️',
      'Femenino'             => '♀️',
      'Masculino y Femenino' => '👥',
      'Senior'               => '👴',
      'Juvenil'              => '⭐',
      'Profesional'          => '🛡️',
      'Pitch & Putt'         => '⛳',
      'Federativas'          => '📋',
      'Golf Adaptado'        => '♿',
      'Reglas y Campos'      => '📖',
      'default'              => '⛳',
    );
    $emoji = isset( $cat_emojis[ $cat_name ] ) ? $cat_emojis[ $cat_name ] : $cat_emojis['default'];
    ?>

    <!-- HERO -->
    <div class="noticia-hero" style="background: linear-gradient(135deg, rgba(10,61,46,0.97) 0%, rgba(26,102,69,0.85) 50%, rgba(45,158,107,0.6) 100%), url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?: 'https://images.unsplash.com/photo-1535131749006-b7f58c99034b?w=1600&q=80' ); ?>') center/cover no-repeat;">
      <div class="noticia-hero-pattern"></div>
      <div class="noticia-hero-overlay"></div>
      <div class="noticia-hero-content">
        <div class="noticia-breadcrumb">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a>
          <span>&rsaquo;</span>
          <a href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>">Noticias</a>
          <span>&rsaquo;</span>
          <?php if ( ! empty( $categories ) ) : ?>
            <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php echo esc_html( $cat_name ); ?></a>
          <?php else : ?>
            <span><?php echo esc_html( $cat_name ); ?></span>
          <?php endif; ?>
        </div>
        <div class="noticia-cats">
          <?php
          if ( ! empty( $categories ) ) {
            foreach ( $categories as $c ) {
              echo '<a href="' . esc_url( get_category_link( $c->term_id ) ) . '" class="noticia-cat-badge" style="text-decoration:none;">' . esc_html( $c->name ) . '</a> ';
            }
          } else {
            echo '<span class="noticia-cat-badge">Actualidad</span>';
          }
          ?>
        </div>
        <div class="noticia-hero-line"></div>
        <h1 class="noticia-hero-titulo"><?php the_title(); ?></h1>
        <div class="noticia-hero-fecha"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></div>
      </div>
    </div>

    <!-- CUERPO -->
    <div class="noticia-body-wrap">
      <article>
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="noticia-img-wrap">
            <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>" class="loaded">
            <div class="noticia-img-caption">
              <?php echo esc_html( $cat_name ); ?> &middot; <?php echo esc_html( get_the_date( 'j M Y' ) ); ?>
            </div>
          </div>
        <?php else : ?>
          <div class="noticia-img-wrap">
            <div class="noticia-img-placeholder" style="background:<?php echo esc_attr( $gradient ); ?>;">
              <?php echo esc_html( $emoji ); ?>
            </div>
          </div>
        <?php endif; ?>

        <?php
        $excerpt = get_the_excerpt();
        if ( ! empty( $excerpt ) ) :
        ?>
          <p class="noticia-excerpt-pull"><?php echo esc_html( $excerpt ); ?></p>
        <?php endif; ?>

        <div class="noticia-wp-content">
          <?php the_content(); ?>
        </div>

        <div class="noticia-footer-note">
          Esta noticia es un comunicado oficial de la Federación de Golf de Castilla-La Mancha.
        </div>
      </article>

      <aside class="noticia-sidebar">
        <!-- Datos -->
        <div class="sidebar-card sidebar-datos-card">
          <div class="sidebar-card-title">Datos</div>
          <div class="sidebar-card-body" id="sidebarDatos">
            <div class="sidebar-info-row">
              <div class="sidebar-info-item">
                <span class="sidebar-info-label">Publicado</span>
                <span class="sidebar-info-value"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></span>
              </div>
              <div class="sidebar-info-item">
                <span class="sidebar-info-label">Categorías</span>
                <span class="sidebar-info-value">
                  <?php
                  if ( ! empty( $categories ) ) {
                    $cat_links = array();
                    foreach ( $categories as $c ) {
                      $cat_links[] = '<a href="' . esc_url( get_category_link( $c->term_id ) ) . '">' . esc_html( $c->name ) . '</a>';
                    }
                    echo implode( ', ', $cat_links );
                  } else {
                    echo 'Actualidad';
                  }
                  ?>
                </span>
              </div>
              <div class="sidebar-info-item">
                <span class="sidebar-info-label">Temporada</span>
                <span class="sidebar-info-value"><?php echo esc_html( get_the_date( 'Y' ) ); ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Relacionadas -->
        <div class="sidebar-card sidebar-relac-card">
          <div class="sidebar-card-title">Noticias relacionadas</div>
          <div class="sidebar-card-body">
            <?php
            $related_args = array(
              'post_type'      => 'post',
              'posts_per_page' => 4,
              'post__not_in'   => array( get_the_ID() ),
              'post_status'    => 'publish',
            );
            if ( ! empty( $categories ) ) {
              $related_args['category__in'] = array( $categories[0]->term_id );
            }
            $related_query = new WP_Query( $related_args );

            if ( $related_query->have_posts() ) :
              while ( $related_query->have_posts() ) :
                $related_query->the_post();
                $rel_cats = get_the_category();
                $rel_cat_name = ! empty( $rel_cats ) ? $rel_cats[0]->name : 'default';
                $rel_gradient = isset( $cat_gradients[ $rel_cat_name ] ) ? $cat_gradients[ $rel_cat_name ] : $cat_gradients['default'];
                $rel_emoji = isset( $cat_emojis[ $rel_cat_name ] ) ? $cat_emojis[ $rel_cat_name ] : $cat_emojis['default'];
                ?>
                <a href="<?php the_permalink(); ?>" class="related-post-link relacionadas-item" style="text-decoration: none;">
                  <div class="relacionadas-thumb" style="background:<?php echo esc_attr( $rel_gradient ); ?>;">
                    <?php if ( has_post_thumbnail() ) : ?>
                      <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) ); ?>" alt="" loading="lazy">
                    <?php else : ?>
                      <span style="font-size:18px;"><?php echo esc_html( $rel_emoji ); ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="relacionadas-body">
                    <div class="relacionadas-titulo"><?php the_title(); ?></div>
                    <div class="relacionadas-fecha"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></div>
                  </div>
                </a>
                <?php
              endwhile;
              wp_reset_postdata();
            else :
              echo '<p style="font-size:13px;color:var(--gray-300);">No hay noticias relacionadas.</p>';
            endif;
            ?>
          </div>
        </div>

        <!-- Patrocinadores -->
        <div class="sidebar-card sidebar-patroc-card">
          <div class="sidebar-card-title">Patrocinadores</div>
          <div class="sidebar-card-body">
            <a href="https://www.castillalamancha.es/" target="_blank" rel="noopener" class="patroc-main" style="text-decoration:none;">
              <div class="patroc-main-s-label" style="font-size: 8px; font-weight: 700; color: var(--green-accent); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Patrocinador oficial</div>
              <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo_castilla_la_mancha.png" alt="Junta de Castilla-La Mancha" style="max-width: 180px; max-height: 70px; object-fit: contain;">
              <div class="patroc-main-s-name" style="font-size: 10px; color: var(--gray-300); margin-top: 6px; text-align: center;">Junta de Castilla-La Mancha</div>
            </a>
            
            <div style="height:10px"></div>
            
            <div class="patroc-grid-s">
              <a href="https://automaor.com.es/" target="_blank" rel="noopener" class="patroc-item-s" style="text-decoration:none;">
                <img src="https://www.fedgolfclm.com/wp-content/uploads/2025/04/Automaor-RENAULT-Golf-CLM-banners-05-1.jpg" alt="Renault Automaor" style="max-width: 100px; max-height: 48px; object-fit: contain;">
              </a>
              <a href="https://www.joma-sport.com/" target="_blank" rel="noopener" class="patroc-item-s" style="text-decoration:none;">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/joma-logo.svg" alt="Joma Sport" style="max-width: 100px; max-height: 48px; object-fit: contain;">
              </a>
              <a href="http://www.rfegolf.es/" target="_blank" rel="noopener" class="patroc-item-s" style="text-decoration:none;">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/rfeg-logo.png" alt="RFEG" style="max-width: 100px; max-height: 48px; object-fit: contain;">
              </a>
              <a href="http://www.fedgolfclm.com/acuerdo-suscrito-por-la-federacion-de-golf-de-castilla-la-mancha-con-golf-estudio/" target="_blank" rel="noopener" class="patroc-item-s" style="text-decoration:none;">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/golf-estudio-logo.jpg" alt="Golf Estudio" style="max-width: 100px; max-height: 48px; object-fit: contain;">
              </a>
            </div>
          </div>
        </div>
      </aside>
    </div>

    <?php
  endwhile;
endif;
?>

<?php
get_footer();
