<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GC_Search {

    public function __construct() {
        add_shortcode( 'gc_technik_suche', [ $this, 'render_shortcode' ] );
        add_action( 'wp_ajax_gc_search', [ $this, 'ajax_search' ] );
        add_action( 'wp_ajax_nopriv_gc_search', [ $this, 'ajax_search' ] );
    }

    public function render_shortcode( $atts ) {
        $atts = shortcode_atts( [
            'per_page' => 12,
        ], $atts );

        ob_start();
        ?>
        <div class="gc-search-wrapper" data-per-page="<?php echo esc_attr( $atts['per_page'] ); ?>">
            <div class="gc-search-bar">
                <input type="text" class="gc-search-input" placeholder="Suche nach Bauteil, Sicherung, Kabel..." autocomplete="off" />
                <span class="gc-search-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </span>
            </div>

            <div class="gc-search-filters">
                <?php echo $this->render_taxonomy_filter( 'gc_category', 'Kategorie' ); ?>
                <?php echo $this->render_taxonomy_filter( 'gc_model', 'Modell' ); ?>
                <?php echo $this->render_taxonomy_filter( 'gc_model_year', 'Modelljahr' ); ?>
                <button type="button" class="gc-filter-reset" style="display:none;">Filter zurücksetzen</button>
            </div>

            <div class="gc-search-status" style="display:none;">
                <span class="gc-search-count"></span> Ergebnisse
            </div>

            <div class="gc-search-results"></div>

            <div class="gc-search-loading" style="display:none;">
                <div class="gc-spinner"></div>
                <span>Suche läuft...</span>
            </div>

            <div class="gc-search-no-results" style="display:none;">
                <p>Keine Ergebnisse gefunden. Versuche einen anderen Suchbegriff oder ändere die Filter.</p>
            </div>

            <div class="gc-search-pagination" style="display:none;">
                <button type="button" class="gc-load-more">Mehr Ergebnisse laden</button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_taxonomy_filter( $taxonomy, $label ) {
        $terms = get_terms( [
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'parent'     => 0,
        ] );

        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            return '';
        }

        $html = '<select class="gc-filter" data-taxonomy="' . esc_attr( $taxonomy ) . '">';
        $html .= '<option value="">' . esc_html( $label ) . '</option>';

        foreach ( $terms as $term ) {
            $html .= '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</option>';

            $children = get_terms( [
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
                'parent'     => $term->term_id,
            ] );

            if ( ! is_wp_error( $children ) ) {
                foreach ( $children as $child ) {
                    $html .= '<option value="' . esc_attr( $child->term_id ) . '">&nbsp;&nbsp;— '
                        . esc_html( $child->name ) . '</option>';
                }
            }
        }

        $html .= '</select>';
        return $html;
    }

    public function ajax_search() {
        check_ajax_referer( 'gc_search_nonce', 'nonce' );

        $query   = sanitize_text_field( $_POST['query'] ?? '' );
        $page    = absint( $_POST['page'] ?? 1 );
        $per_page = absint( $_POST['per_page'] ?? 12 );
        $filters = [];

        foreach ( [ 'gc_category', 'gc_model', 'gc_model_year' ] as $tax ) {
            $val = absint( $_POST[ $tax ] ?? 0 );
            if ( $val ) {
                $filters[ $tax ] = $val;
            }
        }

        $cache_key = 'gc_search_' . md5( $query . serialize( $filters ) . $page . $per_page );
        $cached = get_transient( $cache_key );

        if ( false !== $cached ) {
            wp_send_json_success( $cached );
        }

        $args = [
            'post_type'      => 'gc_article',
            'posts_per_page' => $per_page,
            'paged'          => $page,
            'post_status'    => 'publish',
        ];

        if ( $query ) {
            $args['s'] = $query;

            // Also search custom fields
            $args['meta_query'] = [
                'relation' => 'OR',
                [ 'key' => 'gc_vw_code', 'value' => $query, 'compare' => 'LIKE' ],
                [ 'key' => 'gc_wire_colors', 'value' => $query, 'compare' => 'LIKE' ],
                [ 'key' => 'gc_fuse_rating', 'value' => $query, 'compare' => 'LIKE' ],
                [ 'key' => 'gc_location', 'value' => $query, 'compare' => 'LIKE' ],
            ];
        }

        if ( ! empty( $filters ) ) {
            $args['tax_query'] = [ 'relation' => 'AND' ];
            foreach ( $filters as $tax => $term_id ) {
                $args['tax_query'][] = [
                    'taxonomy' => $tax,
                    'field'    => 'term_id',
                    'terms'    => $term_id,
                ];
            }
        }

        $wp_query = new WP_Query( $args );
        $results  = [];

        foreach ( $wp_query->posts as $post ) {
            $categories = wp_get_post_terms( $post->ID, 'gc_category', [ 'fields' => 'names' ] );
            $models     = wp_get_post_terms( $post->ID, 'gc_model', [ 'fields' => 'names' ] );
            $components = wp_get_post_terms( $post->ID, 'gc_component', [ 'fields' => 'names' ] );

            $excerpt = $post->post_excerpt ?: wp_trim_words( strip_tags( $post->post_content ), 30 );

            if ( $query ) {
                $excerpt = $this->highlight_text( $excerpt, $query );
            }

            $results[] = [
                'id'         => $post->ID,
                'title'      => $post->post_title,
                'url'        => get_permalink( $post->ID ),
                'excerpt'    => $excerpt,
                'categories' => $categories,
                'models'     => $models,
                'components' => $components,
                'vw_code'    => get_post_meta( $post->ID, 'gc_vw_code', true ),
                'thumbnail'  => get_the_post_thumbnail_url( $post->ID, 'medium' ),
            ];
        }

        $response = [
            'results'    => $results,
            'total'      => $wp_query->found_posts,
            'pages'      => $wp_query->max_num_pages,
            'page'       => $page,
        ];

        set_transient( $cache_key, $response, 5 * MINUTE_IN_SECONDS );

        wp_send_json_success( $response );
    }

    private function highlight_text( $text, $query ) {
        $words = array_filter( explode( ' ', $query ) );
        foreach ( $words as $word ) {
            $word = preg_quote( $word, '/' );
            $text = preg_replace( '/(' . $word . ')/iu', '<mark>$1</mark>', $text );
        }
        return $text;
    }
}
