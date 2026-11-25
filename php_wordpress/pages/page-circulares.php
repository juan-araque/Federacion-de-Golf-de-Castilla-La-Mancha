<?php
/**
 * Template Name: Circulares
 *
 * @package FGCLM_Theme_v071
 */

get_header();

// Query dynamic circulars
$circ_query = new WP_Query( array(
    'post_type'      => 'circular_doc',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
) );

$dynamic_circs = array(
    'rfeg'    => array(),
    'fgclm'   => array(),
    'locales' => array(),
);

if ( $circ_query->have_posts() ) {
    while ( $circ_query->have_posts() ) {
        $circ_query->the_post();
        $p_id = get_the_ID();
        $type     = get_post_meta( $p_id, '_circular_type', true );
        $year     = get_post_meta( $p_id, '_circular_year', true );
        $pdf_file = get_post_meta( $p_id, '_circular_pdf', true );
        $badge    = get_post_meta( $p_id, '_circular_badge_text', true );
        $is_new   = get_post_meta( $p_id, '_circular_is_new', true ) === 'yes';
        $subtitle = get_post_meta( $p_id, '_circular_subtitle', true );
        
        if ( empty( $type ) ) $type = 'fgclm';
        if ( empty( $year ) ) $year = date('Y');
        
        if ( ! empty( $pdf_file ) ) {
            // Default subtitle fallback
            if ( empty( $subtitle ) ) {
                if ( $type === 'rfeg' ) {
                    $subtitle = 'Publicada: ' . $year . ' · RFEG';
                } elseif ( $type === 'fgclm' ) {
                    $subtitle = 'Publicada: ' . $year . ' · FGCLM';
                } else {
                    $subtitle = 'Reglas Locales · Temporada ' . $year;
                }
            }
            
            // Default badge fallback
            if ( empty( $badge ) && $is_new ) {
                $badge = 'Nueva';
            }
            
            $dynamic_circs[$type][] = array(
                'title'    => get_the_title(),
                'url'      => $pdf_file,
                'subtitle' => $subtitle,
                'badge'    => $badge,
                'is_new'   => $is_new,
                'year'     => $year,
            );
        }
    }
    wp_reset_postdata();
}
?>

