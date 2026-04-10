<?php
/**
 * Taxonomy archive template for gc_category, gc_model, gc_model_year, gc_component
 */

get_header();

$term       = get_queried_object();
$taxonomy   = $term->taxonomy;
$parent     = null;

// Get parent term for breadcrumb
if ( $term->parent ) {
    $parent = get_term( $term->parent, $taxonomy );
}

// Labels for taxonomies
$tax_labels = [
    'gc_category'   => 'Technik-Kategorien',
    'gc_model'      => 'Modelle',
    'gc_model_year' => 'Modelljahre',
    'gc_component'  => 'Bauteile',
];

$tax_label = $tax_labels[ $taxonomy ] ?? 'Kategorien';
?>

<div class="gc-archive-wrapper">

    <!-- Breadcrumb + Back Navigation -->
    <nav class="gc-breadcrumb gc-taxonomy-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'gc_article' ) ); ?>">Technik-DB</a>
        <span class="gc-breadcrumb-sep">&rsaquo;</span>
        <span><?php echo esc_html( $tax_label ); ?></span>

        <?php if ( $parent ) : ?>
            <span class="gc-breadcrumb-sep">&rsaquo;</span>
            <a href="<?php echo esc_url( get_term_link( $parent ) ); ?>"><?php echo esc_html( $parent->name ); ?></a>
        <?php endif; ?>

        <span class="gc-breadcrumb-sep">&rsaquo;</span>
        <span class="gc-breadcrumb-current"><?php echo esc_html( $term->name ); ?></span>
    </nav>

    <!-- Header -->
    <header class="gc-archive-header">
        <h1 class="gc-archive-title"><?php echo esc_html( $term->name ); ?></h1>

        <?php if ( $term->description ) : ?>
            <p class="gc-archive-description"><?php echo esc_html( $term->description ); ?></p>
        <?php endif; ?>

        <div class="gc-taxonomy-meta">
            <span class="gc-meta-item"><?php echo $term->count; ?> Artikel in dieser Kategorie</span>
        </div>
    </header>

    <!-- Inline Search -->
    <?php echo do_shortcode( '[gc_technik_suche]' ); ?>

    <!-- Subcategories (if hierarchical and has children) -->
    <?php
    $children = get_terms( [
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'parent'     => $term->term_id,
    ] );

    if ( ! is_wp_error( $children ) && ! empty( $children ) ) :
    ?>
        <section class="gc-subcategories-section">
            <h2>Unterkategorien</h2>
            <div class="gc-category-grid">
                <?php foreach ( $children as $child ) : ?>
                    <div class="gc-category-card">
                        <a href="<?php echo esc_url( get_term_link( $child ) ); ?>" class="gc-category-card-link">
                            <h3 class="gc-category-card-title"><?php echo esc_html( $child->name ); ?></h3>
                            <span class="gc-category-card-count"><?php echo $child->count; ?> Artikel</span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Articles in this term -->
    <?php if ( have_posts() ) : ?>
        <section class="gc-taxonomy-articles">
            <h2>Artikel</h2>
            <div class="gc-article-list">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article class="gc-article-list-item">
                        <div class="gc-article-list-content">
                            <?php
                            $cats = wp_get_post_terms( get_the_ID(), 'gc_category', [ 'fields' => 'names' ] );
                            if ( ! empty( $cats ) ) :
                            ?>
                                <span class="gc-article-card-badge"><?php echo esc_html( $cats[0] ); ?></span>
                            <?php endif; ?>

                            <h3 class="gc-article-list-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <p class="gc-article-list-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 30 ); ?></p>

                            <div class="gc-article-list-meta">
                                <?php
                                $vw_code = get_post_meta( get_the_ID(), 'gc_vw_code', true );
                                if ( $vw_code ) :
                                ?>
                                    <span class="gc-badge gc-badge-code"><?php echo esc_html( $vw_code ); ?></span>
                                <?php endif; ?>

                                <?php
                                $models = wp_get_post_terms( get_the_ID(), 'gc_model', [ 'fields' => 'names' ] );
                                foreach ( $models as $model ) :
                                ?>
                                    <span class="gc-badge"><?php echo esc_html( $model ); ?></span>
                                <?php endforeach; ?>

                                <?php
                                $location = get_post_meta( get_the_ID(), 'gc_location', true );
                                if ( $location ) :
                                ?>
                                    <span class="gc-badge"><?php echo esc_html( $location ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a href="<?php the_permalink(); ?>" class="gc-article-list-arrow" aria-label="Artikel lesen">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination( [
                'prev_text' => '&laquo; Zurück',
                'next_text' => 'Weiter &raquo;',
            ] ); ?>
        </section>
    <?php else : ?>
        <div class="gc-search-no-results" style="display:block;">
            <p>Noch keine Artikel in dieser Kategorie. Schau bald wieder vorbei!</p>
        </div>
    <?php endif; ?>

    <!-- Back to overview -->
    <div class="gc-back-link">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'gc_article' ) ); ?>">
            &laquo; Zurück zur Technik-Datenbank
        </a>
    </div>

</div>

<?php get_footer(); ?>
