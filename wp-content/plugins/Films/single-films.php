<?php
 /*Template Name: New Template
 */
get_header(); ?>
<div class="wrap">
    <div id="content" role="main">
    <?php
    $mypost = array( 'post_type' => 'films', );
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header" style="text-align:center">
                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php the_post_thumbnail(); ?>
                </div>
                <!-- Display Title and Author Name -->
                <h1><?php the_title(); ?></h1>
                <h4><?php echo esc_html( get_post_meta( get_the_ID(), 'subtitle', true ) ); ?></h4>
                <form action="" method="post">
                    <input name="add-to-cart" type="hidden" value="<?php echo $post->ID ?>" />
                    <input name="quantity" type="number" value="1" min="1"  />
                    <input name="submit" type="submit" value="Add to cart" />
                </form>
                <br />
            </header>
            <!-- Display movie review contents -->
            <div class="entry-content"><?php the_content(); ?></div>
        </article>
    <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>