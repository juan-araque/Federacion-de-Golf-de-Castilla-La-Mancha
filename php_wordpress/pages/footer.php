<?php
/**
 * Footer template for the FGCLM Theme
 *
 * @package FGCLM_Theme_v071
 */
?>

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
        <li><a href="<?php echo esc_url( home_url( '/presidente/' ) ); ?>">Bienvenida del Presidente</a></li>
        <li><a href="<?php echo esc_url( home_url( '/comites/' ) ); ?>">Comités y Delegaciones</a></li>
        <li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Contacto</a></li>
      </ul>
    </div>
    <div>
      <div class="footer-col-title">Legal</div>
      <ul class="footer-links">
        <li><a href="<?php echo esc_url( home_url( '/aviso-legal/' ) ); ?>">Aviso Legal</a></li>
        <li><a href="<?php echo esc_url( home_url( '/privacidad/' ) ); ?>">Política de Privacidad</a></li>
        <li><a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>">Política de Cookies</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© <?php echo date('Y'); ?> Federación de Golf de Castilla-La Mancha · Todos los derechos reservados</p>
    <div class="footer-social">
      <a href="https://www.facebook.com/fedgolfclm/" target="_blank" rel="noopener" title="Facebook">
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/facebook_logo.png" alt="Facebook">
      </a>
      <a href="https://www.instagram.com/fedgolfclm" target="_blank" rel="noopener" title="Instagram">
        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/instagram_logo.png" alt="Instagram">
      </a>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
