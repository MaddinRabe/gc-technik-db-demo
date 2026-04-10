<?php
/**
 * Kategorie-Fix: Artikel korrekt zuordnen
 * - "Camping" Kategorie löschen, Artikel zu "Campingausstattung Grand California" verschieben
 * - Alle Artikel anhand von Titel/Inhalt den richtigen Unterkategorien zuordnen
 *
 * Aufruf: /wp-admin/?gc_fix_categories=1
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class GC_Category_Fix {

    public static function run() {
        if ( get_option( 'gc_categories_fixed_v2' ) ) {
            return 'Kategorien wurden bereits korrigiert.';
        }

        $fixed = 0;

        // 1. Alte "Camping" Kategorie in "Campingausstattung Grand California" zusammenführen
        $old_camping = get_term_by( 'name', 'Camping', 'gc_category' );
        $new_camping = get_term_by( 'name', 'Campingausstattung Grand California', 'gc_category' );

        if ( $old_camping && $new_camping ) {
            // Alle Artikel von "Camping" nach "Campingausstattung Grand California" verschieben
            $posts_in_old = get_posts( [
                'post_type'      => 'gc_article',
                'posts_per_page' => -1,
                'tax_query'      => [ [ 'taxonomy' => 'gc_category', 'field' => 'term_id', 'terms' => $old_camping->term_id ] ],
            ] );
            foreach ( $posts_in_old as $p ) {
                wp_remove_object_terms( $p->ID, $old_camping->term_id, 'gc_category' );
                wp_set_object_terms( $p->ID, $new_camping->term_id, 'gc_category', true );
                $fixed++;
            }
            // Unterkategorien umhängen
            $old_children = get_terms( [ 'taxonomy' => 'gc_category', 'parent' => $old_camping->term_id, 'hide_empty' => false ] );
            if ( ! is_wp_error( $old_children ) ) {
                foreach ( $old_children as $child ) {
                    wp_update_term( $child->term_id, 'gc_category', [ 'parent' => $new_camping->term_id ] );
                }
            }
            // Alte Kategorie löschen
            wp_delete_term( $old_camping->term_id, 'gc_category' );
        }

        // 2. Unterkategorie-Mapping: Artikel anhand von Schlüsselwörtern zuordnen
        $mapping = self::get_keyword_mapping();

        $all_articles = get_posts( [
            'post_type'      => 'gc_article',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ] );

        foreach ( $all_articles as $article ) {
            $title   = strtolower( $article->post_title );
            $content = strtolower( $article->post_content );
            $vw_code = strtolower( get_post_meta( $article->ID, 'gc_vw_code', true ) );
            $location = strtolower( get_post_meta( $article->ID, 'gc_location', true ) );
            $haystack = $title . ' ' . $content . ' ' . $vw_code . ' ' . $location;

            $cats_to_add = [];

            foreach ( $mapping as $category_name => $keywords ) {
                foreach ( $keywords as $kw ) {
                    if ( strpos( $haystack, strtolower( $kw ) ) !== false ) {
                        $cats_to_add[] = $category_name;
                        break;
                    }
                }
            }

            if ( ! empty( $cats_to_add ) ) {
                // Bestehende Kategorien behalten, neue hinzufügen
                foreach ( $cats_to_add as $cat_name ) {
                    $term = get_term_by( 'name', $cat_name, 'gc_category' );
                    if ( $term ) {
                        wp_set_object_terms( $article->ID, $term->term_id, 'gc_category', true );
                    }
                }
                $fixed++;
            }
        }

        update_option( 'gc_categories_fixed_v2', true );
        return sprintf( 'Kategorie-Fix abgeschlossen. %d Artikel aktualisiert.', $fixed );
    }

    private static function get_keyword_mapping() {
        return [
            // Campingausstattung Unterkategorien
            'Nasszelle' => [
                'nasszelle', 'duschwanne', 'waschbecken', 'toilette', 'formhimmel nasszelle',
                'handtuchhalter', 'dachhaube nasszelle', 'dachrahmen', 'serviceklappe toilette',
                'halterahmen der serviceklappe', 'fäkalien', 'badtür', 'türgriff nasszelle',
                'rückwand', 'schrank aus- und einbauen', 'bad-seitenwand', 'entkopplungsplatte',
            ],
            'Küche' => [
                'kühlschrank', 'j699', 'ntc-sensor', 'temperatursensor im kühlschrank',
                'spüle', 'küchenleuchte', 'w57',
            ],
            'Heizung & Klima' => [
                'truma', 'heizung', 'zusatzheizung', 'zx2', 'klimaanlage', 'klimakompressor',
                'dachklima', 'pollenfilter', 'e515h', 'e133h', 'e525h', 'warmluft', 'ausströmer',
            ],
            'Wasser & Sanitär' => [
                'wassersystem', 'frischwasser', 'abwasser', 'wasserleitung', 'schwerkraftmethode',
                'druckluftmethode', 'wasserpumpe', 'wasserstandsgeber', 'g120', 'g126',
                'entleeren der', 'abwasserbehälter',
            ],
            'Strom & Solar' => [
                'solarzellen', 'c20', 'solar', 'batterie', 'landstrom', 'wechselrichter',
                'energiemanagement', 'zweitbatterie', 'aufbaubatterie', '230v',
            ],
            'Möbel & Verkleidungen' => [
                'seitenverkleidung', 'eckverkleidung', 'dachstaukasten', 'stauschrank',
                'sitzbank', 'verstärkung sitzbank', 'bettverbreiterung', 'querschläfer',
                'einstiegsleiste', 'frontwand', 'seitenwand', 'b-säule',
                'verkleidung unter', 'verkleidungen für', 'blende', 'schrank',
                'jalousette', 'drehsitz', 'sitzverkleidung', 'furnier',
                'gummileiste bett', 'lattenrost', 'innenraumleiter', 'befestigungshaken',
            ],
            'Dach & Dachhauben' => [
                'dachhaube', 'dachluke', 'dachhauben', 'hochraumdach', 'schutzgitter',
                'profildichtung', 'aufsteller', 'dachrahmen', 'dachlucken', 'dachverkleidung',
                'windgeräusche an der', 'regenrinne',
            ],
            'Markise' => [
                'markise', 'markisenkurbel', 'befestigungshalter', 'kappe aus- und einbauen',
                'schimmel auf der markise',
            ],

            // Elektrik Unterkategorien
            'Beleuchtung' => [
                'innenleuchte', 'leseleuchte', 'bremsleuchte', 'led-beleuchtung',
                'ambientebeleuchtung', 'campingbeleuchtung', 'w7', 'w11', 'w12',
                'insekten im scheinwerfer',
            ],

            // Karosserie Unterkategorien
            'Folierung' => [
                'folierung', 'zebra', 'abdeckfolie',
            ],
            'Außen' => [
                'trittstufe', 'regenrinne',
            ],

            // Fahrwerk
            'Fahrwerk & Lenkung' => [
                'gelenkwelle', 'antriebswelle', 'gleichlaufgelenk', 'lenkeinschlag',
                'quietschgeräusche', 'faltenbalg', 'fahrzeug zieht',
            ],
            'Motor & Antrieb' => [
                'kühlmittelverlust', 'agr-kühler', 'ölundichtigkeit', 'dichtflansch',
                'ölverbrauch', 'ventilschaftdichtung', 'abgasklappe', 'motorsteuergerät',
                'starterbatterie', 'bi-turbo', 'kaltstart', 'nageln',
            ],

            // Elektrik & Elektronik
            'Elektrik & Elektronik' => [
                'mib3', 'infotainment', 'we connect', 'online-dienste', 'vkms',
                'dab+', 'dometic zbe', 'campingmodus', 'bedieneinheit', 'e153',
                'steuergerät', 'software', 'fehlermeldung',
            ],
        ];
    }
}

add_action( 'admin_init', function () {
    if ( isset( $_GET['gc_fix_categories'] ) && current_user_can( 'manage_options' ) ) {
        $result = GC_Category_Fix::run();
        add_action( 'admin_notices', function () use ( $result ) {
            echo '<div class="notice notice-success"><p>' . esc_html( $result ) . '</p></div>';
        } );
    }
} );
