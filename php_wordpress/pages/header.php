<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Jose, Ricardo, Marcos, Alexis, Juan y Maxi">
  <link rel="icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() ); ?>/img/favicon_pagina.png">
  
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="https://api.open-meteo.com">
  <link rel="dns-prefetch" href="https://images.unsplash.com">

  <?php wp_head(); ?>

  <script type="text/javascript">
      var wp_home_url = '<?php echo esc_url( home_url() ); ?>';
      <?php
      $competiciones_pages = get_pages(array(
          'meta_key' => '_wp_page_template',
          'meta_value' => 'page-competiciones.php'
      ));
      if (!empty($competiciones_pages)) {
          $comp_url = get_permalink($competiciones_pages[0]->ID);
      } else {
          $comp_url = home_url('/competiciones/');
      }
      ?>
      var wp_competiciones_url = '<?php echo esc_url($comp_url); ?>';
  </script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<nav id="mainNav">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo">
    <div class="nav-logo-icon">
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/favicon_pagina.png" alt="Logo FGCLM">
    </div>
    <div class="nav-logo-text">
      <span>Federación de Golf</span>
      <span>Castilla-La Mancha</span>
    </div>
  </a>

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
      <a href="<?php echo esc_url( home_url( '/inscripciones-y-resultados-torneos-fed-castilla-la-mancha/' ) ); ?>">Torneos</a>
      <ul class="dropdown-menu">
        <li><a href="<?php echo esc_url( home_url( '/inscripciones-y-resultados-torneos-fed-castilla-la-mancha/' ) ); ?>">Calendario</a></li>
        <li><a href="https://rfegolf.es/CompetenciaPaginas/AllCompetitions.aspx" target="_blank" rel="noopener">Calendario RFEG</a></li>
        <li><a href="<?php echo esc_url( home_url( '/en-vivo/' ) ); ?>"><span class="recording-indicator"></span>En vivo</a></li>
      </ul>
    </li>
    <li><a href="<?php echo esc_url( home_url( '/escuela/' ) ); ?>">Escuela</a></li>
    <li class="dropdown mobile-app-torneos">
      <a href="#">App Torneos</a>
      <ul class="dropdown-menu">
        <li><a href="https://apps.apple.com/es/app/fgclm-torneos/id6444149260" target="_blank" rel="noopener">📱 iOS (iPhone/iPad)</a></li>
        <li><a href="https://play.google.com/store/apps/details?id=es.torneodegolf.fed.app&pli=1" target="_blank" rel="noopener">📱 Android</a></li>
        <li><a href="https://appgallery.huawei.com/app/C107314035" target="_blank" rel="noopener">📱 Huawei</a></li>
      </ul>
    </li>
  </ul>

  <div class="nav-right" style="display: flex; align-items: center; gap: 16px;">
    <div class="nav-actions">
      <!-- Search Button Toggle -->
      <button class="nav-search-btn" id="searchToggle" aria-label="Buscar en el sitio" title="Buscar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="7"/>
          <line x1="16.5" y1="16.5" x2="22" y2="22"/>
        </svg>
      </button>

      <a href="https://rfegolf.es/jugar/handicap" target="_blank" rel="noopener" class="btn-nav btn-nav-outline">Consultar Hándicap</a>
      
      <div class="dropdown-app nav-app-dropdown">
        <button class="btn-nav btn-nav-gold boton-app-nav">App Torneos ▾</button>
        <div class="dropdown-contenido dropdown-contenido-nav">
          <a href="https://apps.apple.com/es/app/fgclm-torneos/id6444149260" target="_blank" rel="noopener" style="display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 384 512"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"/></svg>
            iOS
          </a>
          <a href="https://play.google.com/store/apps/details?id=es.torneodegolf.fed.app&pli=1" target="_blank" rel="noopener" style="display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 512 512"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/></svg>
            Android
          </a>
          <a href="https://appgallery.huawei.com/app/C107314035" target="_blank" rel="noopener" style="display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/><path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
            Huawei
          </a>
        </div>
      </div>

      <div class="lang-selector" id="langSelector">
        <button class="lang-toggle" id="langToggle" aria-label="Cambiar idioma" aria-expanded="false" aria-haspopup="true">
          <svg class="lang-globe" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          <span class="lang-current" id="langCurrent">ES</span>
          <svg class="lang-chevron" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="lang-dropdown" id="langDropdown" role="menu">
          <button class="lang-option active" role="menuitem" data-lang="es">
            <span class="lang-option-code">ES</span>
            <span class="lang-option-name">Español</span>
            <svg class="lang-check" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </button>
          <button class="lang-option" role="menuitem" data-lang="en">
            <span class="lang-option-code">EN</span>
            <span class="lang-option-name">English</span>
            <svg class="lang-check" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </button>
        </div>
      </div>
    </div>
    
    <button class="nav-hamburger" id="navToggle" aria-label="Abrir menú" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- ── BUSCADOR OVERLAY ─────────────────────────────────── -->
<div id="searchOverlay" role="dialog" aria-modal="true" aria-label="Buscador del sitio">
  <div id="searchModal">
    <div id="searchHeader">
      <div id="searchInputWrap">
        <svg id="searchIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="7"/>
          <line x1="16.5" y1="16.5" x2="22" y2="22"/>
        </svg>
        <input type="search" id="searchInput" placeholder="Buscar en la web de la FGCLM…" autocomplete="off" spellcheck="false">
        <button id="searchClear" aria-label="Limpiar búsqueda">✕</button>
      </div>
      <button id="searchClose" aria-label="Cerrar buscador">Cerrar</button>
    </div>
    <div id="searchBody">
      <div id="searchResults"></div>
      <div id="searchEmpty" hidden>
        <div id="searchEmptyIcon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
          </svg>
        </div>
        <p>No se encontraron resultados para <strong id="searchEmptyTerm"></strong></p>
        <p>Prueba con otros términos o navega por el menú principal.</p>
      </div>
      <div id="searchHint" style="display:none;"></div>
    </div>
  </div>
</div>
