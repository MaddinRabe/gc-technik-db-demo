<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GC_CPT {

    public function __construct() {
        add_action( 'init', [ $this, 'register' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post_gc_article', [ $this, 'save_meta' ] );
    }

    public function register() {
        $labels = [
            'name'               => 'Technik-Artikel',
            'singular_name'      => 'Technik-Artikel',
            'add_new'            => 'Neuer Artikel',
            'add_new_item'       => 'Neuen Technik-Artikel erstellen',
            'edit_item'          => 'Technik-Artikel bearbeiten',
            'new_item'           => 'Neuer Technik-Artikel',
            'view_item'          => 'Technik-Artikel ansehen',
            'search_items'       => 'Technik-Artikel suchen',
            'not_found'          => 'Keine Technik-Artikel gefunden',
            'not_found_in_trash' => 'Keine Technik-Artikel im Papierkorb',
            'all_items'          => 'Alle Technik-Artikel',
            'menu_name'          => 'Technik-DB',
        ];

        register_post_type( 'gc_article', [
            'labels'       => $labels,
            'public'       => true,
            'has_archive'  => true,
            'rewrite'      => [ 'slug' => 'technik', 'with_front' => false ],
            'menu_icon'    => 'dashicons-database',
            'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ],
            'show_in_rest' => true,
            'taxonomies'   => [ 'gc_category', 'gc_model', 'gc_model_year', 'gc_component' ],
        ] );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'gc_article_details',
            'Technische Details',
            [ $this, 'render_meta_box' ],
            'gc_article',
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'gc_article_meta', 'gc_article_meta_nonce' );

        $fields = $this->get_meta_fields();

        echo '<table class="form-table gc-meta-table">';
        foreach ( $fields as $key => $field ) {
            $value = get_post_meta( $post->ID, $key, true );
            echo '<tr>';
            echo '<th><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th>';
            echo '<td>';

            if ( $field['type'] === 'textarea' ) {
                echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="3" class="large-text">'
                    . esc_textarea( $value ) . '</textarea>';
            } else {
                echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="'
                    . esc_attr( $value ) . '" class="regular-text" />';
            }

            if ( ! empty( $field['description'] ) ) {
                echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
            }

            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function save_meta( $post_id ) {
        if ( ! isset( $_POST['gc_article_meta_nonce'] )
            || ! wp_verify_nonce( $_POST['gc_article_meta_nonce'], 'gc_article_meta' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        foreach ( array_keys( $this->get_meta_fields() ) as $key ) {
            if ( isset( $_POST[ $key ] ) ) {
                update_post_meta( $post_id, $key, sanitize_textarea_field( $_POST[ $key ] ) );
            }
        }
    }

    private function get_meta_fields() {
        return [
            'gc_vw_code' => [
                'label'       => 'VW Bauteil-Code',
                'type'        => 'text',
                'description' => 'z.B. W49, SA2, J699, E153',
            ],
            'gc_wire_colors' => [
                'label'       => 'Kabelfarben',
                'type'        => 'textarea',
                'description' => 'z.B. rt/bl = rot/blau, sw/gn = schwarz/grün',
            ],
            'gc_fuse_rating' => [
                'label'       => 'Sicherungswert',
                'type'        => 'text',
                'description' => 'z.B. 5A, 10A, 30A',
            ],
            'gc_location' => [
                'label'       => 'Einbauort',
                'type'        => 'text',
                'description' => 'z.B. Technikschrank Pos. 3, Sicherungshalter B',
            ],
            'gc_tools_needed' => [
                'label'       => 'Benötigtes Werkzeug',
                'type'        => 'textarea',
                'description' => 'z.B. Demontagekeil 3409, Torx T25',
            ],
            'gc_torque_specs' => [
                'label'       => 'Anzugsdrehmomente',
                'type'        => 'textarea',
                'description' => 'z.B. Schrauben: 1 Nm',
            ],
        ];
    }
}
