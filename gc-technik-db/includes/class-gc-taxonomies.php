<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GC_Taxonomies {

    public function __construct() {
        add_action( 'init', [ $this, 'register' ] );
    }

    public function register() {
        // Kategorie (hierarchisch)
        register_taxonomy( 'gc_category', 'gc_article', [
            'labels' => [
                'name'          => 'Technik-Kategorien',
                'singular_name' => 'Technik-Kategorie',
                'search_items'  => 'Kategorien suchen',
                'all_items'     => 'Alle Kategorien',
                'parent_item'   => 'Übergeordnete Kategorie',
                'edit_item'     => 'Kategorie bearbeiten',
                'add_new_item'  => 'Neue Kategorie',
            ],
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'technik-kategorie', 'with_front' => false ],
            'show_in_rest' => true,
            'show_admin_column' => true,
        ] );

        // Modell (hierarchisch)
        register_taxonomy( 'gc_model', 'gc_article', [
            'labels' => [
                'name'          => 'Modelle',
                'singular_name' => 'Modell',
                'search_items'  => 'Modelle suchen',
                'all_items'     => 'Alle Modelle',
                'parent_item'   => 'Übergeordnetes Modell',
                'edit_item'     => 'Modell bearbeiten',
                'add_new_item'  => 'Neues Modell',
            ],
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'modell', 'with_front' => false ],
            'show_in_rest' => true,
            'show_admin_column' => true,
        ] );

        // Modelljahr (flat)
        register_taxonomy( 'gc_model_year', 'gc_article', [
            'labels' => [
                'name'          => 'Modelljahre',
                'singular_name' => 'Modelljahr',
                'search_items'  => 'Modelljahre suchen',
                'all_items'     => 'Alle Modelljahre',
                'edit_item'     => 'Modelljahr bearbeiten',
                'add_new_item'  => 'Neues Modelljahr',
            ],
            'hierarchical' => false,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'modelljahr', 'with_front' => false ],
            'show_in_rest' => true,
            'show_admin_column' => true,
        ] );

        // Bauteil-Tags (flat)
        register_taxonomy( 'gc_component', 'gc_article', [
            'labels' => [
                'name'          => 'Bauteile',
                'singular_name' => 'Bauteil',
                'search_items'  => 'Bauteile suchen',
                'all_items'     => 'Alle Bauteile',
                'edit_item'     => 'Bauteil bearbeiten',
                'add_new_item'  => 'Neues Bauteil',
            ],
            'hierarchical' => false,
            'public'       => true,
            'rewrite'      => [ 'slug' => 'bauteil', 'with_front' => false ],
            'show_in_rest' => true,
            'show_admin_column' => true,
        ] );

        $this->create_default_terms();
    }

    private function create_default_terms() {
        if ( get_option( 'gc_default_terms_created' ) ) {
            return;
        }

        // Hauptkategorien
        $categories = [
            'Elektrik' => [
                'Sicherungen',
                'Stromlaufpläne',
                'Beleuchtung',
                'Steuergeräte',
                'Steckverbindungen',
            ],
            'Campingausstattung Grand California' => [
                'Küche',
                'Nasszelle',
                'Heizung & Klima',
                'Wasser & Sanitär',
                'Strom & Solar',
                'Möbel & Verkleidungen',
                'Dach & Dachhauben',
                'Markise',
            ],
            'Karosserie' => [
                'Innenausstattung',
                'Außen',
                'Fenster & Türen',
                'Dach & Aufstelldach',
                'Folierung',
            ],
            'Fahrwerk & Motor' => [
                'Motor',
                'Getriebe',
                'Bremsen',
                'Lenkung',
                'Abgas & Umwelt',
            ],
            'Reparaturanleitungen' => [
                'Aus- und Einbau',
                'Wartung',
                'Fehlerbehebung',
                'Montageübersichten',
            ],
            'Technische Produktinformationen (TPI)' => [
                'Elektrik & Elektronik',
                'Aufbau & Ausstattung',
                'Motor & Antrieb',
                'Fahrwerk & Lenkung',
                'Heizung & Klima',
            ],
            'Servicehinweise' => [
                'Bekannte Probleme',
                'Lösungen & Workarounds',
            ],
            'Servicemaßnahmen & Rückrufe' => [],
        ];

        foreach ( $categories as $parent_name => $children ) {
            $parent = wp_insert_term( $parent_name, 'gc_category' );
            if ( ! is_wp_error( $parent ) ) {
                foreach ( $children as $child_name ) {
                    wp_insert_term( $child_name, 'gc_category', [
                        'parent' => $parent['term_id'],
                    ] );
                }
            }
        }

        // Modelle
        $models = [
            'Grand California' => [ 'Grand California 600', 'Grand California 680' ],
            'Crafter'          => [],
        ];

        foreach ( $models as $parent_name => $children ) {
            $parent = wp_insert_term( $parent_name, 'gc_model' );
            if ( ! is_wp_error( $parent ) ) {
                foreach ( $children as $child_name ) {
                    wp_insert_term( $child_name, 'gc_model', [
                        'parent' => $parent['term_id'],
                    ] );
                }
            }
        }

        // Modelljahre
        for ( $year = 2019; $year <= 2026; $year++ ) {
            $suffix = $year >= 2024 ? ' (Facelift)' : '';
            wp_insert_term( $year . $suffix, 'gc_model_year' );
        }

        update_option( 'gc_default_terms_created', true );
    }
}
