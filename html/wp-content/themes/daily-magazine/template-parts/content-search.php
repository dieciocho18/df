<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Daily_Magazine
 */
$archive_class = elegant_magazine_get_option('archive_layout');
if ($archive_class == 'archive-layout-grid'):
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('col-lg-4 col-sm-6 col-md-6 archive-layout-grid'); ?> data-mh="archive-layout-grid">
        <?php elegant_magazine_page_layout_blocks(); ?>
    </article>
<?php else: ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php elegant_magazine_page_layout_blocks(); ?>
    </article>
<?php endif; ?>

