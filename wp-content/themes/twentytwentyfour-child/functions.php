<?php
/**
 * Functions and definitions
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Four_Child
 */

function twentytwentyfour_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'), wp_get_theme()->get('Version') );

    wp_enqueue_style( 'child-responsive-style', get_stylesheet_directory_uri() . '/responsive.css', array('child-style'), wp_get_theme()->get('Version') );

    wp_enqueue_script( 'custom-projects-script', get_stylesheet_directory_uri() . '/js/projects-ajax.js', array('jquery'), wp_get_theme()->get('Version'), true );

    // параметры AJAX для JS
    wp_localize_script( 'custom-projects-script', 'ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'filter_projects_nonce' )
    ) );
}
add_action( 'wp_enqueue_scripts', 'twentytwentyfour_child_enqueue_styles' );




add_action('wp_ajax_filter_projects', 'handle_filter_projects');
add_action('wp_ajax_nopriv_filter_projects', 'handle_filter_projects');

function handle_filter_projects() {
    check_ajax_referer('filter_projects_nonce', 'nonce');

    $sort_by = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'default';

    $args = array(
        'post_type'      => 'projects',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    switch ($sort_by) {
        case 'date_desc':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'date_asc':
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
            break;
        case 'cost_asc':
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            $args['meta_key'] = 'cost';
            break;
        case 'cost_desc':
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            $args['meta_key'] = 'cost';
            break;
        case 'default':
        default:
            break;
    }

    $projects_query = new WP_Query($args);
    $html_output = '';

    if ($projects_query->have_posts()) {
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            // аналогично page-projects.php
            $cost = get_field('cost');
            $gallery = get_field('project_gallery');
            $first_image_url = '';
            if ( ! empty( $gallery ) && is_array( $gallery ) ) {
                $first_image_url = esc_url($gallery[0]['url']);
            } elseif ( has_post_thumbnail() ) {
                $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
                $first_image_url = esc_url( wp_get_attachment_image_url( $thumbnail_id, 'medium' ) );
            }

            // Формируем HTML для карточки проекта
            $html_output .= '<article id="project-' . get_the_ID() . '" class="project-card">';
            $html_output .= '<div class="project-card__image">';
            if ($first_image_url) {
                $html_output .= '<a href="' . esc_url(get_permalink()) . '"><img src="' . $first_image_url . '" alt="' . get_the_title() . '"></a>';
            } else {
                 $html_output .= '<div class="no-image-placeholder">Нет изображения</div>';
            }
            $html_output .= '</div>';
            $html_output .= '<div class="project-card__content">';
            $html_output .= '<h3 class="project-card__title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
            $html_output .= '<div class="project-card__meta">';

            // Категории
            $terms = get_the_terms( get_the_ID(), 'project_category' );
            if ( $terms && ! is_wp_error( $terms ) ) {
                $term_links = array();
                foreach ( $terms as $term ) {
                    $term_links[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
                }
                $html_output .= '<span class="project-card__category">' . implode( ', ', $term_links ) . '</span>';
            }
            // Стоимость
            if ( $cost ) {
                $html_output .= '<span class="project-card__cost">' . esc_html( $cost ) . ' руб.</span>';
            }
            $html_output .= '</div></div>';
            $html_output .= '</article>';
        }
        wp_reset_postdata();
    } else {
        $html_output = '<p style="text-align: center; padding: 50px;">Проекты не найдены.</p>';
    }

    wp_send_json_success( array(
        'html' => $html_output,
        'message' => 'Проекты успешно отсортированы.',
    ) );

    wp_die();
}


    ?>