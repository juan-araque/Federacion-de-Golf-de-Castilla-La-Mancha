<?php
/**
 * Template Name: En Vivo
 *
 * @package FGCLM_Theme_v071
 */

get_header();

$enlace_en_vivo = get_post_meta( get_the_ID(), '_en_vivo_link', true );
$enlace_boton   = get_post_meta( get_the_ID(), '_en_vivo_button_link', true );
$multitorneos_raw = get_post_meta( get_the_ID(), '_en_vivo_multitorneos', true );

$torneos = array();

// Parse multitorneos if set
if ( ! empty( $multitorneos_raw ) ) {
    $lines = explode( "\n", $multitorneos_raw );
    foreach ( $lines as $line ) {
        $line = trim( $line );
        if ( empty( $line ) ) continue;
        
        $parts = explode( '|', $line );
        if ( count( $parts ) >= 2 ) {
            $name = trim( $parts[0] );
            $iframe = trim( $parts[1] );
            $button = isset( $parts[2] ) ? trim( $parts[2] ) : $iframe;
            
            $torneos[] = array(
                'name' => $name,
                'iframe_url' => $iframe,
                'button_url' => $button
            );
        }
    }
}

// Add single tournament if set and not already in list
if ( ! empty( $enlace_en_vivo ) ) {
    $exists = false;
    foreach ( $torneos as $t ) {
        if ( $t['iframe_url'] === $enlace_en_vivo ) {
            $exists = true;
            break;
        }
    }
    if ( ! $exists ) {
        array_unshift( $torneos, array(
            'name' => 'Torneo Principal',
            'iframe_url' => $enlace_en_vivo,
            'button_url' => ! empty( $enlace_boton ) ? $enlace_boton : $enlace_en_vivo
        ) );
    }
}

// Fallback if empty
if ( empty( $torneos ) ) {
    $torneos[] = array(
        'name' => 'Plataforma de Torneos',
        'iframe_url' => 'https://torneos.fgclmgolf.com',
        'button_url' => 'https://torneos.fgclmgolf.com'
    );
}

$has_multiple = count( $torneos ) > 1;
$active_torneo = $torneos[0];
?>

<style>
body { padding-top: 70px; }
</style>

<div class="page-hero">
  <div class="page-hero-content">
    <div class="page-hero-label">Competiciones</div>
    <h1><em>En Vivo</em></h1>
    <p>Sigue los resultados y clasificaciones de los torneos en tiempo real.</p>
  </div>
</div>

<section class="live-scoring-section">
  <div style="width: 100%; display: flex; flex-direction: column; align-items: center; gap: 15px;">
    
    <?php if ( $has_multiple ) : ?>
      <div class="selector-torneos-envivo" style="margin-bottom: 25px; width: 100%; max-width: 480px; text-align: center; display: flex; align-items: center; justify-content: center; gap: 12px; margin-left: auto; margin-right: auto; padding: 0 1rem;">
        <label for="select-torneo" style="font-family: var(--font-body); font-weight: 600; color: var(--green-dark); font-size: 15px; white-space: nowrap;">Seleccionar Torneo:</label>
        <select id="select-torneo" style="padding: 10px 16px; border-radius: 6px; border: 1.5px solid var(--green-accent); background: var(--white); font-family: var(--font-body); font-size: 14px; font-weight: 500; color: var(--green-dark); cursor: pointer; outline: none; transition: border-color 0.2s; width: 100%; max-width: 320px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
          <?php foreach ( $torneos as $index => $t ) : ?>
            <option value="<?php echo $index; ?>"><?php echo esc_html( $t['name'] ); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <script type="text/javascript">
        window.liveTournaments = <?php echo json_encode( $torneos ); ?>;
        document.addEventListener('DOMContentLoaded', function() {
            var select = document.getElementById('select-torneo');
            if (select) {
                select.addEventListener('change', function() {
                    var index = this.value;
                    var torneo = window.liveTournaments[index];
                    if (torneo) {
                        var iframeContainer = document.getElementById('live-iframe-container');
                        var cardContainer = document.getElementById('live-card-container');
                        var hasIframe = torneo.iframe_url && torneo.iframe_url !== 'https://torneos.fgclmgolf.com';
                        
                        if (hasIframe) {
                            var iframe = document.querySelector('.live-iframe');
                            if (iframe) {
                                iframe.src = torneo.iframe_url;
                            }
                            var fallbackLink = document.querySelector('.live-iframe-fallback-link a');
                            if (fallbackLink) {
                                fallbackLink.href = torneo.button_url;
                            }
                            iframeContainer.style.display = 'flex';
                            cardContainer.style.display = 'none';
                        } else {
                            var btnCta = document.querySelector('.btn-live-cta');
                            if (btnCta) {
                                btnCta.href = torneo.button_url;
                            }
                            iframeContainer.style.display = 'none';
                            cardContainer.style.display = 'block';
                        }
                    }
                });
            }
        });
      </script>
    <?php endif; ?>

    <!-- Iframe Container -->
    <div id="live-iframe-container" style="width: 100%; display: <?php echo ( ! empty( $active_torneo['iframe_url'] ) && $active_torneo['iframe_url'] !== 'https://torneos.fgclmgolf.com' ) ? 'flex' : 'none'; ?>; flex-direction: column; align-items: center; gap: 15px;">
      <div class="live-iframe-wrapper">
        <iframe src="<?php echo esc_url( $active_torneo['iframe_url'] ); ?>" class="live-iframe" allowfullscreen></iframe>
      </div>
      <div class="live-iframe-fallback-link">
        <p>¿Tienes problemas para ver la clasificación? <a href="<?php echo esc_url( $active_torneo['button_url'] ); ?>" target="_blank" rel="noopener noreferrer">Abre la clasificación directa en una nueva pestaña</a>.</p>
      </div>
    </div>

    <!-- Card Container (Fallback) -->
    <div id="live-card-container" style="width: 100%; display: <?php echo ( empty( $active_torneo['iframe_url'] ) || $active_torneo['iframe_url'] === 'https://torneos.fgclmgolf.com' ) ? 'block' : 'none'; ?>;">
      <div class="live-scoring-card" style="margin: 0 auto;">
        <div class="live-badge">
          <span class="live-dot"></span> EN VIVO
        </div>
        
        <h2 class="live-title">Sigue el Torneo En Directo</h2>
        
        <p class="live-subtitle">
          Accede a las clasificaciones, horarios de salida y resultados hoyo por hoyo actualizados al minuto a través de la plataforma oficial de la FGCLM.
        </p>
        
        <div class="live-actions">
          <a href="<?php echo esc_url( $active_torneo['button_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="btn-live-cta">
            Ver Clasificación en Directo
          </a>
        </div>
      </div>
    </div>

  </div>
</section>

<?php
get_footer();
