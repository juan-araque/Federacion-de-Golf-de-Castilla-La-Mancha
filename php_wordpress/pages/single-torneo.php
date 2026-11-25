<?php
/**
 * Template for displaying single Torneo (tournament) posts
 *
 * @package FGCLM_Theme_v071
 */

get_header();

if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();

    $fecha      = get_post_meta( get_the_ID(), '_torneo_fecha', true );
    $sede       = get_post_meta( get_the_ID(), '_torneo_sede',  true );
    $noticia_id = get_post_meta( get_the_ID(), '_torneo_noticia_relacionada', true );
    ?>

    <!-- Hero -->
    <div class="page-hero" style="background: linear-gradient(135deg, rgba(10,61,46,0.97) 0%, rgba(26,102,69,0.85) 50%, rgba(45,158,107,0.6) 100%);">
      <div class="page-hero-content">
        <div class="page-hero-label">Calendario</div>
        <h1><em><?php the_title(); ?></em></h1>
      </div>
    </div>

    <!-- Content -->
    <div class="torneo-detail-wrap">
      <div class="torneo-detail-card">
        <div class="torneo-detail-icon">
          <svg viewBox="0 0 64 64" width="64" height="64" fill="none" stroke="currentColor" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="12" y="16" width="40" height="36" rx="4" ry="4"></rect>
            <line x1="20" y1="10" x2="20" y2="22"></line>
            <line x1="44" y1="10" x2="44" y2="22"></line>
            <line x1="12" y1="28" x2="52" y2="28"></line>
          </svg>
        </div>

        <h2 class="torneo-detail-title">Información del Campeonato</h2>

        <div class="torneo-detail-meta">
          <?php if ( $fecha ) : ?>
          <div class="torneo-detail-item">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span><?php echo esc_html( $fecha ); ?></span>
          </div>
          <?php endif; ?>

          <?php if ( $sede ) : ?>
          <div class="torneo-detail-item">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <span class="torneo-detail-sede"><?php echo esc_html( $sede ); ?></span>
          </div>
          <?php endif; ?>
        </div>

        <?php
        $content = get_the_content();
        if ( ! empty( trim( $content ) ) ) :
        ?>
          <div class="torneo-detail-content">
            <?php the_content(); ?>
          </div>
        <?php endif; ?>

        <?php
        // ── NOTICIA VINCULADA (desde el editor de WordPress) ──
        if ( ! empty( $noticia_id ) ) :
          $noticia_post = get_post( $noticia_id );
          if ( $noticia_post && $noticia_post->post_status === 'publish' ) :
            $noticia_link  = get_permalink( $noticia_id );
            $noticia_title = get_the_title( $noticia_id );
            $noticia_date  = get_the_date( 'j M Y', $noticia_id );
            $noticia_thumb = get_the_post_thumbnail_url( $noticia_id, 'medium' );
            $noticia_excerpt = get_the_excerpt( $noticia_id );
        ?>
          <div class="torneo-noticia-vinculada">
            <a href="<?php echo esc_url( $noticia_link ); ?>" class="torneo-noticia-card">
              <?php if ( $noticia_thumb ) : ?>
                <div class="torneo-noticia-thumb">
                  <img src="<?php echo esc_url( $noticia_thumb ); ?>" alt="<?php echo esc_attr( $noticia_title ); ?>" loading="lazy">
                </div>
              <?php endif; ?>
              <div class="torneo-noticia-info">
                <div class="torneo-noticia-badge">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                    <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6z"/>
                  </svg>
                  Noticia vinculada
                </div>
                <div class="torneo-noticia-titulo"><?php echo esc_html( $noticia_title ); ?></div>
                <?php if ( $noticia_excerpt ) : ?>
                  <div class="torneo-noticia-excerpt"><?php echo esc_html( wp_trim_words( $noticia_excerpt, 25 ) ); ?></div>
                <?php endif; ?>
                <div class="torneo-noticia-footer">
                  <span class="torneo-noticia-fecha"><?php echo esc_html( $noticia_date ); ?></span>
                  <span class="torneo-noticia-btn">Leer noticia →</span>
                </div>
              </div>
            </a>
          </div>
        <?php
          endif;
        else :
          // ── NOTICIAS RELACIONADAS AUTOMÁTICAS (cuando NO hay noticia vinculada) ──
          $torneo_title = get_the_title();

          // Remove stop words
          $stop_words = array(
            'de', 'del', 'la', 'el', 'los', 'las', 'por', 'y', 'en', 'a', 'con', 'para',
            'i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x', 'xi', 'xii',
            'xiii', 'xiv', 'xv', 'xvi', 'xvii', 'xviii', 'xix', 'xx', 'xxi', 'xxii',
            'xxiii', 'xxiv', 'xxv', 'xxvi', 'xxvii', 'xxviii', 'xxix', 'xxx', 'xxxi',
            'xxxii', 'xxxiii', 'xxxiv', 'xxxv', 'xxxvi', 'xxxvii',
            'castilla', 'mancha', 'jornada', 'puntuable',
          );

          $words = preg_split( '/[\s\-–—,]+/', mb_strtolower( $torneo_title ) );
          $keywords = array();
          foreach ( $words as $w ) {
            $w = trim( $w, '()[].' );
            if ( mb_strlen( $w ) > 2 && ! in_array( $w, $stop_words, true ) && ! is_numeric( $w ) ) {
              $keywords[] = $w;
            }
          }

          $related_posts = array();
          $found_ids     = array();

          $fecha_orden = get_post_meta( get_the_ID(), '_torneo_fecha_orden', true );
          $date_query = array();
          if ( ! empty( $fecha_orden ) ) {
            $date_timestamp = strtotime( $fecha_orden );
            if ( $date_timestamp ) {
              // News can be published from 30 days before the tournament to 30 days after the tournament
              $start_date = date( 'Y-m-d', strtotime( '-30 days', $date_timestamp ) );
              $end_date   = date( 'Y-m-d', strtotime( '+30 days', $date_timestamp ) );
              $date_query = array(
                array(
                  'after'     => $start_date,
                  'before'    => $end_date,
                  'inclusive' => true,
                ),
              );
            }
          }

          // 1. Query by keywords AND date range
          if ( ! empty( $keywords ) ) {
            $search_term = implode( ' ', array_slice( $keywords, 0, 5 ) );
            $args = array(
              'post_type'      => 'post',
              'posts_per_page' => 6,
              'post_status'    => 'publish',
              's'              => $search_term,
              'orderby'        => 'relevance',
            );
            if ( ! empty( $date_query ) ) {
              $args['date_query'] = $date_query;
            }
            $related_query = new WP_Query( $args );
            if ( $related_query->have_posts() ) {
              while ( $related_query->have_posts() ) {
                $related_query->the_post();
                if ( ! in_array( get_the_ID(), $found_ids, true ) ) {
                  $found_ids[]     = get_the_ID();
                  $related_posts[] = array(
                    'title'     => get_the_title(),
                    'permalink' => get_permalink(),
                    'date'      => get_the_date( 'j M Y' ),
                    'thumb'     => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
                  );
                }
              }
              wp_reset_postdata();
            }

            // 2. Query by fewer keywords AND date range
            if ( count( $related_posts ) < 6 && count( $keywords ) > 2 ) {
              $args2 = array(
                'post_type'      => 'post',
                'posts_per_page' => 6 - count( $related_posts ),
                'post_status'    => 'publish',
                's'              => implode( ' ', array_slice( $keywords, 0, 3 ) ),
                'orderby'        => 'relevance',
                'post__not_in'   => $found_ids,
              );
              if ( ! empty( $date_query ) ) {
                $args2['date_query'] = $date_query;
              }
              $related_query2 = new WP_Query( $args2 );
              if ( $related_query2->have_posts() ) {
                while ( $related_query2->have_posts() ) {
                  $related_query2->the_post();
                  if ( ! in_array( get_the_ID(), $found_ids, true ) ) {
                    $found_ids[]     = get_the_ID();
                    $related_posts[] = array(
                      'title'     => get_the_title(),
                      'permalink' => get_permalink(),
                      'date'      => get_the_date( 'j M Y' ),
                      'thumb'     => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
                    );
                  }
                }
                wp_reset_postdata();
              }
            }
          }

          // 3. Query just by date range (chronological proximity) if we still don't have enough posts
          if ( count( $related_posts ) < 4 && ! empty( $date_query ) ) {
            $args3 = array(
              'post_type'      => 'post',
              'posts_per_page' => 6 - count( $related_posts ),
              'post_status'    => 'publish',
              'orderby'        => 'date',
              'order'          => 'DESC',
              'post__not_in'   => $found_ids,
              'date_query'     => $date_query,
            );
            $related_query3 = new WP_Query( $args3 );
            if ( $related_query3->have_posts() ) {
              while ( $related_query3->have_posts() ) {
                $related_query3->the_post();
                if ( ! in_array( get_the_ID(), $found_ids, true ) ) {
                  $found_ids[]     = get_the_ID();
                  $related_posts[] = array(
                    'title'     => get_the_title(),
                    'permalink' => get_permalink(),
                    'date'      => get_the_date( 'j M Y' ),
                    'thumb'     => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
                  );
                }
              }
              wp_reset_postdata();
            }
          }

          // 4. Query by keywords WITHOUT date range restriction as a final fallback
          if ( count( $related_posts ) < 4 && ! empty( $keywords ) ) {
            $args4 = array(
              'post_type'      => 'post',
              'posts_per_page' => 6 - count( $related_posts ),
              'post_status'    => 'publish',
              's'              => implode( ' ', array_slice( $keywords, 0, 3 ) ),
              'orderby'        => 'relevance',
              'post__not_in'   => $found_ids,
            );
            $related_query4 = new WP_Query( $args4 );
            if ( $related_query4->have_posts() ) {
              while ( $related_query4->have_posts() ) {
                $related_query4->the_post();
                if ( ! in_array( get_the_ID(), $found_ids, true ) ) {
                  $found_ids[]     = get_the_ID();
                  $related_posts[] = array(
                    'title'     => get_the_title(),
                    'permalink' => get_permalink(),
                    'date'      => get_the_date( 'j M Y' ),
                    'thumb'     => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
                  );
                }
              }
              wp_reset_postdata();
            }
          }

          $related_posts = array_slice( $related_posts, 0, 6 );

          if ( ! empty( $related_posts ) ) :
        ?>
          <div class="torneo-related-section">
            <div class="torneo-related-card">
              <div class="torneo-related-header">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                  <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6z"/>
                </svg>
                Noticias relacionadas
              </div>
              <div class="torneo-related-body">
                <?php foreach ( $related_posts as $rp ) : ?>
                  <a href="<?php echo esc_url( $rp['permalink'] ); ?>" class="relacionadas-item" style="text-decoration: none;">
                    <div class="relacionadas-thumb" style="background: linear-gradient(135deg,#0a3d2e,#1a6645);">
                      <?php if ( ! empty( $rp['thumb'] ) ) : ?>
                        <img src="<?php echo esc_url( $rp['thumb'] ); ?>" alt="" loading="lazy">
                      <?php else : ?>
                        <span style="font-size:18px;">📰</span>
                      <?php endif; ?>
                    </div>
                    <div class="relacionadas-body">
                      <div class="relacionadas-titulo"><?php echo esc_html( $rp['title'] ); ?></div>
                      <div class="relacionadas-fecha"><?php echo esc_html( $rp['date'] ); ?></div>
                    </div>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php
          endif;
        endif;
        ?>

      </div>
    </div>

    <?php
  endwhile;
endif;

get_footer();
