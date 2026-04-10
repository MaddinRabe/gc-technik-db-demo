<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GC_TOC {

    public function __construct() {
        add_shortcode( 'gc_technik_toc', [ $this, 'render_shortcode' ] );
        add_filter( 'the_content', [ $this, 'auto_insert_toc' ], 20 );
        add_filter( 'the_content', [ $this, 'add_heading_ids' ], 10 );
    }

    public function add_heading_ids( $content ) {
        if ( ! is_singular( 'gc_article' ) ) {
            return $content;
        }

        $content = preg_replace_callback(
            '/<(h[23])([^>]*)>(.*?)<\/\1>/i',
            function ( $matches ) {
                $tag   = $matches[1];
                $attrs = $matches[2];
                $text  = $matches[3];
                $id    = $this->generate_id( strip_tags( $text ) );

                if ( preg_match( '/id=["\']/', $attrs ) ) {
                    return $matches[0];
                }

                return '<' . $tag . ' id="' . esc_attr( $id ) . '"' . $attrs . '>' . $text . '</' . $tag . '>';
            },
            $content
        );

        return $content;
    }

    public function auto_insert_toc( $content ) {
        if ( ! is_singular( 'gc_article' ) ) {
            return $content;
        }

        $headings = $this->extract_headings( $content );

        if ( count( $headings ) < 2 ) {
            return $content;
        }

        $toc_html = $this->build_toc_html( $headings );

        return $toc_html . $content;
    }

    public function render_shortcode() {
        global $post;

        if ( ! $post ) {
            return '';
        }

        $content  = apply_filters( 'the_content', $post->post_content );
        $headings = $this->extract_headings( $content );

        if ( count( $headings ) < 2 ) {
            return '';
        }

        return $this->build_toc_html( $headings );
    }

    private function extract_headings( $content ) {
        $headings = [];

        preg_match_all(
            '/<(h[23])[^>]*id=["\']([^"\']+)["\'][^>]*>(.*?)<\/\1>/i',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach ( $matches as $match ) {
            $headings[] = [
                'level' => (int) substr( $match[1], 1 ),
                'id'    => $match[2],
                'text'  => strip_tags( $match[3] ),
            ];
        }

        return $headings;
    }

    private function build_toc_html( $headings ) {
        $html  = '<nav class="gc-toc" aria-label="Inhaltsverzeichnis">';
        $html .= '<div class="gc-toc-header">';
        $html .= '<h4 class="gc-toc-title">Inhaltsverzeichnis</h4>';
        $html .= '<button class="gc-toc-toggle" aria-expanded="true" aria-controls="gc-toc-list">';
        $html .= '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>';
        $html .= '</button>';
        $html .= '</div>';
        $html .= '<ol class="gc-toc-list" id="gc-toc-list">';

        $current_level = 2;

        foreach ( $headings as $heading ) {
            if ( $heading['level'] > $current_level ) {
                $html .= '<ol class="gc-toc-sublist">';
            } elseif ( $heading['level'] < $current_level ) {
                $html .= '</li></ol>';
            } elseif ( $heading !== $headings[0] ) {
                $html .= '</li>';
            }

            $html .= '<li class="gc-toc-item" data-level="' . $heading['level'] . '">';
            $html .= '<a href="#' . esc_attr( $heading['id'] ) . '" class="gc-toc-link">'
                . esc_html( $heading['text'] ) . '</a>';

            $current_level = $heading['level'];
        }

        $html .= '</li></ol></nav>';

        return $html;
    }

    private function generate_id( $text ) {
        $id = sanitize_title( $text );
        $id = preg_replace( '/[^a-z0-9\-]/', '', $id );
        return $id ?: 'section-' . wp_rand( 1000, 9999 );
    }
}
