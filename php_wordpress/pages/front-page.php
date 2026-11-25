<?php
/**
 * The front page template file
 *
 * @package FGCLM_Theme_v071
 */

get_header();
?>

<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-pattern"></div>

  <div class="hero-top-bar">
    <div class="hero-tag">Temporada 2026 en marcha</div>
  </div>
  
  <div id="weather-widget" class="weather-widget"></div>

  <div class="hero-content">
    <div class="hero-text">
      <div class="hero-eyebrow">Federación Oficial</div>
      <h1 class="hero-title">
        Federación de Golf<br>
        <em>Castilla-La Mancha</em>
      </h1>
      <p class="hero-sub">
        Promovemos y regulamos el golf en Castilla-La Mancha. Descubre competiciones, clubes, rankings y todo lo que
        necesitas para disfrutar del golf en nuestra región.
      </p>
      <div class="hero-ctas">
        <a href="#torneos" class="btn-primary">Ver Calendario →</a>
      </div>
    </div>
  </div>

  <div class="hero-stats">
    <div class="hero-stat">
      <div class="hero-stat-num">5.800+</div>
      <div class="hero-stat-label">Federados</div>
    </div>
    <div class="hero-stat">
      <div class="hero-stat-num">19</div>
      <div class="hero-stat-label">Campos</div>
    </div>
  </div>
</section>

<section class="noticias-section" id="noticias">
  <div class="section-header">
    <div class="section-label">Actualidad</div>
    <h2 class="section-title">Últimas noticias</h2>
  </div>

  <!-- PHP injection of WordPress dynamic news data for the JS carousel -->
  <?php
  $latest_news = fgclm_v071_get_latest_news_data();
  if ( ! empty( $latest_news ) ) :
  ?>
  <script type="text/javascript">
      window.latestNewsData = <?php echo json_encode( $latest_news ); ?>;
  </script>
  <?php endif; ?>

  <div id="noticiasIndexSkeleton" style="display:flex; justify-content:center; overflow:hidden;">
    <div style="display:flex; height:380px; width:85%; background:var(--white); border:1px solid var(--gray-100); border-radius:12px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.06);">
      <div style="width:48%; background:var(--gray-100); animation:pulse 1.4s ease-in-out infinite;"></div>
      <div style="width:52%; padding:40px 48px; display:flex; flex-direction:column; justify-content:center; gap:16px;">
        <div style="height:12px; width:20%; background:var(--gray-100); border-radius:4px; animation:pulse 1.4s ease-in-out infinite 0.1s;"></div>
        <div style="height:28px; width:80%; background:var(--gray-100); border-radius:4px; animation:pulse 1.4s ease-in-out infinite 0.2s;"></div>
        <div style="height:28px; width:60%; background:var(--gray-100); border-radius:4px; animation:pulse 1.4s ease-in-out infinite 0.3s;"></div>
        <div style="height:16px; width:90%; background:var(--gray-100); border-radius:4px; animation:pulse 1.4s ease-in-out infinite 0.4s;"></div>
        <div style="height:16px; width:75%; background:var(--gray-100); border-radius:4px; animation:pulse 1.4s ease-in-out infinite 0.5s;"></div>
      </div>
    </div>
  </div>

  <div class="noticias-carousel-wrapper" id="noticiasCarouselWrapper" style="display:none; position: relative;">
    <button class="carousel-arrow prev-arrow" id="btnPrevNews" aria-label="Noticia anterior">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 18l-6-6 6-6" />
      </svg>
    </button>

    <div class="noticias-carousel-container" id="noticiasIndexGrid">
      <div class="noticias-carousel-track" id="noticiasCarouselTrack"></div>
    </div>

    <button class="carousel-arrow next-arrow" id="btnNextNews" aria-label="Siguiente noticia">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 18l6-6-6-6" />
      </svg>
    </button>
  </div>

  <div class="ver-mas">
    <a href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>" class="btn-primary" style="background: var(--green-dark); color: var(--white);">Ver todas las noticias →</a>
  </div>
</section>

<section class="calendario-section" id="torneos">
  <div class="section-header">
    <div class="section-label">Agenda 2026</div>
    <h2 class="section-title">Próximos torneos</h2>
  </div>

  <div class="calendario-grid" id="calendarioIndexGrid">
      <!-- Loaded dynamically via calendario_index.js fetching /competiciones/ -->
  </div>

  <div style="margin-top: 2.5rem;">
    <a href="<?php echo esc_url( home_url( '/competiciones/' ) ); ?>" class="btn-primary">Ver calendario completo →</a>
  </div>
</section>

