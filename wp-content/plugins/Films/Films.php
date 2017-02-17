<?php
/*
Plugin Name: Films
Description: Declares a plugin that will create a custom post type displaying films.
Version: 1.0
Author: Kolomiets Igor
*/

//2. Добавить новый post type "фильмы" с опциями: заголовок, подзаголовок, контент, картинка, категория.
add_action( 'init', 'create_film' );
function create_film() {
    register_post_type( 'films',
        array(
            'labels' => array(
                'name' => 'Films',
                'singular_name' => 'Film',
                'add_new' => 'Add New Film',
                'add_new_item' => 'Add New Film',
                'edit' => 'Edit',
                'edit_item' => 'Edit Film',
                'new_item' => 'New Film',
                'view' => 'View',
                'view_item' => 'View Film',
                'search_items' => 'Search Film',
                'not_found' => 'No Film found',
                'not_found_in_trash' => 'No Film found in Trash',
                'parent' => 'Parent Film'
            ),
            'public' => true,
            'menu_position' => 15,
            'heirarchial' =>true,
            'supports' => array( 'title', 'editor', 'thumbnail'),
            'taxonomies' => array( 'category' ),
            'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
            'has_archive' => true,
            'rewrite' => array('slug' => 'films'),
        )
    );
}

add_action( 'admin_init', 'my_admin' );
function my_admin() {
    add_meta_box( 'film_meta_box',
        'Custom attributes',
        'display_film_meta_box',
        'films', 'normal', 'high'
    );
}

function display_film_meta_box( $film ) {
    // Retrieve current name of the Director and Movie Rating based on review ID
    $subtitle = esc_html( get_post_meta( $film->ID, 'subtitle', true ) );
    $product_id = esc_html( get_post_meta( $film->ID, 'product_id', true ) );
    ?>
    <table>
        <tr>
            <td style="width: 100%">Subtitle</td>
            <td><input type="text" size="70" name="subtitle" value="<?php echo $subtitle; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Product id</td>
            <td><input type="text" size="40" name="product_id" value="<?php echo $product_id; ?>" /></td>
        </tr>
    </table>
    <?php
}

add_action( 'save_post', 'add_film_fields', 10, 2 );
function add_film_fields( $movie_review_id, $movie_review ) {
    // Check post type for movie reviews
    var_dump($movie_review->post_type);
    if ( $movie_review->post_type == 'films' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['subtitle'] ) && $_POST['subtitle'] != '' ) {
            update_post_meta( $movie_review_id, 'subtitle', $_POST['subtitle'] );
        }
        if ( isset( $_POST['product_id'] ) && $_POST['product_id'] != '' ) {
            update_post_meta( $movie_review_id, 'product_id', $_POST['product_id'] );
        }
    }
}

//5. Добавить новое поле skype в форму регистрации.

add_action('register_form','show_fields');
add_action('register_post','check_fields',10,3);
add_action('user_register', 'register_fields');
 
function show_fields() {
?>
<p>
	<label>Skype<br/>
	<input id="skype" class="input" type="text" name="skype" /></label>
</p>
<?php }
 
function check_fields ( $login, $email, $errors ) {
	global $skype;
	if ($_POST['skype'] == ''){
		$errors->add( 'empty_realname', "ОШИБКА: Поле Skype не может быть пустым" );
	} else {
		$skype = $_POST['skype'];
	}
	return $errors;
}
 
function register_fields($user_id,$password= "",$meta=array()){
	update_user_meta( $user_id, 'skype', $_POST['skype'] );
}

// 6. После регистрации пользователь должен попадать на страницу с избранными фильмами.

function wph_reg_redirect(){
    return home_url('/category/films/favorite-films/');
}
add_filter('registration_redirect', 'wph_reg_redirect');
//перенаправление после регистрации end

//7. После нажатия на кнопку купить пользователь должен быть направлен на оплату paypal минуя корзину.
//Корзину я обошел но дальше нужно собрать данные для PayPal API

add_filter ('add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout() {
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}

add_action("template_redirect", 'my_template_redirect');
// Template selection Defines the template for the custom post types.
function my_template_redirect()
  {
  global $wp;
  global $wp_query;
  if (stripos($wp->query_vars["category_name"],"films") !== false)
  {
      //var_dump($wp->query_vars["category_name"]);
    $mypost = array( 'post_type' => 'films' );
    $loop = new WP_Query( $mypost );
      //var_dump(new WP_Query('category_name=films'));
    if ($loop->have_posts())
      {
          include(TEMPLATEPATH . '/archive-films.php');
          die();
      }
      else
      {
          $wp_query->is_404 = true;
      }
    }
}

?>