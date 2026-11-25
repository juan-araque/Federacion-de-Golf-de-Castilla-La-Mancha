#!/bin/bash
# Convert static HTML subpages to WordPress page templates
# This extracts content between <body> and </body> (minus script tags),
# wraps in get_header()/get_footer(), and creates page-{slug}.php files

DEST="/Users/maximartin/Downloads/GOLF-main/v0.3"

convert_file() {
    local src="$1"
    local slug="$2"
    local outfile="$DEST/page-${slug}.php"
    
    # Extract the content between <body...> and </body>
    # Remove script tags and HTML boilerplate
    local body_content
    body_content=$(sed -n '/<body/,/<\/body>/p' "$src" | \
        sed '1d;$d' | \
        sed '/<script src=.*shared\.js/d' | \
        sed '/<script src=.*shared_nav\.js/d')
    
    cat > "$outfile" << PHPEOF
<?php
/**
 * Page template: ${slug}
 * Auto-converted from static HTML
 *
 * @package FGCLM_Theme_v03
 */
get_header();
?>

${body_content}

<?php get_footer();
PHPEOF
    echo "Created: $outfile"
}

# Rankings subpages
for f in /Users/maximartin/Downloads/GOLF-main/rankings/*.html; do
    basename_f=$(basename "$f" .html)
    slug="rankings-${basename_f}"
    convert_file "$f" "$slug"
done

# LicenHandi subpages
for f in /Users/maximartin/Downloads/GOLF-main/licenHandi/*.html; do
    basename_f=$(basename "$f" .html)
    convert_file "$f" "$basename_f"
done

# Resultados subpages
for f in /Users/maximartin/Downloads/GOLF-main/competiciones/resultados/*.html; do
    basename_f=$(basename "$f" .html)
    # Skip the plantilla (template)
    [ "$basename_f" = "plantilla_resultado" ] && continue
    slug="resultados-$(echo $basename_f | sed 's/resultados//')"
    convert_file "$f" "$slug"
done

echo "Done! All files converted."