<section class="areas-section" id="sobre">
  <div class="areas-section-inner">
    <div class="section-header areas-header">
      <div class="section-label">Navega por la federación</div>
      <h2 class="section-title">Áreas de interés</h2>
      <p class="areas-subtitle">Accede directamente a los servicios más relevantes de la Federación de Golf de Castilla-La Mancha.</p>
    </div>

    <div class="areas-grid">
      <a href="<?php echo esc_url( home_url( '/inscripciones-y-resultados-torneos-fed-castilla-la-mancha/' ) ); ?>" class="area-card" id="area-competiciones">
        <div class="area-card-glow"></div>
        <div class="area-icon-wrap">
          <svg class="area-svg-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M24 4L30.18 16.54L44 18.56L34 28.28L36.36 42.04L24 35.54L11.64 42.04L14 28.28L4 18.56L17.82 16.54L24 4Z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M24 4L30.18 16.54L44 18.56L34 28.28L36.36 42.04L24 35.54L11.64 42.04L14 28.28L4 18.56L17.82 16.54L24 4Z" fill="currentColor" opacity="0.12" />
          </svg>
        </div>
        <div class="area-card-content">
          <div class="area-tag">Calendario 2026</div>
          <div class="area-title">Competiciones y Torneos</div>
          <div class="area-desc">Consulta el calendario completo de torneos FGCLM y la RFEG, inscripciones y resultados en tiempo real.</div>
        </div>
        <div class="area-link"><span>Acceder</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14" />
            <path d="m12 5 7 7-7 7" />
          </svg></div>
      </a>

      <a href="<?php echo esc_url( home_url( '/rankings/' ) ); ?>" class="area-card" id="area-rankings">
        <div class="area-card-glow"></div>
        <div class="area-icon-wrap">
          <svg class="area-svg-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="6" y="28" width="10" height="16" rx="2" stroke="currentColor" stroke-width="2.5" fill="currentColor" fill-opacity="0.12" />
            <rect x="19" y="16" width="10" height="28" rx="2" stroke="currentColor" stroke-width="2.5" fill="currentColor" fill-opacity="0.12" />
            <rect x="32" y="22" width="10" height="22" rx="2" stroke="currentColor" stroke-width="2.5" fill="currentColor" fill-opacity="0.12" />
            <path d="M24 6L26.5 11H21.5L24 6Z" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.3" />
          </svg>
        </div>
        <div class="area-card-content">
          <div class="area-tag">Circuito Autonómico</div>
          <div class="area-title">Rankings CLM 2026</div>
          <div class="area-desc">Posiciones actualizadas en las distintas categorías del circuito autonómico de Castilla-La Mancha.</div>
        </div>
        <div class="area-link"><span>Acceder</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14" />
            <path d="m12 5 7 7-7 7" />
          </svg></div>
      </a>

      <a href="<?php echo esc_url( home_url( '/licencia/' ) ); ?>" class="area-card" id="area-licencia">
        <div class="area-card-glow"></div>
        <div class="area-icon-wrap">
          <svg class="area-svg-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="8" y="8" width="32" height="32" rx="4" stroke="currentColor" stroke-width="2.5" fill="currentColor" fill-opacity="0.08" />
            <path d="M16 20h16M16 26h12M16 32h8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
            <circle cx="36" cy="14" r="5" stroke="currentColor" stroke-width="2" fill="currentColor" fill-opacity="0.2" />
            <path d="M34.5 14L35.5 15L37.5 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
        <div class="area-card-content">
          <div class="area-tag">Gestión Federativa</div>
          <div class="area-title">Licencia y Hándicap</div>
          <div class="area-desc">Trámites, cuotas, obtención del hándicap y gestión de tu licencia federativa.</div>
        </div>
        <div class="area-link"><span>Acceder</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14" />
            <path d="m12 5 7 7-7 7" />
          </svg></div>
      </a>

      <a href="<?php echo esc_url( home_url( '/circulares-fgclm/' ) ); ?>" class="area-card" id="area-reglamentos">
        <div class="area-card-glow"></div>
        <div class="area-icon-wrap">
          <svg class="area-svg-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 6C12 6 8 8 8 12V40C8 40 12 38 16 38C20 38 24 40 24 40C24 40 28 38 32 38C36 38 40 40 40 40V12C40 8 36 6 36 6C36 6 32 8 28 8C24 8 24 6 24 6C24 6 20 8 16 8C12 8 12 6 12 6Z" stroke="currentColor" stroke-width="2.5" fill="currentColor" fill-opacity="0.08" />
            <path d="M24 6V40" stroke="currentColor" stroke-width="2" stroke-dasharray="3 3" opacity="0.4" />
            <path d="M16 16h4M16 22h4M16 28h4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
            <path d="M28 16h4M28 22h4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
          </svg>
        </div>
        <div class="area-card-content">
          <div class="area-tag">Normativa Oficial</div>
          <div class="area-title">Circulares</div>
          <div class="area-desc">Circulares FGCLM y RFEG, reglamentos y condiciones de la competición.</div>
        </div>
        <div class="area-link"><span>Acceder</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14" />
            <path d="m12 5 7 7-7 7" />
          </svg></div>
      </a>
    </div>
  </div>
