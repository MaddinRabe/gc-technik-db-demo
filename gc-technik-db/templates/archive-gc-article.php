<?php
/**
 * Archive template for gc_article post type
 */

get_header(); ?>

<div class="gc-archive-wrapper">
    <header class="gc-archive-header">
        <h1 class="gc-archive-title">Technik-Datenbank</h1>
        <p class="gc-archive-description">
            Technische Informationen, Sicherungspläne, Stromlaufpläne und Reparaturanleitungen für den VW Grand California.
        </p>
    </header>

    <?php echo do_shortcode( '[gc_technik_suche]' ); ?>

    <section class="gc-categories-overview">
        <h2>Kategorien</h2>
        <div class="gc-category-grid">
            <?php
            $categories = get_terms( [
                'taxonomy'   => 'gc_category',
                'hide_empty' => false,
                'parent'     => 0,
            ] );

            if ( ! is_wp_error( $categories ) ) :
                foreach ( $categories as $category ) :
                    $children = get_terms( [
                        'taxonomy'   => 'gc_category',
                        'hide_empty' => false,
                        'parent'     => $category->term_id,
                    ] );
                    $count = $category->count;
                    if ( ! is_wp_error( $children ) ) {
                        foreach ( $children as $child ) {
                            $count += $child->count;
                        }
                    }
                    ?>
                    <div class="gc-category-card">
                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="gc-category-card-link">
                            <h3 class="gc-category-card-title"><?php echo esc_html( $category->name ); ?></h3>
                            <span class="gc-category-card-count"><?php echo $count; ?> Artikel</span>
                        </a>

                        <?php if ( ! is_wp_error( $children ) && ! empty( $children ) ) : ?>
                            <ul class="gc-subcategory-list">
                                <?php foreach ( $children as $child ) : ?>
                                    <li>
                                        <a href="<?php echo esc_url( get_term_link( $child ) ); ?>">
                                            <?php echo esc_html( $child->name ); ?>
                                            <span class="gc-count">(<?php echo $child->count; ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
        </div>
    </section>

    <?php if ( have_posts() ) : ?>
        <section class="gc-latest-articles">
            <h2>Neueste Artikel</h2>
            <div class="gc-article-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article class="gc-article-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="gc-article-card-thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'medium' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="gc-article-card-content">
                            <?php
                            $cats = wp_get_post_terms( get_the_ID(), 'gc_category', [ 'fields' => 'names' ] );
                            if ( ! empty( $cats ) ) :
                            ?>
                                <span class="gc-article-card-badge"><?php echo esc_html( $cats[0] ); ?></span>
                            <?php endif; ?>

                            <h3 class="gc-article-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <p class="gc-article-card-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>

                            <?php
                            $vw_code = get_post_meta( get_the_ID(), 'gc_vw_code', true );
                            if ( $vw_code ) :
                            ?>
                                <span class="gc-article-card-code"><?php echo esc_html( $vw_code ); ?></span>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination( [
                'prev_text' => '&laquo; Zurück',
                'next_text' => 'Weiter &raquo;',
            ] ); ?>
        </section>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
