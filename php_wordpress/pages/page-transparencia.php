<?php
/**
 * Template Name: Transparencia
 *
 * @package FGCLM_Theme_v071
 */

get_header();
?>

<style>
body { padding-top: 70px; }
    .transp-section { padding: 64px 6rem; max-width: 1100px; margin: 0 auto; }
    .block-title {
      font-family: var(--font-display);
      font-size: 1.4rem; font-weight: 700;
      color: var(--green-dark);
      margin-bottom: 24px;
      padding-bottom: 12px;
      border-bottom: 2px solid var(--gold);
    }
    .intro-text { font-size: 15px; color: var(--gray-600); line-height: 1.8; max-width: 720px; margin-bottom: 48px; }
    .doc-list {
      margin-bottom: 48px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .doc-item {
      display: flex;
      align-items: center;
      gap: 20px;
      padding: 22px 32px;
      background: var(--gray-50);
      border: 1px solid var(--gray-100);
      border-radius: 10px;
      transition: all 0.2s ease;
    }
    .doc-icon { font-size: 24px; flex-shrink: 0; }
    .doc-info { flex: 1; }
    .doc-nombre { font-weight: 600; color: var(--green-dark); font-size: 14px; }
    .doc-tipo { font-size: 11px; color: var(--gray-300); text-transform: uppercase; letter-spacing: 0.8px; margin-top: 2px; }
    .doc-anyo {
      font-size: 11px; font-weight: 700;
      color: var(--gray-300);
      background: var(--gray-100);
      padding: 4px 10px;
      border-radius: 2px;
      flex-shrink: 0;
    }
    a.doc-item {
      text-decoration: none;
      color: inherit;
    }
    a.doc-item:hover {
      background: var(--gray-100);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    /* Adaptaciones para Modo Oscuro */
    body.a11y-dark-mode .doc-item:hover {
      background: var(--green-mid) !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }
    body.a11y-dark-mode .doc-anyo {
      background: rgba(201, 168, 76, 0.15) !important;
      color: var(--gold-light) !important;
    }
    .info-box {
      background: var(--gray-50);
      border: 1px solid var(--gray-100);
      padding: 32px 36px;
      margin-bottom: 48px;
    }
    .info-box p { font-size: 14px; color: var(--gray-600); line-height: 1.7; margin-bottom: 12px; }
    .info-box p:last-child { margin-bottom: 0; }
    @media (max-width: 1100px) { .transp-section { padding: 48px 2.5rem; } }
    @media (max-width: 640px) { .transp-section { padding: 32px 1.25rem; } }
</style>

<div class="page-hero">
  <div class="page-hero-content">
    <div class="page-hero-label">La Federación</div>
    <h1><em>Transparencia</em></h1>
    <p>Información institucional, económica y de gestión de la Federación de Golf de Castilla-La Mancha.</p>
  </div>
</div>

<div class="transp-section">
  <p class="intro-text">
    La Federación de Golf de Castilla-La Mancha está comprometida con los principios de transparencia, publicidad activa y buen gobierno deportivo. En esta sección encontrarás los documentos e información institucional que la federación pone a disposición de sus federados, clubes y ciudadanos en general, en cumplimiento de la legislación vigente en materia de transparencia.
  </p>

  <?php
  // Define helper function to output the SVG based on the icon name
  if ( ! function_exists( 'fgclm_get_transp_icon_svg' ) ) {
      function fgclm_get_transp_icon_svg( $icon_name ) {
          switch ( $icon_name ) {
              case 'dolar':
              case 'presupuesto':
                  return '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; color:var(--gold);"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>';
              case 'usuarios':
              case 'censo':
                  return '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; color:var(--gold);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
              case 'archivo':
              case 'balance':
              case 'documento':
                  return '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; color:var(--gold);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>';
              case 'actividad':
              case 'ejecucion':
              case 'linea':
              default:
                  return '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; color:var(--gold);"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>';
          }
      }
  }

  // 1. Static default documents
  $docs_by_year = array(
      '2026' => array(
          array(
              'nombre' => 'Presupuesto 2026',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2026/04/PRESUPUESTO-2026.pdf',
              'icon'   => 'dolar',
              'tipo'   => 'Documento PDF'
          ),
      ),
      '2025' => array(
          array(
              'nombre' => 'Presupuesto 2025',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2025/03/PRESUPUESTO-fgclm-2025.pdf',
              'icon'   => 'dolar',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Censo de Jugadores 2025',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2026/02/CENSO-JUGADORES-TORNEOS-FGCLM-2025.pdf',
              'icon'   => 'usuarios',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Pérdidas y Ganancias 2025',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2026/04/Cuenta-Perdidas-y-Ganancias-2025.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Situación 2025',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2026/04/Balance-Situacion-2025.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Ejecución de Presupuesto 2025',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2026/04/EJECUCION-INGRESOS-GASTOS-2025.pdf',
              'icon'   => 'actividad',
              'tipo'   => 'Documento PDF'
          ),
      ),
      '2024' => array(
          array(
              'nombre' => 'Presupuesto 2024',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2024/02/Presupuesto-FGCLM-2024.pdf',
              'icon'   => 'dolar',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Censo de Jugadores 2024',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2025/02/CENSO-ELECTORES-FGCLM-2024.pdf',
              'icon'   => 'usuarios',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Pérdidas y Ganancias 2024',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2025/03/CUENTA-DE-PERDIDAS-Y-GANANCIAS-fgclm-2024.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Situación 2024',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2025/03/BALANCE-fgclm-2024.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Ejecución de Presupuesto 2024',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2025/03/Ejecucion-presupuestaria-fgclm-2024.pdf',
              'icon'   => 'actividad',
              'tipo'   => 'Documento PDF'
          ),
      ),
      '2023' => array(
          array(
              'nombre' => 'Presupuesto 2023',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2023/04/Presupuesto-2023.pdf',
              'icon'   => 'dolar',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Pérdidas y Ganancias 2023',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2024/02/BALANCE-PERDIDAS-Y-GANANCIAS-FGCLM-2023.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Situación 2023',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2024/02/BALANCES-DE-SITUACION-FGCLM-2023.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Ejecución de Presupuesto 2023',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2024/02/Ejecucion-presupuesto-FGCLM-2023.pdf',
              'icon'   => 'actividad',
              'tipo'   => 'Documento PDF'
          ),
      ),
      '2022' => array(
          array(
              'nombre' => 'Presupuesto 2022',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2022/04/Presupuesto-fgclm-Asamblea-2022.pdf',
              'icon'   => 'dolar',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Pérdidas y Ganancias 2022',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2023/04/Balance-de-P-Y-G-2022.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Situación 2022',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2023/04/BALANCE-SITUACION-2022.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Ejecución de Presupuesto 2022',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2023/04/Ejecucion-presupuestaria-2022.pdf',
              'icon'   => 'actividad',
              'tipo'   => 'Documento PDF'
          ),
      ),
      '2021' => array(
          array(
              'nombre' => 'Presupuesto 2021',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2021/11/Presupuesto-fgclm-2021.pdf',
              'icon'   => 'dolar',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Cuenta de Pérdidas y Ganancias 2021',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2022/04/PERDIDAS-Y-GANANCIAS-fgclm-2021.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Situación 2021',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2022/04/BALANCE-SITUACION-fgclm-2021.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Ejecución de Presupuesto 2021',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2022/04/Ejecucion-del-presupuesto-fgclm-2021.pdf',
              'icon'   => 'actividad',
              'tipo'   => 'Documento PDF'
          ),
      ),
      '2020' => array(
          array(
              'nombre' => 'Cuenta de Pérdidas y Ganancias 2020',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2021/11/BALANCE-PERDIDAS-Y-GANANCIAS-fgclm-2020.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
          array(
              'nombre' => 'Balance de Situación 2020',
              'url'    => 'https://www.fedgolfclm.com/wp-content/uploads/2021/11/BALANCE-DE-SITUACION-fgclm-2020.pdf',
              'icon'   => 'archivo',
              'tipo'   => 'Documento PDF'
          ),
      ),
  );

  // 2. Query dynamic documents from WP Database
  $transp_query = new WP_Query( array(
      'post_type'      => 'transparencia_doc',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
  ) );

  if ( $transp_query->have_posts() ) {
      while ( $transp_query->have_posts() ) {
          $transp_query->the_post();
          $p_id = get_the_ID();
          $year     = get_post_meta( $p_id, '_transp_doc_year', true );
          $pdf_file = get_post_meta( $p_id, '_transp_pdf_file', true );
          $icon     = get_post_meta( $p_id, '_transp_doc_icon', true );
          
          if ( ! empty( $year ) && ! empty( $pdf_file ) ) {
              $year = trim( $year );
              if ( ! isset( $docs_by_year[$year] ) ) {
                  $docs_by_year[$year] = array();
              }
              // Add to the beginning of the array so new documents appear first within their year
              array_unshift( $docs_by_year[$year], array(
                  'nombre' => get_the_title(),
                  'url'    => $pdf_file,
                  'icon'   => ! empty( $icon ) ? $icon : 'archivo',
                  'tipo'   => 'Documento PDF'
              ) );
          }
      }
      wp_reset_postdata();
  }

  // Sort years descending
  krsort( $docs_by_year );

  // 3. Render documents sorted by year
  foreach ( $docs_by_year as $year => $docs ) :
      if ( empty( $docs ) ) continue;
      ?>
      <div class="block-title" style="margin-top: 48px; font-size: 20px;">Año <?php echo esc_html( $year ); ?></div>
      <div class="doc-list" style="margin-bottom: 24px;">
          <?php foreach ( $docs as $doc ) : ?>
              <a href="<?php echo esc_url( $doc['url'] ); ?>" target="_blank" class="doc-item">
                  <div class="doc-icon">
                      <?php echo fgclm_get_transp_icon_svg( $doc['icon'] ); ?>
                  </div>
                  <div class="doc-info">
                      <div class="doc-nombre"><?php echo esc_html( $doc['nombre'] ); ?></div>
                      <div class="doc-tipo"><?php echo esc_html( $doc['tipo'] ); ?></div>
                  </div>
                  <div class="doc-anyo"><?php echo esc_html( $year ); ?></div>
              </a>
          <?php endforeach; ?>
      </div>
  <?php endforeach; ?>

<div class="block-title">Solicitud de Información</div>
  <div class="info-box">
    <p>
      En virtud de la Ley 19/2013, de 9 de diciembre, de transparencia, acceso a la información pública y buen gobierno, cualquier ciudadano puede solicitar información pública a la Federación de Golf de Castilla-La Mancha.
    </p>
    <p>
      Las solicitudes de información pueden presentarse por correo electrónico dirigido a la Secretaría General, indicando claramente los datos del solicitante, la información que se solicita y la forma de recibir la respuesta.
    </p>
    <p>
      <strong>Contacto para solicitudes de transparencia:</strong><br>
      federacion@fedgolfclm.com · Teléfono: 949-262300<br>
      Plaza de España 1, Primera Planta, Local 8 · 19200 Azuqueca de Henares (Guadalajara)
    </p>
  </div>
</div>

<?php
get_footer();