</section>

<div class="patrocinadores-section">
  <div class="pat-label">Patrocinadores y colaboradores institucionales</div>

  <div class="pat-featured">
    <a href="https://www.castillalamancha.es/" target="_blank" rel="noopener" class="pat-featured-card">
      <div class="pat-featured-badge">Colaborador Oficial</div>
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo_castilla_la_mancha.png" alt="Junta de Castilla-La Mancha" class="pat-featured-img" style="height:110px; width:auto;" loading="lazy" decoding="async" />
      <div class="pat-featured-name">Junta de Castilla-La Mancha</div>
    </a>

    <a href="https://automaor.com.es/" target="_blank" rel="noopener" class="pat-featured-card">
      <div class="pat-featured-badge">Patrocinador Oficial</div>
      <img src="https://www.fedgolfclm.com/wp-content/uploads/2025/04/Automaor-RENAULT-Golf-CLM-banners-05-1.jpg" alt="Renault Automaor" class="pat-featured-img" style="height:96px; width:auto;" loading="lazy" decoding="async" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
      <div class="pat-featured-fallback" style="display:none; align-items:center; justify-content:center; height:96px;">
        <div style="font-family:var(--font-display); font-size:15px; font-weight:700; color:var(--green-dark); text-align:center; line-height:1.2;">
          Renault Automaor
        </div>
      </div>
      <div class="pat-featured-name">Renault Automaor</div>
    </a>

    <a href="https://deportes.castillalamancha.es/" target="_blank" rel="noopener" class="pat-featured-card">
      <div class="pat-featured-badge">Colaborador Principal</div>
      <img src="https://www.fedgolfclm.com/wp-content/uploads/2025/11/IMAGEN_SOMOSDEPORTE3-18-BLANCO-300x212.png" alt="Somos Deporte" class="pat-featured-img" style="height:100px; width:auto;" loading="lazy" decoding="async" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
      <div class="pat-featured-fallback" style="display:none; align-items:center; justify-content:center; height:100px;">
        <div style="font-family:var(--font-display); font-size:15px; font-weight:700; color:var(--green-dark); text-align:center; line-height:1.2;">
          🏅 Somos Deporte<br><span style="font-size:11px; font-weight:400;">CLM</span>
        </div>
      </div>
      <div class="pat-featured-name">Somos Deporte CLM</div>
    </a>
  </div>

  <div class="pat-divider"></div>

  <div class="pat-logos">
    <a href="https://www.joma-sport.com/" target="_blank" rel="noopener" class="pat-logo">
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/joma-logo.svg" alt="Joma" loading="lazy" decoding="async" onerror="this.style.display='none'; var s=document.createElement('span'); s.textContent='JOMA'; s.style.cssText='font-size:18px;font-weight:700;color:var(--gray-600);'; this.parentNode.appendChild(s);" />
    </a>
    <a href="https://www.turismocastillalamancha.es/" target="_blank" rel="noopener" class="pat-logo">
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/turismo-clm-logo.png" alt="Turismo Castilla-La Mancha" loading="lazy" decoding="async" onerror="this.style.display='none'; var s=document.createElement('span'); s.textContent='Turismo CLM'; s.style.cssText='font-size:14px;font-weight:700;color:var(--gray-600);'; this.parentNode.appendChild(s);" />
    </a>
    <a href="http://www.rfegolf.es/" target="_blank" rel="noopener" class="pat-logo">
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/rfeg-logo.png" alt="RFEG" loading="lazy" decoding="async" onerror="this.style.display='none'; var s=document.createElement('span'); s.textContent='RFEG'; s.style.cssText='font-size:15px;font-weight:700;color:var(--gray-600);'; this.parentNode.appendChild(s);" />
    </a>
    <a href="http://www.fedgolfclm.com/acuerdo-suscrito-por-la-federacion-de-golf-de-castilla-la-mancha-con-golf-estudio/" target="_blank" rel="noopener" class="pat-logo">
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/golf-estudio-logo.jpg" alt="Golf Estudio" loading="lazy" decoding="async" onerror="this.style.display='none'; var s=document.createElement('span'); s.textContent='Golf Estudio'; s.style.cssText='font-size:14px;font-weight:600;color:var(--gray-600);'; this.parentNode.appendChild(s);" />
    </a>
  </div>
</div>

<?php
get_footer();
