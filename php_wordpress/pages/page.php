<?php
/**
 * The template for displaying all pages by default
 *
 * @package FGCLM_Theme_v071
 */

get_header();
?>

<?php
if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();
    ?>
    <div class="page-hero">
      <div class="page-hero-content">
        <div class="page-hero-label"><?php bloginfo('name'); ?></div>
        <h1><?php the_title(); ?></h1>
        <?php if ( has_excerpt() ) : ?>
          <p><?php echo esc_html( get_the_excerpt() ); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="junta-section" style="background: var(--white); padding: 56px 6rem; max-width: 1100px; margin: 0 auto; line-height: 1.8; color: var(--gray-800); min-height: 400px;">
      <?php the_content(); ?>
    </div>
    <?php
  endwhile;
endif;
?>

<?php
get_footer();
