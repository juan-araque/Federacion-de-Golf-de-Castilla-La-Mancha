/**
 * theme-shared.js — Header, Footer, Buscador y Accesibilidad compartido
 * Federación de Golf de Castilla-La Mancha
 * WordPress Theme v0.3
 */

(function () {
  "use strict";

  const currentPage = window.location.pathname.split("/").pop() || "index.html";

  const currentScript = document.currentScript;
  const scriptSrc = currentScript ? currentScript.getAttribute("src") : "";
  const basePath = scriptSrc ? scriptSrc.replace(/shared(?:_nav)?\.js.*$/, "") : "";

  const NAV_HTML = `
<nav id="mainNav">
  <a href="index.html" class="nav-logo">
    <div class="nav-logo-icon">
      <img src="img/favicon_pagina.png" alt="Logo FGCLM">
    </div>
    <div class="nav-logo-text">
      <span>Federación de Golf</span>
      <span>Castilla-La Mancha</span>
    </div>
  </a>

  <ul class="nav-links" id="navLinks">
    <li><a href="noticias.html" data-page="noticias.html">Noticias</a></li>
    <li class="dropdown">
      <a href="presidente.html">Federación</a>
      <ul class="dropdown-menu">
        <li><a href="presidente.html" data-page="presidente.html">Bienvenida del Presidente</a></li>
        <li><a href="junta-directiva.html" data-page="junta-directiva.html">Junta Directiva y Asamblea</a></li>
        <li><a href="comites.html" data-page="comites.html">Comités y Delegaciones</a></li>
        <li><a href="licencia.html" data-page="licencia.html">Licencia y Hándicap</a></li>
        <li><a href="subvenciones.html" data-page="subvenciones.html">Subvenciones</a></li>
        <li><a href="transparencia.html" data-page="transparencia.html">Transparencia</a></li>
        <li><a href="contacto.html" data-page="contacto.html">Contacto</a></li>
      </ul>
    </li>
    <li class="dropdown">
      <a href="reglas-golf.html">Reglamentos</a>
      <ul class="dropdown-menu">
        <li><a href="reglas-golf.html" data-page="reglas-golf.html">Reglas de Golf</a></li>
        <li><a href="circulares-rfeg.html" data-page="circulares-rfeg.html">Circulares RFEG</a></li>
        <li><a href="circulares-fgclm.html" data-page="circulares-fgclm.html">Circulares FGCLM</a></li>
        <li><a href="reglas-locales.html" data-page="reglas-locales.html">Reglas Locales</a></li>
      </ul>
    </li>
    <li class="dropdown">
      <a href="rankings.html">Rankings</a>
      <ul class="dropdown-menu">
        <li><a href="rankings.html" data-page="rankings.html">Rankings Castilla-La Mancha</a></li>
        <li><a href="circuito-5categoria.html" data-page="circuito-5categoria.html">Circuito 5ª Categoría</a></li>
      </ul>
    </li>
    <li><a href="clubes.html" data-page="clubes.html">Clubes</a></li>
    <li class="dropdown">
      <a href="competiciones.html">Torneos</a>
      <ul class="dropdown-menu">
        <li><a href="competiciones.html" data-page="competiciones.html">Calendario</a></li>
        <li><a href="https://rfegolf.es/CompetenciaPaginas/AllCompetitions.aspx">Calendario RFEG</a></li>
        <li><a href="en-vivo.html" data-page="en-vivo.html"><span class="recording-indicator"></span>En vivo</a></li>
      </ul>
    </li>
    <li><a href="escuela.html" data-page="escuela.html">Escuela</a></li>
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
      <a href="https://rfegolf.es/jugar/handicap" target="blank" class="btn-nav btn-nav-outline">Consultar Hándicap</a>
      <div class="dropdown-app nav-app-dropdown">
        <button class="btn-nav btn-nav-gold boton-app-nav">App Torneos ▾</button>
        <div class="dropdown-contenido dropdown-contenido-nav">
          <a href="https://apps.apple.com/es/app/fgclm-torneos/id6444149260" target="_blank" style="display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 384 512"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"/></svg>
            iOS
          </a>
          <a href="https://play.google.com/store/apps/details?id=es.torneodegolf.fed.app&pli=1" target="_blank" style="display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 512 512"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/></svg>
            Android
          </a>
          <a href="https://appgallery.huawei.com/app/C107314035" target="_blank" style="display: flex; align-items: center; gap: 8px;">
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
</nav>`;

  const FOOTER_HTML = `
<footer class="footer-minimal">
  <div class="footer-top">
    <div class="footer-brand">
      <div class="footer-logo">
        <div class="footer-logo-icon">FG</div>
        <div class="footer-logo-name">Federación de Golf<br>de Castilla-La Mancha</div>
      </div>
      <p class="footer-desc">
        Organismo rector del golf en Castilla-La Mancha, comprometido con el desarrollo, la promoción y la regulación del deporte.
      </p>
      <div class="footer-contact">
        <p>Plaza de España 1, Local 8, 19200 Azuqueca de Henares (Gu) | 949-262300 | federacion@fedgolfclm.com</p>
      </div>
    </div>
    <div>
      <div class="footer-col-title">La federación</div>
      <ul class="footer-links">
        <li><a href="presidente.html">Bienvenida del Presidente</a></li>
        <li><a href="comites.html">Comités y Delegaciones</a></li>
        <li><a href="contacto.html">Contacto</a></li>
      </ul>
    </div>
    <div>
      <div class="footer-col-title">Legal</div>
      <ul class="footer-links">
        <li><a href="aviso-legal.html">Aviso Legal</a></li>
        <li><a href="privacidad.html">Política de Privacidad</a></li>
        <li><a href="cookies.html">Política de Cookies</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 Federación de Golf de Castilla-La Mancha · Todos los derechos reservados</p>
    <div class="footer-social">
      <a href="https://www.facebook.com/fedgolfclm/" target="_blank" rel="noopener" title="Facebook"><img src="img/facebook_logo.png" alt="Facebook"></a>
      <a href="https://www.instagram.com/fedgolfclm" target="_blank" rel="noopener" title="Instagram"><img src="img/instagram_logo.png" alt="Instagram"></a>
    </div>
  </div>
</footer>`;

  function injectNav() {
    let finalNav = NAV_HTML;
    if (basePath && basePath !== "./") {
      finalNav = finalNav.replace(/(href|src)="(?!\/|http|mailto|#|javascript:)([^"]+)"/g, '$1="' + basePath + '$2"');
    }
    const existingNav = document.getElementById("mainNav") || document.querySelector("nav");
    if (existingNav) {
      existingNav.outerHTML = finalNav;
    } else {
      document.body.insertAdjacentHTML("afterbegin", finalNav);
    }
  }

  function injectFooter() {
    let finalFooter = FOOTER_HTML;
    if (basePath && basePath !== "./") {
      finalFooter = finalFooter.replace(/(href|src)="(?!\/|http|mailto|#|javascript:)([^"]+)"/g, '$1="' + basePath + '$2"');
    }
    const existingFooter = document.querySelector("footer");
    if (existingFooter) {
      existingFooter.outerHTML = finalFooter;
    } else {
      document.body.insertAdjacentHTML("beforeend", finalFooter);
    }
  }

  function markActiveLink() {
    document.querySelectorAll(".nav-links a[data-page]").forEach((link) => {
      if (link.getAttribute("data-page") === currentPage) {
        link.classList.add("active");
        link.style.color = "var(--gold)";
      }
    });
  }

  // ── SITE INDEX for search ──────────────────────────────────
  const homeUrl = (typeof fgclmSettings !== 'undefined' && fgclmSettings.homeUrl) ? fgclmSettings.homeUrl : '';

  const SITE_INDEX = [
    { titulo: 'Inicio', url: homeUrl + '/', desc: 'Página principal de la Federación de Golf de Castilla-La Mancha', tags: 'inicio home principal federacion' },
    { titulo: 'Bienvenida del Presidente', url: homeUrl + '/presidente/', desc: 'Mensaje de bienvenida del presidente de la FGCLM', tags: 'presidente bienvenida directiva' },
    { titulo: 'Junta Directiva y Asamblea', url: homeUrl + '/junta-directiva-y-asamblea-general/', desc: 'Junta directiva, asamblea general y órganos de gobierno', tags: 'junta directiva asamblea gobierno organos' },
    { titulo: 'Comités y Delegaciones', url: homeUrl + '/comites/', desc: 'Comités técnicos y delegaciones provinciales', tags: 'comites delegaciones provinciales albacete cuenca ciudad real guadalajara toledo' },
    { titulo: 'Licencia y Hándicap', url: homeUrl + '/licencia/', desc: 'Tramitación de licencias federativas y consulta de hándicap', tags: 'licencia handicap federado tramites cuota seguro' },
    { titulo: 'Subvenciones', url: homeUrl + '/subvenciones/', desc: 'Información sobre subvenciones y ayudas de la federación', tags: 'subvenciones ayudas becas financiacion' },
    { titulo: 'Transparencia', url: homeUrl + '/transparencia/', desc: 'Portal de transparencia y documentación oficial', tags: 'transparencia documentos cuentas presupuesto estatutos' },
    { titulo: 'Contacto', url: homeUrl + '/contacto/', desc: 'Datos de contacto y formulario de la federación', tags: 'contacto telefono email direccion azuqueca guadalajara' },
    { titulo: 'Reglas de Golf', url: homeUrl + '/reglas-golf/', desc: 'Reglamento oficial de golf y reglas del juego', tags: 'reglas golf reglamento normas juego rfeg' },
    { titulo: 'Circulares RFEG', url: homeUrl + '/circulares-rfeg/', desc: 'Circulares de la Real Federación Española de Golf', tags: 'circulares rfeg comunicados' },
    { titulo: 'Circulares FGCLM', url: homeUrl + '/circulares-fgclm/', desc: 'Circulares federativas y comunicados oficiales de la FGCLM', tags: 'circulares fgclm reglas locales comunicados' },
    { titulo: 'Reglas Locales', url: homeUrl + '/reglas-locales/', desc: 'Reglas locales aplicables a competiciones en Castilla-La Mancha', tags: 'reglas locales campos condiciones' },
    { titulo: 'Rankings Castilla-La Mancha', url: homeUrl + '/rankings/', desc: 'Rankings autonómicos masculino, femenino, senior y juvenil', tags: 'rankings clasificacion masculino femenino senior juvenil absoluto' },
    { titulo: 'Circuito 5ª Categoría', url: homeUrl + '/circuito-5categoria/', desc: 'Circuito y ranking de jugadores de quinta categoría', tags: 'circuito quinta 5 categoria ranking local' },
    { titulo: 'Clubes de Golf', url: homeUrl + '/clubes/', desc: 'Directorio de clubes y campos de golf de Castilla-La Mancha', tags: 'clubes campos golf directorio albacete cuenca ciudad real guadalajara toledo' },
    { titulo: 'Competiciones y Calendario', url: homeUrl + '/competiciones/', desc: 'Calendario de torneos e inscripciones', tags: 'torneos calendario inscripciones competiciones' },
    { titulo: 'En vivo', url: homeUrl + '/en-vivo/', desc: 'Retransmisiones en directo de torneos', tags: 'en vivo directo streaming' },
    { titulo: 'Escuela de Golf', url: homeUrl + '/escuela/', desc: 'Escuela de golf y programas de iniciación', tags: 'escuela iniciacion aprendizaje clases jovenes benjamines' },
    { titulo: 'Noticias', url: homeUrl + '/noticias/', desc: 'Últimas noticias y novedades de la federación', tags: 'noticias actualidad campeonatos resultados' },
    { titulo: 'Aviso Legal', url: homeUrl + '/aviso-legal/', desc: 'Aviso legal y condiciones de uso del sitio web', tags: 'aviso legal condiciones uso terminos' },
    { titulo: 'Política de Privacidad', url: homeUrl + '/privacidad/', desc: 'Política de privacidad y protección de datos', tags: 'privacidad datos personales rgpd lopd' },
    { titulo: 'Política de Cookies', url: homeUrl + '/cookies/', desc: 'Información sobre el uso de cookies en el sitio web', tags: 'cookies politica navegacion' },
  ];

  // ── SEARCH CSS ─────────────────────────────────────────────
  const SEARCH_CSS = `
/* Botón lupa en el nav */
.nav-search-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: rgba(255,255,255,0.75);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 6px;
  border-radius: 4px;
  transition: color 0.2s, background 0.2s;
  margin-right: 4px;
  flex-shrink: 0;
}
.nav-search-btn:hover {
  color: var(--gold);
  background: rgba(255,255,255,0.08);
}

/* Overlay fondo */
#searchOverlay {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 2000;
  background: rgba(10, 61, 46, 0.55);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
  align-items: flex-start;
  justify-content: center;
  padding-top: 80px;
  padding-left: 1rem;
  padding-right: 1rem;
  animation: searchFadeIn 0.2s ease;
}
#searchOverlay.search-open {
  display: flex;
}
@keyframes searchFadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}

/* Modal */
#searchModal {
  background: var(--white);
  width: 100%;
  max-width: 680px;
  border-radius: 4px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
  overflow: hidden;
  animation: searchSlideIn 0.22s ease;
  max-height: calc(100vh - 120px);
  display: flex;
  flex-direction: column;
}
@keyframes searchSlideIn {
  from { transform: translateY(-16px); opacity: 0; }
  to   { transform: translateY(0);     opacity: 1; }
}

/* Cabecera del modal */
#searchHeader {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px 20px;
  border-bottom: 1px solid var(--gray-100);
  flex-shrink: 0;
}
#searchInputWrap {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--gray-50);
  border: 1.5px solid var(--gray-100);
  border-radius: 3px;
  padding: 10px 14px;
  transition: border-color 0.2s;
}
#searchInputWrap:focus-within {
  border-color: var(--green-accent);
}
#searchIcon {
  color: var(--gray-300);
  flex-shrink: 0;
}
#searchInput {
  flex: 1;
  border: none;
  background: transparent;
  font-family: var(--font-body);
  font-size: 15px;
  color: var(--gray-900);
  outline: none;
  min-width: 0;
}
#searchInput::placeholder { color: var(--gray-300); }
#searchInput::-webkit-search-cancel-button {
  -webkit-appearance: none;
  appearance: none;
  display: none;
}
#searchClear {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--gray-300);
  font-size: 14px;
  padding: 2px 4px;
  line-height: 1;
  display: none;
  transition: color 0.15s;
}
#searchClear:hover { color: var(--gray-900); }
#searchClear.visible { display: block; }
#searchClose {
  background: none;
  border: 1.5px solid var(--gray-100);
  cursor: pointer;
  color: var(--gray-600);
  font-family: var(--font-body);
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 8px 14px;
  border-radius: 3px;
  white-space: nowrap;
  transition: all 0.15s;
  flex-shrink: 0;
}
#searchClose:hover {
  border-color: var(--gold);
  color: var(--green-dark);
}

/* Cuerpo de resultados */
#searchBody {
  overflow-y: auto;
  flex: 1;
}

/* Resultados */
#searchResults {
  padding: 0 8px;
}
.search-section-label {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--gray-300);
  padding: 16px 12px 6px;
}
.search-result-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 11px 12px;
  border-radius: 3px;
  text-decoration: none;
  color: var(--gray-900);
  transition: background 0.15s;
  cursor: pointer;
}
.search-result-item:hover {
  background: var(--gray-50);
}
.search-result-item:hover .search-result-title {
  color: var(--green-accent);
}
.search-result-icon {
  width: 34px;
  height: 34px;
  background: var(--green-light);
  border-radius: 3px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  color: var(--green-mid);
}
.search-result-icon.noticia-icon {
  background: var(--gold-light);
  color: #856404;
}
.search-result-content { flex: 1; min-width: 0; }
.search-result-title {
  font-size: 14px;
  font-weight: 600;
  color: var(--gray-900);
  transition: color 0.15s;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.search-result-desc {
  font-size: 12px;
  color: var(--gray-600);
  margin-top: 2px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
mark {
  background: var(--gold-light);
  color: var(--gray-900);
  border-radius: 2px;
  padding: 0 1px;
}

/* Estado vacío */
#searchEmpty {
  padding: 48px 20px;
  text-align: center;
}
#searchEmptyIcon {
  width: 60px;
  height: 60px;
  background: var(--gray-50);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
  color: var(--gray-300);
}
#searchEmpty p {
  font-size: 14px;
  color: var(--gray-600);
  line-height: 1.6;
  margin-bottom: 6px;
}

/* Mobile */
@media (max-width: 640px) {
  #searchOverlay { padding-top: 70px; padding-left: 0; padding-right: 0; align-items: flex-start; }
  #searchModal { max-width: 100%; border-radius: 0 0 4px 4px; max-height: calc(100vh - 70px); }
}
@media (max-width: 480px) {
  #searchHeader { padding: 12px 14px; gap: 8px; }
  #searchInputWrap { padding: 9px 11px; }
  #searchClose { font-size: 11px; padding: 7px 10px; }
}

/* Dark Mode overrides for Global Search Modal */
body.a11y-dark-mode #searchModal {
  background: var(--color-surface) !important;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5) !important;
}

body.a11y-dark-mode #searchHeader {
  border-bottom-color: var(--color-border) !important;
}

body.a11y-dark-mode #searchInputWrap {
  background: var(--color-background) !important;
  border-color: var(--color-border) !important;
}

body.a11y-dark-mode #searchInputWrap:focus-within {
  border-color: var(--green-accent) !important;
}

body.a11y-dark-mode #searchInput {
  color: var(--color-text) !important;
}

body.a11y-dark-mode #searchInput::placeholder {
  color: var(--color-text-secondary) !important;
}

body.a11y-dark-mode #searchIcon {
  color: var(--color-text-secondary) !important;
}

body.a11y-dark-mode #searchClear {
  color: var(--color-text-secondary) !important;
}
body.a11y-dark-mode #searchClear:hover {
  color: var(--color-text) !important;
}

body.a11y-dark-mode #searchClose {
  border-color: var(--color-border) !important;
  color: var(--color-text-secondary) !important;
}
body.a11y-dark-mode #searchClose:hover {
  border-color: var(--gold) !important;
  color: var(--white) !important;
  background: var(--green-mid) !important;
}

body.a11y-dark-mode .search-section-label {
  color: var(--gold) !important;
}

body.a11y-dark-mode .search-result-item {
  color: var(--color-text) !important;
}

body.a11y-dark-mode .search-result-item:hover {
  background: var(--color-background) !important;
}

body.a11y-dark-mode .search-result-item:hover .search-result-title {
  color: var(--gold) !important;
}

body.a11y-dark-mode .search-result-icon {
  background: var(--color-background) !important;
  color: var(--color-text-secondary) !important;
}

body.a11y-dark-mode .search-result-icon.noticia-icon {
  background: rgba(212, 175, 55, 0.15) !important;
  color: var(--gold) !important;
}

body.a11y-dark-mode .search-result-title {
  color: var(--color-text) !important;
}

body.a11y-dark-mode .search-result-desc {
  color: var(--color-text-secondary) !important;
}

body.a11y-dark-mode mark {
  background: rgba(212, 175, 55, 0.3) !important;
  color: var(--white) !important;
}

body.a11y-dark-mode #searchEmptyIcon {
  background: var(--color-background) !important;
  color: var(--color-text-secondary) !important;
}

body.a11y-dark-mode #searchEmpty p {
  color: var(--color-text-secondary) !important;
}
`;

  // ── SEARCH LOGIC ─────────────────────────────────────────────
  function normalize(str) {
    return str.toLowerCase()
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
      .replace(/[^\w\s]/g, ' ');
  }

  function highlight(text, query) {
    if (!query) return text;
    const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const regex = new RegExp(`(${escaped})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
  }

  function searchPages(q) {
    const terms = normalize(q).split(/\s+/).filter(Boolean);
    if (!terms.length) return [];
    return SITE_INDEX.filter(page => {
      const haystack = normalize(page.titulo + ' ' + page.desc + ' ' + page.tags);
      return terms.every(t => haystack.includes(t));
    });
  }

  function searchNoticias(q, noticias) {
    const terms = normalize(q).split(/\s+/).filter(Boolean);
    if (!terms.length) return [];
    return noticias.filter(n => {
      const haystack = normalize((n.titulo || n.title || '') + ' ' + (n.resumen || n.excerpt || ''));
      return terms.every(t => haystack.includes(t));
    }).slice(0, 5);
  }

  let noticiasCache = null;
  function loadNoticiasForSearch(cb) {
    if (noticiasCache) { cb(noticiasCache); return; }
    // Try WP REST API first
    const wpApiUrl = homeUrl + '/wp-json/wp/v2/posts?per_page=50&_fields=id,title,excerpt,link';
    fetch(wpApiUrl)
      .then(r => r.json())
      .then(data => {
        if (Array.isArray(data)) {
          noticiasCache = data.map(p => ({
            id: p.id,
            titulo: p.title ? p.title.rendered : '',
            resumen: p.excerpt ? p.excerpt.rendered.replace(/<[^>]+>/g, '').trim() : '',
            url: p.link || (homeUrl + '/?p=' + p.id)
          }));
        } else {
          noticiasCache = [];
        }
        cb(noticiasCache);
      })
      .catch(() => cb([]));
  }

  function renderResults(q, pageResults, noticiaResults) {
    const container = document.getElementById('searchResults');
    const empty = document.getElementById('searchEmpty');
    const hint = document.getElementById('searchHint');
    if (!container) return;

    const total = pageResults.length + noticiaResults.length;
    if (hint) hint.style.display = 'none';

    if (total === 0) {
      container.innerHTML = '';
      if (empty) {
        empty.hidden = false;
        var emptyTerm = document.getElementById('searchEmptyTerm');
        if (emptyTerm) emptyTerm.textContent = '"' + q + '"';
      }
      return;
    }

    if (empty) empty.hidden = true;
    let html = '';

    // Pages
    if (pageResults.length > 0) {
      html += '<div class="search-section-label">Páginas del sitio</div>';
      pageResults.forEach(p => {
        const titleHL = highlight(p.titulo, q);
        const descHL  = highlight(p.desc, q);
        html += `
          <a class="search-result-item" href="${p.url}">
            <div class="search-result-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="search-result-content">
              <div class="search-result-title">${titleHL}</div>
              <div class="search-result-desc">${descHL}</div>
            </div>
          </a>`;
      });
    }

    // News
    if (noticiaResults.length > 0) {
      html += '<div class="search-section-label">Noticias</div>';
      noticiaResults.forEach(n => {
        const titleHL = highlight(n.titulo || 'Noticia', q);
        const desc = (n.resumen || '').slice(0, 100) + '…';
        const url = n.url || (homeUrl + '/?p=' + n.id);
        html += `
          <a class="search-result-item" href="${url}">
            <div class="search-result-icon noticia-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div class="search-result-content">
              <div class="search-result-title">${titleHL}</div>
              <div class="search-result-desc">${desc}</div>
            </div>
          </a>`;
      });
    }

    container.innerHTML = html;
  }

  let searchDebounce = null;
  function runSearch(q) {
    clearTimeout(searchDebounce);
    const hint = document.getElementById('searchHint');
    const empty = document.getElementById('searchEmpty');
    const results = document.getElementById('searchResults');

    if (!q.trim()) {
      if (hint) hint.style.display = '';
      if (empty) empty.hidden = true;
      if (results) results.innerHTML = '';
      return;
    }

    searchDebounce = setTimeout(() => {
      const pageResults = searchPages(q);
      loadNoticiasForSearch(noticias => {
        const noticiaResults = searchNoticias(q, noticias);
        renderResults(q, pageResults, noticiaResults);
      });
    }, 180);
  }

  // ── SEARCH INIT ─────────────────────────────────────────────
  function injectSearchStyles() {
    if (document.getElementById('fgclm-search-styles')) return;
    const style = document.createElement('style');
    style.id = 'fgclm-search-styles';
    style.textContent = SEARCH_CSS;
    document.head.appendChild(style);
  }

  function initSearch() {
    const overlay   = document.getElementById('searchOverlay');
    const input     = document.getElementById('searchInput');
    const clearBtn  = document.getElementById('searchClear');
    const closeBtn  = document.getElementById('searchClose');
    const toggleBtn = document.getElementById('searchToggle');
    const hint      = document.getElementById('searchHint');

    if (!overlay || !input) return;

    function openSearch() {
      overlay.classList.add('search-open');
      document.body.style.overflow = 'hidden';
      setTimeout(() => input.focus(), 50);
    }
    function closeSearch() {
      overlay.classList.remove('search-open');
      document.body.style.overflow = '';
      input.value = '';
      if (clearBtn) clearBtn.classList.remove('visible');
      var results = document.getElementById('searchResults');
      if (results) results.innerHTML = '';
      var empty = document.getElementById('searchEmpty');
      if (empty) empty.hidden = true;
      if (hint) hint.style.display = '';
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSearch);
    if (closeBtn) closeBtn.addEventListener('click', closeSearch);

    overlay.addEventListener('click', e => {
      if (e.target === overlay) closeSearch();
    });

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && overlay.classList.contains('search-open')) closeSearch();
      // Ctrl+K / Cmd+K
      if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        overlay.classList.contains('search-open') ? closeSearch() : openSearch();
      }
    });

    input.addEventListener('input', () => {
      const val = input.value;
      if (clearBtn) clearBtn.classList.toggle('visible', val.length > 0);
      runSearch(val);
    });

    if (clearBtn) {
      clearBtn.addEventListener('click', () => {
        input.value = '';
        clearBtn.classList.remove('visible');
        var results = document.getElementById('searchResults');
        if (results) results.innerHTML = '';
        var empty = document.getElementById('searchEmpty');
        if (empty) empty.hidden = true;
        if (hint) hint.style.display = '';
        input.focus();
      });
    }
  }

  // ── ACCESSIBILITY PANEL HTML ──────────────────────────────────
  const A11Y_HTML = `
<div id="a11yWidget" class="a11y-widget">
  <!-- Overlay backdrop -->
  <div class="a11y-overlay" id="a11yOverlay"></div>

  <!-- Sidebar Panel -->
  <aside class="a11y-panel" id="a11yPanel" role="dialog" aria-modal="true" aria-label="Panel de accesibilidad">
    <!-- Header -->
    <div class="a11y-panel-header">
      <div class="a11y-panel-header-left">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="a11y-panel-header-icon">
          <circle cx="12" cy="4.5" r="2"/>
          <path d="M12 7.5c-3.5 0-6.5 1.5-6.5 1.5l1 2.5s2.2-1 5.5-1 5.5 1 5.5 1l1-2.5S15.5 7.5 12 7.5z"/>
          <path d="M10.5 12.5l-2 7h2.5l1-3.5 1 3.5H15.5l-2-7"/>
        </svg>
        <span class="a11y-panel-title">Accesibilidad</span>
      </div>
      <button class="a11y-panel-close" id="a11yClose" aria-label="Cerrar panel de accesibilidad">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="a11y-panel-body">
      <!-- Section 1: Display Mode -->
      <div class="a11y-section">
        <div class="a11y-section-label">Modo de visualización</div>
        <div class="a11y-mode-grid">
          <button class="a11y-mode-btn active" id="a11yModeLight" role="radio" aria-checked="true" aria-label="Activar modo claro">
            <span class="a11y-mode-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            </span>
            <span class="a11y-mode-text">Modo Claro</span>
          </button>
          <button class="a11y-mode-btn" id="a11yModeDark" role="radio" aria-checked="false" aria-label="Activar modo oscuro">
            <span class="a11y-mode-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </span>
            <span class="a11y-mode-text">Modo Oscuro</span>
          </button>
        </div>
      </div>

      <!-- Section 2: Text & Readability -->
      <div class="a11y-section">
        <div class="a11y-section-label">Texto y Legibilidad</div>

        <!-- Text Size -->
        <button class="a11y-option" id="a11yTextSize" role="switch" aria-checked="false">
          <span class="a11y-option-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/></svg>
          </span>
          <span class="a11y-option-text">
            <span class="a11y-option-title">Aumentar Texto</span>
            <span class="a11y-option-desc">Incrementa el tamaño de la tipografía un 20%</span>
          </span>
          <span class="a11y-switch" aria-hidden="true"><span class="a11y-switch-knob"></span></span>
        </button>

        <!-- Dyslexia Font -->
        <button class="a11y-option" id="a11yDyslexia" role="switch" aria-checked="false">
          <span class="a11y-option-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/><path d="M7 4l5 16 5-16" opacity="0.3"/></svg>
          </span>
          <span class="a11y-option-text">
            <span class="a11y-option-title">Tipografía para Dislexia</span>
            <span class="a11y-option-desc">Usa la fuente OpenDyslexic en toda la web</span>
          </span>
          <span class="a11y-switch" aria-hidden="true"><span class="a11y-switch-knob"></span></span>
        </button>


      </div>

      <!-- Section 3: Color Blindness -->
      <div class="a11y-section">
        <div class="a11y-section-label">Asistencias Visuales</div>

        <div class="a11y-accordion" id="a11yColorBlindAccordion">
          <button class="a11y-accordion-toggle" id="a11yColorBlindToggle" aria-expanded="false" aria-controls="a11yColorBlindContent">
            <span class="a11y-option-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20z" fill="currentColor" opacity="0.15"/><circle cx="8" cy="10" r="2" fill="currentColor" opacity="0.3"/><circle cx="16" cy="10" r="2" fill="currentColor" opacity="0.3"/></svg>
            </span>
            <span class="a11y-option-text">
              <span class="a11y-option-title">Asistencia para Daltonismo</span>
              <span class="a11y-option-desc">Filtros de color para distintos tipos</span>
            </span>
            <svg class="a11y-accordion-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>

          <div class="a11y-accordion-content" id="a11yColorBlindContent" role="region" aria-labelledby="a11yColorBlindToggle" hidden>
            <div class="a11y-cb-grid">
              <button class="a11y-cb-btn" data-filter="protanopia">
                <span class="a11y-cb-swatch a11y-cb-proto"></span>
                <span class="a11y-cb-name">Protanopía</span>
              </button>
              <button class="a11y-cb-btn" data-filter="deuteranopia">
                <span class="a11y-cb-swatch a11y-cb-deuter"></span>
                <span class="a11y-cb-name">Deuteranopía</span>
              </button>
              <button class="a11y-cb-btn" data-filter="tritanopia">
                <span class="a11y-cb-swatch a11y-cb-trit"></span>
                <span class="a11y-cb-name">Tritanopía</span>
              </button>
              <button class="a11y-cb-btn" data-filter="grayscale">
                <span class="a11y-cb-swatch a11y-cb-gray"></span>
                <span class="a11y-cb-name">Escala de Grises</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="a11y-panel-footer">
      <button class="a11y-reset-btn" id="a11yReset">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
        Restablecer todo
      </button>
    </div>
  </aside>

  <!-- FAB -->
  <button class="a11y-fab" id="a11yToggle" aria-label="Abrir opciones de accesibilidad" tabindex="0">
    <svg class="a11y-fab-icon" viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
      <circle cx="12" cy="4.5" r="2"/>
      <path d="M12 7.5c-3.5 0-6.5 1.5-6.5 1.5l1 2.5s2.2-1 5.5-1 5.5 1 5.5 1l1-2.5S15.5 7.5 12 7.5z"/>
      <path d="M10.5 12.5l-2 7h2.5l1-3.5 1 3.5H15.5l-2-7"/>
    </svg>
  </button>
</div>`;


  function injectA11yWidget() {
    // Don't inject if it already exists
    if (document.getElementById('a11yWidget')) return;
    document.body.insertAdjacentHTML('beforeend', A11Y_HTML);
  }

  function initHamburger() {
    const toggle = document.getElementById("navToggle");
    const navLinks = document.getElementById("navLinks");
    if (!toggle || !navLinks) return;

    toggle.addEventListener("click", () => {
      const isOpen = navLinks.classList.toggle("nav-open");
      toggle.classList.toggle("active", isOpen);
      toggle.setAttribute("aria-expanded", isOpen);
      document.body.style.overflow = isOpen ? "hidden" : "";
    });

    // Mobile dropdown toggle — click to expand/collapse on touch devices
    navLinks.querySelectorAll("li.dropdown > a").forEach((dropdownLink) => {
      dropdownLink.addEventListener("click", (e) => {
        e.preventDefault(); 
        
        const parent = dropdownLink.closest("li.dropdown");
        const isVisible = parent.classList.contains("active-mobile") || parent.classList.contains("active-dropdown");
        
        // Close all other open submenus first
        navLinks.querySelectorAll("li.dropdown").forEach((m) => {
          if (m !== parent) {
            m.classList.remove("active-mobile");
            m.classList.remove("active-dropdown");
          }
        });
        
        if (!isVisible) {
          parent.classList.add("active-mobile");
          parent.classList.add("active-dropdown");
        } else {
          parent.classList.remove("active-mobile");
          parent.classList.remove("active-dropdown");
        }
      });
    });

    // Add App Torneos click handler
    const appTorneosBtn = document.querySelector(".boton-app-nav");
    if (appTorneosBtn) {
      appTorneosBtn.addEventListener("click", (e) => {
        e.preventDefault();
        const parent = appTorneosBtn.closest(".nav-app-dropdown");
        if (parent) {
          parent.classList.toggle("active-dropdown");
        }
      });
      // Close app torneos when clicking outside
      document.addEventListener("click", (e) => {
        if (!e.target.closest(".nav-app-dropdown")) {
          document.querySelectorAll(".nav-app-dropdown").forEach(d => d.classList.remove("active-dropdown"));
        }
      });
    }

    // Close menu when clicking a non-dropdown link
    navLinks.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        const parent = link.closest("li");
        if (!parent || !parent.classList.contains("dropdown")) {
          navLinks.classList.remove("nav-open");
          toggle.classList.remove("active");
          toggle.setAttribute("aria-expanded", "false");
          document.body.style.overflow = "";
          // Reset any open submenus
          navLinks.querySelectorAll("li.dropdown").forEach((m) => {
            m.classList.remove("active-mobile");
          });
        }
      });
    });

    // Close on submenu link click (navigate away)
    navLinks.querySelectorAll(".dropdown-menu a").forEach((subLink) => {
      subLink.addEventListener("click", () => {
        navLinks.classList.remove("nav-open");
        toggle.classList.remove("active");
        toggle.setAttribute("aria-expanded", "false");
        document.body.style.overflow = "";
      });
    });

    document.addEventListener("click", (e) => {
      if (!e.target.closest("nav") && navLinks.classList.contains("nav-open")) {
        navLinks.classList.remove("nav-open");
        toggle.classList.remove("active");
        toggle.setAttribute("aria-expanded", "false");
        document.body.style.overflow = "";
        navLinks.querySelectorAll("li.dropdown").forEach((m) => {
          m.classList.remove("active-mobile");
        });
      }
    });
  }

  function initNavScroll() {
    const nav = document.getElementById("mainNav");
    if (!nav) return;
    window.addEventListener(
      "scroll",
      () => {
        nav.style.boxShadow =
          window.scrollY > 10
            ? "0 4px 24px rgba(0,0,0,0.35)"
            : "0 2px 20px rgba(0,0,0,0.25)";
      },
      { passive: true },
    );
  }

  function initScrollReveal() {
    // Elementos que se animan al entrar en pantalla
    const SELECTORS = [
      'section',
      '.page-hero',
      '.section-header',
      '.area-card',
      '.evento-card',
      '.noticia-card',
      '.modalidad-card',
      '.precio-card',
      '.profesor-card',
      '.doc-block',
      '.form-card',
      '.pat-featured-card',
      '.pat-logo',
      '.ranking-card',
      '.club-card',
      'article',
      '.highlight-box',
    ];

    // Selecciona todos los elementos que coincidan y aún no tengan data-reveal
    const candidates = document.querySelectorAll(SELECTORS.join(','));

    candidates.forEach(function (el) {
      if (el.hasAttribute('data-reveal')) return; // ya marcado manualmente

      // Decide si es hijo de un grid → stagger
      const parent = el.parentElement;
      const siblings = parent
        ? Array.from(parent.children).filter(function (c) {
          return c.matches(SELECTORS.join(','));
        })
        : [];
      const idx = siblings.indexOf(el);

      el.setAttribute('data-reveal', '');
      if (idx > 0 && idx <= 5) {
        el.setAttribute('data-reveal-delay', String(idx));
      }
    });

    // IntersectionObserver: añade .revealed cuando el elemento entra en pantalla
    if (!('IntersectionObserver' in window)) {
      // Fallback: muestra todo si el navegador no soporta IO
      document.querySelectorAll('[data-reveal]').forEach(function (el) {
        el.classList.add('revealed');
      });
      return;
    }

    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            observer.unobserve(entry.target); // se anima solo una vez
          }
        });
      },
      { threshold: 0.08, rootMargin: '0px 0px -40px 0px' }
    );

    document.querySelectorAll('[data-reveal]').forEach(function (el) {
      observer.observe(el);
    });
  }

  // ── LANGUAGE SELECTOR (Google Translate Integration) ──────────────
  function initLangSelector() {
    const selector = document.getElementById('langSelector');
    const toggle = document.getElementById('langToggle');
    const dropdown = document.getElementById('langDropdown');
    const current = document.getElementById('langCurrent');
    if (!selector || !toggle || !dropdown) return;

    const savedLang = localStorage.getItem('fgclm-lang') || 'es';

    function triggerTranslation(lang) {
      const gtCombo = document.querySelector('.goog-te-combo');
      if (gtCombo) {
        gtCombo.value = lang;
        gtCombo.dispatchEvent(new Event('change'));
      }
    }

    function loadGoogleTranslate(targetLang) {
      if (!document.getElementById('google_translate_element')) {
        const gtDiv = document.createElement('div');
        gtDiv.id = 'google_translate_element';
        document.body.appendChild(gtDiv);

        window.googleTranslateElementInit = function() {
          new google.translate.TranslateElement({
            pageLanguage: 'es', 
            includedLanguages: 'es,en', 
            autoDisplay: false
          }, 'google_translate_element');
          
          // Poll for the combo box and translate once ready
          const checkExist = setInterval(function() {
            if (document.querySelector('.goog-te-combo')) {
              clearInterval(checkExist);
              triggerTranslation(targetLang);
            }
          }, 100);
        };

        const gtScript = document.createElement('script');
        gtScript.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
        document.body.appendChild(gtScript);
      } else {
        triggerTranslation(targetLang);
      }
    }

    // Auto-translate on load if preference is English
    if (savedLang === 'en') {
      loadGoogleTranslate('en');
    }

    // Sync UI with current language
    current.textContent = savedLang.toUpperCase();
    dropdown.querySelectorAll('.lang-option').forEach(function(opt) {
      opt.classList.toggle('active', opt.dataset.lang === savedLang);
    });

    function openDropdown() {
      selector.classList.add('lang-open');
      toggle.setAttribute('aria-expanded', 'true');
    }
    function closeDropdown() {
      selector.classList.remove('lang-open');
      toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function(e) {
      e.stopPropagation();
      selector.classList.contains('lang-open') ? closeDropdown() : openDropdown();
    });

    // Keyboard support
    toggle.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        selector.classList.contains('lang-open') ? closeDropdown() : openDropdown();
      }
      if (e.key === 'Escape') closeDropdown();
    });

    dropdown.querySelectorAll('.lang-option').forEach(function(opt) {
      opt.addEventListener('click', function(e) {
        e.stopPropagation();
        const lang = opt.dataset.lang; // 'es' or 'en'
        
        if (lang === localStorage.getItem('fgclm-lang')) {
          closeDropdown();
          return; // No change needed
        }

        // Save preference
        localStorage.setItem('fgclm-lang', lang);

        if (lang === 'en') {
          // Switch to English dynamically without reloading
          loadGoogleTranslate('en');
          
          // Update UI immediately
          current.textContent = lang.toUpperCase();
          dropdown.querySelectorAll('.lang-option').forEach(function(o) {
            o.classList.toggle('active', o === opt);
          });
          closeDropdown();
        } else {
          // Switch back to Spanish (original). Cleanest way is to reload.
          // Since localStorage is 'es', GT script won't even load.
          window.location.reload();
        }
      });
      
      opt.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          opt.click();
        }
        if (e.key === 'Escape') { closeDropdown(); toggle.focus(); }
      });
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('#langSelector')) closeDropdown();
    });
  }

  // ── ACCESSIBILITY WIDGET (EXPANDED PANEL) ──────────────────────
  function initA11yWidget() {
    var fab = document.getElementById('a11yToggle');
    var panel = document.getElementById('a11yPanel');
    var widget = document.getElementById('a11yWidget');
    var overlay = document.getElementById('a11yOverlay');
    var closeBtn = document.getElementById('a11yClose');
    if (!fab || !panel) return;

    var isOpen = false;

    // ── SVG filter for color blindness (injected once into body) ──
    if (!document.getElementById('a11yCBFilters')) {
      var svgNS = 'http://www.w3.org/2000/svg';
      var svgEl = document.createElementNS(svgNS, 'svg');
      svgEl.setAttribute('id', 'a11yCBFilters');
      svgEl.setAttribute('style', 'position:absolute;width:0;height:0;overflow:hidden;');
      svgEl.setAttribute('aria-hidden', 'true');
      svgEl.innerHTML = '<defs>' +
        '<filter id="a11y-protanopia"><feColorMatrix type="matrix" values="0.567,0.433,0,0,0 0.558,0.442,0,0,0 0,0.242,0.758,0,0 0,0,0,1,0"/></filter>' +
        '<filter id="a11y-deuteranopia"><feColorMatrix type="matrix" values="0.625,0.375,0,0,0 0.7,0.3,0,0,0 0,0.3,0.7,0,0 0,0,0,1,0"/></filter>' +
        '<filter id="a11y-tritanopia"><feColorMatrix type="matrix" values="0.95,0.05,0,0,0 0,0.433,0.567,0,0 0,0.475,0.525,0,0 0,0,0,1,0"/></filter>' +
        '<filter id="a11y-grayscale"><feColorMatrix type="saturate" values="0"/></filter>' +
        '</defs>';
      document.body.appendChild(svgEl);
    }

    // ── Load OpenDyslexic font ──
    if (!document.getElementById('a11yDyslexicFont')) {
      var link = document.createElement('link');
      link.id = 'a11yDyslexicFont';
      link.rel = 'stylesheet';
      link.href = 'https://fonts.cdnfonts.com/css/opendyslexic';
      document.head.appendChild(link);
    }

    // ── State management ──
    var state = {
      darkMode: localStorage.getItem('a11y-darkMode') === 'true',
      textSize: localStorage.getItem('a11y-textSize') === 'true',
      dyslexia: localStorage.getItem('a11y-dyslexia') === 'true',
      colorFilter: localStorage.getItem('a11y-colorFilter') || 'none'
    };

    function applyState() {
      // Dark mode
      document.body.classList.toggle('a11y-dark-mode', state.darkMode);
      var lightBtn = document.getElementById('a11yModeLight');
      var darkBtn = document.getElementById('a11yModeDark');
      if (lightBtn && darkBtn) {
        lightBtn.classList.toggle('active', !state.darkMode);
        lightBtn.setAttribute('aria-checked', !state.darkMode);
        darkBtn.classList.toggle('active', state.darkMode);
        darkBtn.setAttribute('aria-checked', state.darkMode);
      }

      // Text size
      document.documentElement.style.fontSize = state.textSize ? '120%' : '';
      var tsEl = document.getElementById('a11yTextSize');
      if (tsEl) {
        tsEl.classList.toggle('active', state.textSize);
        tsEl.setAttribute('aria-checked', state.textSize);
      }

      // Dyslexia font
      document.body.classList.toggle('a11y-dyslexic-font', state.dyslexia);
      var dyEl = document.getElementById('a11yDyslexia');
      if (dyEl) {
        dyEl.classList.toggle('active', state.dyslexia);
        dyEl.setAttribute('aria-checked', state.dyslexia);
      }



      // Color blindness filter
      var filterClasses = ['a11y-filter-protanopia', 'a11y-filter-deuteranopia', 'a11y-filter-tritanopia', 'a11y-filter-grayscale'];
      filterClasses.forEach(function(c) { document.documentElement.classList.remove(c); });
      if (state.colorFilter !== 'none') {
        document.documentElement.classList.add('a11y-filter-' + state.colorFilter);
      }
      // Update CB buttons
      document.querySelectorAll('.a11y-cb-btn').forEach(function(btn) {
        btn.classList.toggle('active', btn.dataset.filter === state.colorFilter);
      });
    }

    function saveState() {
      localStorage.setItem('a11y-darkMode', state.darkMode);
      localStorage.setItem('a11y-textSize', state.textSize);
      localStorage.setItem('a11y-dyslexia', state.dyslexia);
      localStorage.setItem('a11y-colorFilter', state.colorFilter);
    }

    // Apply on load
    applyState();

    // ── Panel open/close ──
    function openPanel() {
      isOpen = true;
      widget.classList.add('a11y-open');
      fab.setAttribute('aria-expanded', 'true');
      fab.setAttribute('aria-label', 'Cerrar opciones de accesibilidad');
      document.body.style.overflow = 'hidden';
      // Focus the close button for keyboard users
      setTimeout(function() { closeBtn && closeBtn.focus(); }, 200);
    }
    function closePanel() {
      isOpen = false;
      widget.classList.remove('a11y-open');
      fab.setAttribute('aria-expanded', 'false');
      fab.setAttribute('aria-label', 'Abrir opciones de accesibilidad');
      document.body.style.overflow = '';
      fab.focus();
    }

    fab.addEventListener('click', function() { isOpen ? closePanel() : openPanel(); });
    fab.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); isOpen ? closePanel() : openPanel(); }
    });
    if (closeBtn) closeBtn.addEventListener('click', closePanel);
    if (overlay) overlay.addEventListener('click', closePanel);

    // Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && isOpen) closePanel();
    });

    // ── Dark / Light mode ──
    var lightBtn = document.getElementById('a11yModeLight');
    var darkBtn = document.getElementById('a11yModeDark');
    if (lightBtn) lightBtn.addEventListener('click', function() {
      state.darkMode = false; saveState(); applyState();
    });
    if (darkBtn) darkBtn.addEventListener('click', function() {
      state.darkMode = true; saveState(); applyState();
    });

    // ── Switch options ──
    document.getElementById('a11yTextSize').addEventListener('click', function() {
      state.textSize = !state.textSize; saveState(); applyState();
    });
    document.getElementById('a11yDyslexia').addEventListener('click', function() {
      state.dyslexia = !state.dyslexia; saveState(); applyState();
    });


    // ── Color blindness accordion ──
    var cbToggle = document.getElementById('a11yColorBlindToggle');
    var cbContent = document.getElementById('a11yColorBlindContent');
    if (cbToggle && cbContent) {
      cbToggle.addEventListener('click', function() {
        var expanded = cbToggle.getAttribute('aria-expanded') === 'true';
        cbToggle.setAttribute('aria-expanded', !expanded);
        cbContent.hidden = expanded;
        cbToggle.closest('.a11y-accordion').classList.toggle('open', !expanded);
      });
    }

    // ── Color blindness buttons ──
    document.querySelectorAll('.a11y-cb-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var filter = btn.dataset.filter;
        state.colorFilter = (state.colorFilter === filter) ? 'none' : filter;
        saveState(); applyState();
      });
    });

    // ── Reset ──
    document.getElementById('a11yReset').addEventListener('click', function() {
      state.darkMode = false;
      state.textSize = false;
      state.dyslexia = false;
      state.colorFilter = 'none';
      saveState(); applyState();
    });
  }

  function initResultadosLinks() {
    document.addEventListener('click', function (e) {
      const a = e.target.closest('a[href*="plantilla_resultado.html"]');
      if (!a) return;
      e.preventDefault();

      const name = a.textContent.trim();
      const row = a.closest('tr');
      let date = '';
      let location = '';

      if (row) {
        const dateEl = row.querySelector('.date-badge');
        if (dateEl) date = dateEl.textContent.trim();
        const locEl = row.querySelector('.location-text');
        if (locEl) location = locEl.textContent.trim();
      }

      let fechaLugar = '';
      if (date && location) {
        fechaLugar = date + ' — ' + location;
      } else if (date) {
        fechaLugar = date;
      } else if (location) {
        fechaLugar = location;
      }

      let targetUrl = homeUrl + '/plantilla-resultado/?torneo_name=' + encodeURIComponent(name);
      if (fechaLugar) {
        targetUrl += '&fecha_lugar=' + encodeURIComponent(fechaLugar);
      }

      window.location.href = targetUrl;
    });
  }

  function initSponsorFallback() {
    const logos = document.querySelectorAll(".pat-logo img");
    logos.forEach(function(img) {
      function applyFallback() {
        if (img.getAttribute("data-fallback-applied")) return;
        img.setAttribute("data-fallback-applied", "true");
        
        const text = img.getAttribute("data-fallback");
        const fontSize = img.getAttribute("data-font-size") || "18px";
        const fontFamily = img.getAttribute("data-font-family") || "var(--font-display)";
        const fontWeight = img.getAttribute("data-font-weight") || "700";
        if (text) {
          const span = document.createElement("span");
          span.className = "pat-logo-fallback-span";
          span.style.fontFamily = fontFamily;
          span.style.fontWeight = fontWeight;
          span.style.fontSize = fontSize;
          span.textContent = text;
          
          span.style.display = "inline-flex";
          span.style.alignItems = "center";
          span.style.justifyContent = "center";
          
          img.parentNode.replaceChild(span, img);
        }
      }

      if (img.complete && img.naturalWidth === 0) {
        applyFallback();
      } else {
        img.addEventListener("error", applyFallback);
      }
    });
  }

  function init() {
    const existingNav = document.getElementById("mainNav") || document.querySelector("nav");
    const isStatic = window.location.protocol === 'file:' || 
                     (existingNav && existingNav.outerHTML.includes('<?php'));

    if (isStatic) {
      injectNav();
      injectFooter();
      markActiveLink();
    } else {
      const logoLink = document.querySelector(".nav-logo");
      if (logoLink && logoLink.getAttribute("href") && logoLink.getAttribute("href").includes('<?php')) {
        logoLink.setAttribute("href", homeUrl + '/');
      }
    }

    injectSearchStyles();
    injectA11yWidget();
    initHamburger();
    initNavScroll();
    initLangSelector();
    initSearch();
    initResultadosLinks();
    initA11yWidget();
    initSponsorFallback();
    // Scroll reveal se lanza después de que el DOM esté completo
    // (pequeño timeout para que otros scripts como noticias_index.js puedan insertar tarjetas)
    setTimeout(initScrollReveal, 80);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();


