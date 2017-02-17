<?php
 /*Template Name: New Template
 */
get_header(); ?>
<div class="wrap">
    <div id="content" role="main">
    <?php
    $mypost = array( 'post_type' => 'films' );
    $loop = new WP_Query( $mypost );
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header" style="text-align:center">
                <!-- Display featured image in right-aligned floating div -->
                <div style="float: right; margin: 10px">
                    <?php //the_post_thumbnail(); ?>
                </div>
                <!-- Display Title and Author Name -->
                <?
                    $product = get_product(get_post_meta( get_the_ID(), 'product_id', true ));
                ?>
                <h1><?php the_title(); ?></h1>
                <h4><?php echo esc_html( get_post_meta( get_the_ID(), 'subtitle', true ) ); ?></h4>
                Price: <?php echo $product->get_price()." ".get_woocommerce_currency_symbol()?>
                <br>
                    <? echo "<button><a href='" . $product->add_to_cart_url() ."' style='color:#FFF'>Buy in one click</a></button>";?>
                <br />
            </header>
            <!-- Display movie review contents -->
            <div class="entry-content"><?php the_content(); ?></div>
        </article>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>