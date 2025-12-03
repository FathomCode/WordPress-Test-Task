<?php
/**
 * Шаблон для отображения одного проекта.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Four_Child
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><!-- .entry-header -->

                <div class="entry-content project-single-content">

                    <div class="project-details-wrapper">
                        <div class="project-details-main">
                            <div class="project-main-image">
                                <?php
                                $gallery = get_field('project_gallery');
                                var_dump($gallery);
                                $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                                $first_image_url = '';

                                if ( ! empty( $gallery ) && is_array( $gallery ) ) {
                                    $first_image_url = esc_url($gallery[0]['url']);
                                } elseif ( $thumbnail_id ) {
                                    $first_image_url = esc_url( wp_get_attachment_image_url( $thumbnail_id, 'large' ) );
                                }

                                if ( $first_image_url ) : ?>
                                    <img src="<?= $first_image_url ?>" alt="<?php the_title_attribute(); ?>">
                                <?php else : ?>
                                    <div class="no-image-placeholder">Нет изображения</div>
                                <?php endif; ?>
                            </div>

                            <div class="project-meta-info">
                                <?php
                                $terms = get_the_terms( get_the_ID(), 'project_category' );
                                if ( $terms && ! is_wp_error( $terms ) ) : ?>
                                    <p><strong>Категории:</strong>
                                        <?php
                                        $term_links = array();
                                        foreach ( $terms as $term ) {
                                            $term_links[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
                                        }
                                        echo implode( ', ', $term_links );
                                        ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ( get_field('cost') ) : ?>
                                    <p><strong>Стоимость:</strong> <?= esc_html( get_field('cost') ) ?> руб.</p>
                                <?php endif; ?>

                                <?php if ( get_field('development_time') ) : ?>
                                    <p><strong>Время разработки:</strong> <?= esc_html( get_field('development_time') ) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="project-full-description">
                            <?php the_content(); ?>

                            <?php if ( get_field('project_description') ) : ?>
                                <div class="project-additional-description">
                                    <h2>Дополнительно</h2>
                                    <?php the_field('project_description'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php
                    $gallery = get_field('project_gallery');
                    if ( $gallery && is_array( $gallery ) ) : ?>
                        <div class="project-gallery-wrapper">
                            <h2>Галерея проекта</h2>
                            <div class="project-gallery-grid">
                                <?php foreach ( $gallery as $image ) : ?>
                                <?php echo "<pre>"; var_dump($image); ?>
                                    <div class="gallery-image-item">
                                        <a href="<?= esc_url($image['full_image_url']) ?>" data-fancybox="gallery">
                                            <img src="<?= esc_url($image['sizes']['medium']) ?>" alt="<?= esc_attr($image['alt']) ?>">
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
        endwhile;
        ?>

    </main>
</div>

<?php
get_footer();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<script>
    // Инициализация Fancybox
    jQuery(document).ready(function($) {
        $('[data-fancybox]').fancybox({
            buttons: [
                "zoom",
                //"share",
                "slideShow",
                "fullScreen",
                "download",
                "thumbs"
            ],
            // other options
        });
    });
</script>
