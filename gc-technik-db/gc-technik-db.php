<?php
/**
 * Plugin Name: GrandCali Technik-DB
 * Plugin URI: https://grandcali.com
 * Description: Technische Wissensdatenbank für den VW Grand California — Sicherungen, Stromlaufpläne, Reparaturanleitungen und mehr.
 * Version: 1.0.0
 * Author: GrandCali.com
 * Author URI: https://grandcali.com
 * Text Domain: gc-technik-db
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'GC_TECHNIK_DB_VERSION', '1.0.0' );
define( 'GC_TECHNIK_DB_PATH', plugin_dir_path( __FILE__ ) );
define( 'GC_TECHNIK_DB_URL', plugin_dir_url( __FILE__ ) );

require_once GC_TECHNIK_DB_PATH . 'includes/class-gc-cpt.php';
require_once GC_TECHNIK_DB_PATH . 'includes/class-gc-taxonomies.php';
require_once GC_TECHNIK_DB_PATH . 'includes/class-gc-search.php';
require_once GC_TECHNIK_DB_PATH . 'includes/class-gc-toc.php';
require_once GC_TECHNIK_DB_PATH . 'sample-data/import.php';

final class GC_Technik_DB {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        new GC_CPT();
        new GC_Taxonomies();
        new GC_Search();
        new GC_TOC();

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_filter( 'template_include', [ $this, 'load_templates' ] );

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
    }

    public function enqueue_assets() {
        if ( ! $this->is_gc_page() ) {
            return;
        }

        wp_enqueue_style(
            'gc-technik-db',
            GC_TECHNIK_DB_URL . 'assets/css/gc-technik-db.css',
            [],
            GC_TECHNIK_DB_VERSION
        );

        wp_enqueue_script(
            'gc-technik-db',
            GC_TECHNIK_DB_URL . 'assets/js/gc-technik-db.js',
            [],
            GC_TECHNIK_DB_VERSION,
            true
        );

        wp_localize_script( 'gc-technik-db', 'gcTechnikDB', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'gc_search_nonce' ),
        ] );
    }

    public function load_templates( $template ) {
        if ( is_post_type_archive( 'gc_article' ) ) {
            $custom = GC_TECHNIK_DB_PATH . 'templates/archive-gc-article.php';
            if ( file_exists( $custom ) ) {
                return $custom;
            }
        }

        if ( is_singular( 'gc_article' ) ) {
            $custom = GC_TECHNIK_DB_PATH . 'templates/single-gc-article.php';
            if ( file_exists( $custom ) ) {
                return $custom;
            }
        }

        return $template;
    }

    public function activate() {
        ( new GC_CPT() )->register();
        ( new GC_Taxonomies() )->register();
        flush_rewrite_rules();
    }

    private function is_gc_page() {
        return is_post_type_archive( 'gc_article' )
            || is_singular( 'gc_article' )
            || is_tax( 'gc_category' )
            || is_tax( 'gc_model' )
            || is_tax( 'gc_model_year' )
            || is_tax( 'gc_component' )
            || $this->has_gc_shortcode();
    }

    private function has_gc_shortcode() {
        global $post;
        if ( ! $post ) {
            return false;
        }
        return has_shortcode( $post->post_content, 'gc_technik_suche' )
            || has_shortcode( $post->post_content, 'gc_technik_toc' );
    }
}

GC_Technik_DB::instance();
