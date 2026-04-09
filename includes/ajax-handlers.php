<?php
/**
 * AJAX Handlers for PowerKit Addons
 *
 * @package PowerKit_Addons_For_Elementor
 */

namespace PKAE_Elementor_PowerKit_Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PKAE_Ajax_Handlers {

	/**
	 * Instance
	 *
	 * @var PKAE_Ajax_Handlers
	 */
	private static $_instance = null;

	/**
	 * Get Instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register AJAX handlers
		add_action( 'wp_ajax_pkae_load_posts', [ $this, 'ajax_load_posts' ] );
		add_action( 'wp_ajax_nopriv_pkae_load_posts', [ $this, 'ajax_load_posts' ] );
		add_action( 'wp_ajax_pkae_advanced_search', [ $this, 'ajax_advanced_search' ] );
		add_action( 'wp_ajax_nopriv_pkae_advanced_search', [ $this, 'ajax_advanced_search' ] );
	}

	/**
	 * AJAX handler for loading posts
	 */
	public function ajax_load_posts() {
		check_ajax_referer( 'pkae_posts_nonce', 'nonce' );

		$widget_id = isset( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';
		$page      = isset( $_POST['page'] ) ? (int) $_POST['page'] : 1;
		$post_id   = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;

		$args = [
			'post_type'      => 'post',
			'posts_per_page' => 6,
			'paged'          => $page,
			'post_status'    => 'publish',
		];

		$query = new \WP_Query( $args );
		$html  = '';

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$html .= '<article class="pkae-posts__item">';
				if ( has_post_thumbnail() ) {
					$html .= '<div class="pkae-posts__img" style="height:220px;"><a href="' . esc_url( get_permalink() ) . '">' . get_the_post_thumbnail( null, 'medium_large' ) . '</a></div>';
				}
				$html .= '<div class="pkae-posts__content">';
				$html .= '<h3 class="pkae-posts__title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
				$html .= '<p class="pkae-posts__excerpt">' . esc_html( wp_trim_words( get_the_excerpt(), 20 ) ) . '</p>';
				$html .= '<a class="pkae-posts__cta" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read More', 'powerkit-addons-for-elementor' ) . '</a>';
				$html .= '</div></article>';
			}
			wp_reset_postdata();
		}

		wp_send_json_success( [ 'html' => $html ] );
	}

	/**
	 * AJAX handler for advanced search
	 */
	public function ajax_advanced_search() {
		check_ajax_referer( 'pkae_search_nonce', 'nonce' );

		$query          = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
		$post_types     = isset( $_POST['post_types'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['post_types'] ) ) : [ 'post', 'page' ];
		$count          = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 5;
		$show_thumb     = isset( $_POST['show_thumb'] ) && 'yes' === $_POST['show_thumb'];
		$show_excerpt   = isset( $_POST['show_excerpt'] ) && 'yes' === $_POST['show_excerpt'];
		$excerpt_length = isset( $_POST['excerpt_length'] ) ? absint( $_POST['excerpt_length'] ) : 15;
		$show_date      = isset( $_POST['show_date'] ) && 'yes' === $_POST['show_date'];
		$show_type      = isset( $_POST['show_type'] ) && 'yes' === $_POST['show_type'];
		$result_style   = isset( $_POST['result_style'] ) ? sanitize_text_field( wp_unslash( $_POST['result_style'] ) ) : 'list';

		if ( empty( $query ) ) {
			wp_send_json_error( [ 'message' => 'Empty query' ] );
		}

		$args = [
			'post_type'      => $post_types,
			'post_status'    => 'publish',
			'posts_per_page' => $count,
			's'              => $query,
			'orderby'        => 'relevance',
		];

		$search_query = new \WP_Query( $args );

		if ( ! $search_query->have_posts() ) {
			wp_send_json_success( [ 'html' => '<div class="pkae-no-results">' . esc_html__( 'No results found', 'powerkit-addons-for-elementor' ) . '</div>' ] );
		}

		$html = '';
		$item_class = 'grid' === $result_style ? 'pkae-result-card' : 'pkae-result-item';

		while ( $search_query->have_posts() ) {
			$search_query->the_post();
			$post_id = get_the_ID();
			
			$html .= '<a href="' . esc_url( get_permalink( $post_id ) ) . '" class="' . esc_attr( $item_class ) . '">';
			
			// Thumbnail
			if ( $show_thumb ) {
				$has_thumb = has_post_thumbnail( $post_id );
				
				if ( $has_thumb ) {
					$thumb_url = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
					if ( $thumb_url ) {
						$html .= '<div class="pkae-result-thumb">';
						$html .= '<img src="' . esc_url( $thumb_url ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '" loading="lazy" />';
						$html .= '</div>';
					}
				}
			}
			
			// Content wrapper
			$html .= '<div class="pkae-result-content">';
			
			// Title
			$html .= '<h4 class="pkae-result-title">' . esc_html( get_the_title( $post_id ) ) . '</h4>';
			
			// Excerpt
			if ( $show_excerpt ) {
				$excerpt = get_the_excerpt( $post_id );
				if ( empty( $excerpt ) ) {
					$excerpt = get_the_content( null, false, $post_id );
				}
				$html .= '<p class="pkae-result-excerpt">' . esc_html( wp_trim_words( $excerpt, $excerpt_length, '...' ) ) . '</p>';
			}
			
			// Meta (date and post type)
			if ( $show_date || $show_type ) {
				$html .= '<div class="pkae-result-meta">';
				
				if ( $show_date ) {
					$html .= '<span class="pkae-result-date">' . esc_html( get_the_date( '', $post_id ) ) . '</span>';
				}
				
				if ( $show_type ) {
					$post_type_obj = get_post_type_object( get_post_type( $post_id ) );
					if ( $post_type_obj ) {
						$html .= '<span class="pkae-result-type">' . esc_html( $post_type_obj->labels->singular_name ) . '</span>';
					}
				}
				
				$html .= '</div>';
			}
			
			$html .= '</div>'; // .pkae-result-content
			$html .= '</a>'; // .pkae-result-item
		}

		wp_reset_postdata();

		wp_send_json_success( [ 'html' => $html ] );
	}
}

// Initialize
PKAE_Ajax_Handlers::instance();
