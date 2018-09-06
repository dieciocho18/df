<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Daily_Magazine
 */

?>


<?php if (is_singular()) : ?>
    <div class="entry-content">
        <?php
        the_content(sprintf(
            wp_kses(
            /* translators: %s: Name of current post. Only visible to screen readers */
                __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'daily-magazine'),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            get_the_title()
        ));

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'daily-magazine'),
            'after' => '</div>',
        ));
        ?>
    </div><!-- .entry-content -->
    <footer class="entry-footer">
        <?php //elegant_magazine_entry_footer(); ?>
    </footer>


<?php else:
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
<?php endif; ?>
