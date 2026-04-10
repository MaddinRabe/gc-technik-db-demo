<?php
/**
 * Single template for gc_article post type
 */

get_header(); ?>

<div class="gc-single-wrapper">
    <?php while ( have_posts() ) : the_post(); ?>

        <article class="gc-single-article">
            <header class="gc-single-header">
                <?php
                $categories = wp_get_post_terms( get_the_ID(), 'gc_category' );
                if ( ! empty( $categories ) ) :
                ?>
                    <nav class="gc-breadcrumb" aria-label="Breadcrumb">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'gc_article' ) ); ?>">Technik-DB</a>
                        <?php foreach ( $categories as $cat ) : ?>
                            <span class="gc-breadcrumb-sep">&rsaquo;</span>
                            <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
                        <?php endforeach; ?>
                    </nav>
                <?php endif; ?>

                <h1 class="gc-single-title"><?php the_title(); ?></h1>

                <div class="gc-single-meta">
                    <?php
                    $models = wp_get_post_terms( get_the_ID(), 'gc_model', [ 'fields' => 'names' ] );
                    $years  = wp_get_post_terms( get_the_ID(), 'gc_model_year', [ 'fields' => 'names' ] );
                    ?>

                    <?php if ( ! empty( $models ) ) : ?>
                        <span class="gc-meta-item gc-meta-model">
                            <?php echo esc_html( implode( ', ', $models ) ); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( ! empty( $years ) ) : ?>
                        <span class="gc-meta-item gc-meta-year">
                            MJ <?php echo esc_html( implode( ', ', $years ) ); ?>
                        </span>
                    <?php endif; ?>

                    <span class="gc-meta-item gc-meta-date">
                        Aktualisiert: <?php echo get_the_modified_date(); ?>
                    </span>
                </div>
            </header>

            <?php
            // Technical details sidebar
            $meta_fields = [
                'gc_vw_code'      => 'VW-Code',
                'gc_fuse_rating'  => 'Sicherungswert',
                'gc_location'     => 'Einbauort',
                'gc_tools_needed' => 'Werkzeug',
                'gc_torque_specs' => 'Drehmomente',
                'gc_wire_colors'  => 'Kabelfarben',
            ];

            $has_meta = false;
            foreach ( $meta_fields as $key => $label ) {
                if ( get_post_meta( get_the_ID(), $key, true ) ) {
                    $has_meta = true;
                    break;
                }
            }
            ?>

            <div class="gc-single-layout">
                <div class="gc-single-content">
                    <?php if ( $has_meta ) : ?>
                        <div class="gc-tech-details">
                            <h4>Technische Details</h4>
                            <dl class="gc-tech-details-list">
                                <?php foreach ( $meta_fields as $key => $label ) :
                                    $value = get_post_meta( get_the_ID(), $key, true );
                                    if ( ! $value ) continue;
                                ?>
                                    <dt><?php echo esc_html( $label ); ?></dt>
                                    <dd><?php echo nl2br( esc_html( $value ) ); ?></dd>
                                <?php endforeach; ?>
                            </dl>
                        </div>
                    <?php endif; ?>

                    <div class="gc-article-body">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    $components = wp_get_post_terms( get_the_ID(), 'gc_component' );
                    if ( ! empty( $components ) ) :
                    ?>
                        <div class="gc-component-tags">
                            <strong>Verwandte Bauteile:</strong>
                            <?php foreach ( $components as $comp ) : ?>
                                <a href="<?php echo esc_url( get_term_link( $comp ) ); ?>" class="gc-component-tag">
                                    <?php echo esc_html( $comp->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <aside class="gc-single-sidebar">
                    <div class="gc-toc-sidebar" id="gc-toc-sidebar">
                        <!-- TOC wird automatisch via Filter eingefügt -->
                    </div>
                </aside>
            </div>

            <nav class="gc-article-navigation">
                <?php
                $prev = get_previous_post( true, '', 'gc_category' );
                $next = get_next_post( true, '', 'gc_category' );
                ?>
                <?php if ( $prev ) : ?>
                    <a href="<?php echo get_permalink( $prev ); ?>" class="gc-nav-prev">
                        &laquo; <?php echo esc_html( $prev->post_title ); ?>
                    </a>
                <?php endif; ?>

                <?php if ( $next ) : ?>
                    <a href="<?php echo get_permalink( $next ); ?>" class="gc-nav-next">
                        <?php echo esc_html( $next->post_title ); ?> &raquo;
                    </a>
                <?php endif; ?>
            </nav>
        </article>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