<style>
body { padding-top: 70px; }
    .circulares-section { padding: 64px 6rem; max-width: 1100px; margin: 0 auto; }
    .block-title {
      font-family: var(--font-display);
      font-size: 1.4rem; font-weight: 700;
      color: var(--green-dark);
      margin-bottom: 24px;
      padding-bottom: 12px;
      border-bottom: 2px solid var(--gold);
    }
    .intro-text { font-size: 15px; color: var(--gray-600); line-height: 1.8; max-width: 720px; margin-bottom: 48px; }
    .tabs {
      display: flex;
      gap: 0;
      border-bottom: 2px solid var(--gray-100);
      margin-bottom: 36px;
    }
    .tab-btn {
      padding: 12px 28px;
      border: none;
      background: none;
      font-family: var(--font-body);
      font-size: 13px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--gray-300);
      cursor: pointer;
      border-bottom: 3px solid transparent;
      margin-bottom: -2px;
      transition: all 0.2s;
    }
    .tab-btn.active { color: var(--green-dark); border-bottom-color: var(--gold); }
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .circ-badge {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      padding: 3px 10px;
      border-radius: 2px;
      background: var(--green-light);
      color: var(--green-mid);
      flex-shrink: 0;
    }
    .circ-badge.nueva { background: var(--gold-light); color: #856404; }
    @media (max-width: 1100px) { .circulares-section { padding: 48px 2.5rem; } }
    @media (max-width: 640px) { .circulares-section { padding: 32px 1.25rem; } .tab-btn { padding: 10px 14px; font-size: 11px; } }
</style>

<div class="page-hero">
  <div class="page-hero-content">
    <div class="page-hero-label">Reglamentos</div>
    <h1><em>Circulares</em></h1>
    <p>Circulares y comunicados oficiales de la RFEG y la Federación de Golf de Castilla-La Mancha.</p>
  </div>
</div>

<div class="circulares-section">
  <p class="intro-text">
    Las circulares son comunicados oficiales emitidos por la Real Federación Española de Golf (RFEG) y la Federación de Golf de Castilla-La Mancha (FGCLM) con carácter normativo, informativo o interpretativo. Son de obligado conocimiento para todos los clubes, árbitros y jugadores federados.
  </p>

  <div class="tabs">
    <button class="tab-btn active" onclick="showTab('rfeg', this)">Circulares RFEG</button>
    <button class="tab-btn" onclick="showTab('fgclm', this)">Circulares FGCLM</button>
    <button class="tab-btn" onclick="showTab('locales', this)">Reglas Locales</button>
  </div>

  <div id="tab-rfeg" class="tab-content active">
    <ul class="circ-list">
      <?php foreach ( $dynamic_circs['rfeg'] as $circ ) : ?>
      <a href="<?php echo esc_url($circ['url']); ?>" target="_blank" class="circ-item" style="text-decoration:none;">
        <div class="circ-icon"><?php echo fgclm_get_circular_icon_svg('rfeg', 'main'); ?></div>
        <div class="circ-info">
          <div class="circ-titulo"><?php echo esc_html($circ['title']); ?></div>
          <div class="circ-meta"><?php echo esc_html($circ['subtitle']); ?></div>
        </div>
        <?php if ( ! empty($circ['badge']) ) : ?>
          <div class="circ-badge<?php echo $circ['is_new'] ? ' nueva' : ''; ?>"><?php echo esc_html($circ['badge']); ?></div>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular RFEG 01/2026 — Condiciones de la Competición Temporada 2026</div>
          <div class="circ-meta">Publicada: enero 2026 · RFEG</div>
        </div>
        <div class="circ-badge nueva">Nueva</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular RFEG 02/2026 — Actualización del Sistema WHS para la temporada 2026</div>
          <div class="circ-meta">Publicada: enero 2026 · RFEG</div>
        </div>
        <div class="circ-badge nueva">Nueva</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular RFEG 03/2026 — Criterios de selección para Campeonatos de España</div>
          <div class="circ-meta">Publicada: febrero 2026 · RFEG</div>
        </div>
        <div class="circ-badge">2026</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular RFEG 04/2026 — Interpretaciones oficiales de las Reglas de Golf</div>
          <div class="circ-meta">Publicada: febrero 2026 · RFEG</div>
        </div>
        <div class="circ-badge">2026</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular RFEG 12/2025 — Normativa de uso de rangefinders y tecnología en competición</div>
          <div class="circ-meta">Publicada: noviembre 2025 · RFEG</div>
        </div>
        <div class="circ-badge">2025</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular RFEG 10/2025 — Cuotas y tarifas de licencias federativas 2025</div>
          <div class="circ-meta">Publicada: octubre 2025 · RFEG</div>
        </div>
        <div class="circ-badge">2025</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular RFEG 08/2025 — Normativa antidopaje temporada 2025</div>
          <div class="circ-meta">Publicada: julio 2025 · RFEG</div>
        </div>
        <div class="circ-badge">2025</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular RFEG 06/2025 — Modificaciones al Reglamento de Competición</div>
          <div class="circ-meta">Publicada: junio 2025 · RFEG</div>
        </div>
        <div class="circ-badge">2025</div>
      </li>
    </ul>
  </div>

  <div id="tab-fgclm" class="tab-content">
    <ul class="circ-list">
      <?php foreach ( $dynamic_circs['fgclm'] as $circ ) : ?>
      <a href="<?php echo esc_url($circ['url']); ?>" target="_blank" class="circ-item" style="text-decoration:none;">
        <div class="circ-icon"><?php echo fgclm_get_circular_icon_svg('fgclm', 'main'); ?></div>
        <div class="circ-info">
          <div class="circ-titulo"><?php echo esc_html($circ['title']); ?></div>
          <div class="circ-meta"><?php echo esc_html($circ['subtitle']); ?></div>
        </div>
        <?php if ( ! empty($circ['badge']) ) : ?>
          <div class="circ-badge<?php echo $circ['is_new'] ? ' nueva' : ''; ?>"><?php echo esc_html($circ['badge']); ?></div>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular FGCLM 01/2026 — Calendario de Competiciones Autonómicas 2026</div>
          <div class="circ-meta">Publicada: enero 2026 · FGCLM</div>
        </div>
        <div class="circ-badge nueva">Nueva</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular FGCLM 02/2026 — Cuotas de licencias temporada 2026</div>
          <div class="circ-meta">Publicada: enero 2026 · FGCLM</div>
        </div>
        <div class="circ-badge nueva">Nueva</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular FGCLM 03/2026 — Condiciones de Competición Cto. Absoluto Masculino 2026</div>
          <div class="circ-meta">Publicada: marzo 2026 · FGCLM</div>
        </div>
        <div class="circ-badge nueva">Nueva</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular FGCLM 04/2026 — Condiciones de Competición Cto. Femenino 2026</div>
          <div class="circ-meta">Publicada: abril 2026 · FGCLM</div>
        </div>
        <div class="circ-badge">2026</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular FGCLM 08/2025 — Normativa del Circuito 5ª Categoría CLM 2025</div>
          <div class="circ-meta">Publicada: marzo 2025 · FGCLM</div>
        </div>
        <div class="circ-badge">2025</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular FGCLM 07/2025 — Procedimiento de inscripción a torneos autonómicos</div>
          <div class="circ-meta">Publicada: febrero 2025 · FGCLM</div>
        </div>
        <div class="circ-badge">2025</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Circular FGCLM 06/2025 — Protocolo de actuación en caso de tormenta eléctrica</div>
          <div class="circ-meta">Publicada: enero 2025 · FGCLM</div>
        </div>
        <div class="circ-badge">2025</div>
      </li>
    </ul>
  </div>

  <div id="tab-locales" class="tab-content">
    <ul class="circ-list">
      <?php foreach ( $dynamic_circs['locales'] as $circ ) : ?>
      <a href="<?php echo esc_url($circ['url']); ?>" target="_blank" class="circ-item" style="text-decoration:none;">
        <div class="circ-icon"><?php echo fgclm_get_circular_icon_svg('locales', 'main'); ?></div>
        <div class="circ-info">
          <div class="circ-titulo"><?php echo esc_html($circ['title']); ?></div>
          <div class="circ-meta"><?php echo esc_html($circ['subtitle']); ?></div>
        </div>
        <?php if ( ! empty($circ['badge']) ) : ?>
          <div class="circ-badge<?php echo $circ['is_new'] ? ' nueva' : ''; ?>"><?php echo esc_html($circ['badge']); ?></div>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Reglas Locales Generales FGCLM 2026</div>
          <div class="circ-meta">Aplicables a todos los torneos autonómicos · Temporada 2026</div>
        </div>
        <div class="circ-badge nueva">Vigente</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Reglas Locales — Club de Golf Layos</div>
          <div class="circ-meta">Válidas para torneos celebrados en este campo · Toledo</div>
        </div>
        <div class="circ-badge">Campo</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Reglas Locales — Cabanillas Golf & Country Club</div>
          <div class="circ-meta">Válidas para torneos celebrados en este campo · Guadalajara</div>
        </div>
        <div class="circ-badge">Campo</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Reglas Locales — Club de Golf Las Pinaíllas</div>
          <div class="circ-meta">Válidas para torneos celebrados en este campo · Albacete</div>
        </div>
        <div class="circ-badge">Campo</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Reglas Locales — Palomarejos Golf</div>
          <div class="circ-meta">Válidas para torneos celebrados en este campo · Toledo</div>
        </div>
        <div class="circ-badge">Campo</div>
      </li>
      <li class="circ-item">
        <div class="circ-icon"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg></div>
        <div class="circ-info">
          <div class="circ-titulo">Reglas Locales — Montealvar Golf</div>
          <div class="circ-meta">Válidas para torneos celebrados en este campo · Guadalajara</div>
        </div>
        <div class="circ-badge">Campo</div>
      </li>
    </ul>
  </div>
</div>


<script>
  function showTab(tabId, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tabId).classList.add('active');
    btn.classList.add('active');
  }
</script>

<?php
get_footer();
