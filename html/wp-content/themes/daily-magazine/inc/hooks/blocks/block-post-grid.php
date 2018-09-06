<?php
/**
 * List block part for displaying page content in page.php
 *
 * @package Daily_Magazine
 */

$excerpt_length = 20;
if (has_post_thumbnail()) {
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'elegant-magazine-medium');
    $url = $thumb['0'];
} else {
    $url = '';
}
global $post;


$class = '';
$background = '';
if ($url != '') {
    $class = 'data-bg data-bg-categorised';
    $background = $url;
}
?>

<div class="row-sm align-items-center">
    <div class="col col-ten">
        <div class="spotlight-post spotlight-post-1">
            <figure class="categorised-article">
                <div class="categorised-article-wrapper">
                    <div class="data-bg-hover <?php echo esc_attr($class); ?>"
                         data-background="<?php echo esc_attr($background); ?>">

                        <div class="figure-categories figure-categories-1 figure-categories-bg">
                            <?php echo elegant_magazine_post_format($post->ID); ?>
                            <?php elegant_magazine_post_categories(); ?>
                        </div>
                    </div>
                </div>
            </figure>
            <figcaption>
                <h3 class="article-title article-title-2">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h3>
                <div class="grid-item-metadata">
                    <?php elegant_magazine_post_item_meta(); ?>
                </div>
                <?php
                $archive_content_view = elegant_magazine_get_option('archive_content_view');
                if ($archive_content_view != 'archive-content-none'):
                    ?>
                    <div class="full-item-discription">
                        <div class="post-description">

                            <?php

                            if ($archive_content_view == 'archive-content-excerpt') {

                                the_excerpt();
                            } else {
                                the_content();
                            }
                            ?>

                        </div>
                    </div>
                <?php endif; ?>
            </figcaption>
        </div>
    </div>
    <?php
    wp_link_pages(array(
        'before' => '<div class="page-links">' . esc_html__('Pages:', 'daily-magazine'),
        'after' => '</div>',
    ));
    ?>
</div>







