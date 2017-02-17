<?php
 /*Template Name: New Template
 */
get_header(); ?>
<div class="wrap">
    <div id="content" role="main">
    <?php
    $mypost = array( 'post_type' => 'films',
                   'category_name' => $wp->query_vars["category_name"]);
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="clear:both;">
            <header class="entry-header">
                <!-- Display featured image in right-aligned floating div -->
                <a href="<? echo get_permalink( $post->ID );?>">
                    <div style="float: left; margin: 10px">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </div>
                    <h1 style="display:inline-block; padding-top:5px;"><?php the_title(); ?></h1>
                    <h4 style="display:inline-block"><?php echo esc_html( get_post_meta( get_the_ID(), 'subtitle', true ) ); ?></h4>
                </a>
                <? 
                    $product = get_product(get_post_meta( get_the_ID(), 'product_id', true ));
                    if($product){
                ?>
                <br>
                Price: <?php echo $product->get_price()." ".get_woocommerce_currency_symbol()?>
                <br>
                    <? echo "<button><a href='" . $product->add_to_cart_url() ."' style='color:#FFF'>Buy in one click</a></button>";?>
                <br />
                <?
                    }
                ?>
            </header>
        </article>
    <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>