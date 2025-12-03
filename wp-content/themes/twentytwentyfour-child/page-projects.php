<?php
/**
 * Template Name: Projects Page
 *
 * Шаблон для страницы отображения всех проектов.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Four_Child
 */

get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
    'post_type'      => 'projects', // Наш CPT
    'posts_per_page' => -1,  
    'paged'          => $paged,
    'order'          => 'DESC',
    'orderby'        => 'date',
);
$projects_query = new WP_Query( $args );

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', 'page' );
        endwhile; // End of the loop.
        ?>

        <section class="projects-list-wrapper">
            <div class="projects-sorter">
                <label for="project-sort">Сортировать:</label>
                <select name="sort" id="project-sort">
                    <option value="default">По умолчанию</option>
                    <option value="date_desc">По дате (сначала новые)</option>
                    <option value="date_asc">По дате (сначала старые)</option>
                    <option value="cost_asc">Стоимость ↑</option>
                    <option value="cost_desc">Стоимость ↓</option>
                </select>
            </div>

            <div id="projects-container" class="projects-grid">
                <?php if ( $projects_query->have_posts() ) : ?>
                    <?php while ( $projects_query->have_posts() ) : $projects_query->the_post(); ?>
                        <?php
                            // Получаем данные для карточки
                            $cost = get_field('cost');
                            $gallery = get_field('project_gallery');
                            $first_image_url = '';
                            
                            if ( ! empty( $gallery ) && is_array( $gallery ) ) {
                                // Берем первое изображение из галереи ACF
                                $first_image_url = esc_url($gallery[0]['full_image_url']);
                            } elseif ( has_post_thumbnail() ) {
                                // Или используем миниатюру поста, если галерея пуста
                                $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                                $first_image_url = esc_url( wp_get_attachment_image_url( $thumbnail_id, 'medium' ) ); // 'medium' - размер изображения
                            }
                        ?>
                        <article id="project-<?php the_ID(); ?>" class="project-card">
                            <div class="project-card__image">
                                <?php if ( $first_image_url ) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?= $first_image_url ?>" alt="<?php the_title_attribute(); ?>">
                                    </a>
                                <?php else: ?>
                                     <div class="no-image-placeholder">Нет изображения</div>
                                <?php endif; ?>
                            </div>
                            <div class="project-card__content">
                                <h3 class="project-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="project-card__meta">
                                    <span class="project-card__category">
                                        <?php
                                        // Получаем категории проекта
                                        $terms = get_the_terms( get_the_ID(), 'project_category' );
                                        if ( $terms && ! is_wp_error( $terms ) ) :
                                            $term_links = array();
                                            foreach ( $terms as $term ) {
                                                $term_links[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
                                            }
                                            echo implode( ', ', $term_links );
                                        endif;
                                        ?>
                                    </span>
                                    <?php if ( $cost ) : ?>
                                        <span class="project-card__cost"><?= esc_html( $cost ) ?> руб.</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p><?php esc_html_e( 'Проекты не найдены.', 'twentytwentyfour-child' ); ?></p>
                <?php endif; ?>
            </div>
        </section>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
?>
